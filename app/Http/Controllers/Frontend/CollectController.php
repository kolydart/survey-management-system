<?php

namespace App\Http\Controllers\Frontend;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionnaire;
use App\Questionnaire;
use App\Response;
use App\Survey;
use App\User;
use App\Mail\QuestionnaireSubmitted;
use Kolydart\Common\Cipher;
use Kolydart\Common\Presenter;
use Illuminate\Support\Facades\Mail;

/**
 * public controller for colecting data
 */
class CollectController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($alias)
    {
        $survey = Survey::where('alias', $alias)->firstOrFail();

        // Log activity for non-authorized users only
        if (!auth()->check()) {
            activity()
                ->performedOn($survey)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->url(),
                ])
                ->log('survey_view');
        }


        if ($survey->completed) {
            Presenter::message('Survey is completed.', 'warning');

            return view('frontend.index', ['content'=>'']);
        } else {
            $questionnaire = new Questionnaire();
            $hidden_answer = Answer::hidden();

            return view('frontend.create', compact('survey', 'questionnaire', 'hidden_answer'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @example data received:
     *  [
     *    "2136_id" => "68"
     *
     *    "2141_id" => "71"
     *    "2141_id" => "74"
     *    "2141_content_74" => "open ended answer"
     *  ]
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionnaire $request)
    {
        /** abort if survey is completed */
        if (Survey::find($request->survey_id)->completed == 1) {
            abort(404, __('The survey has been completed.'));
        }


        /**
         * get name if exists
         * variable name: "check"
         */
        if (\Auth::id()) {
            $name = \Auth::user()->name;
        } elseif (request()->get('check') && env('CIPHER_KEY')) {
            $name = (new Cipher)->decrypt(request()->get('check'), env('CIPHER_KEY'));
        } else {
            $name = null;
        }

        /** debug */
        // die(\Kolydart\Common\Presenter::dd($request->all()));

        /** create questionnaire */
        $questionnaire = Questionnaire::create(['survey_id'=>$request->survey_id, 'name' => $name]);

        $request_array = $request->except(['_token', 'survey_id']); // notice that $request->validated() does not return wildcarderd field names

        /** create responses */
        foreach ($request_array as $key => $value) {
            $array = explode('_', $key);

            if ($array[1] == 'id') {
                $question_id = (int) $array[0];
                $answer_id = (int) $value;
                $content = trim(strip_tags($request_array[$question_id.'_content_'.$answer_id] ?? ''));

                Response::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $question_id,
                    'answer_id' => $answer_id,
                    'content' => $content,
                ]);
            } elseif ($array[1] != 'content') {
                Presenter::mail("Error rkBECq.\nResponse was not created.\n$key => $value\n");
                Presenter::message(__('A question was not submitted, due to invalid data.')." Question $question_id");
            }
        }

        // Audit questionnaire submission - use appropriate system based on auth status
        if (auth()->check()) {
            // Authorized user - use Kolydart Auditable (create direct audit entry)
            \App\AuditLog::create([
                'description' => 'questionnaire_submit',
                'subject_id' => $questionnaire->id,
                'subject_type' => get_class($questionnaire),
                'user_id' => auth()->id(),
                'properties' => [
                    'survey_id' => $questionnaire->survey_id,
                    'responses_count' => count($request_array),
                    'user_agent' => request()->userAgent(),
                ],
                'host' => request()->ip(),
            ]);
        } else {
            // Non-authorized user - use Spatie ActivityLog
            activity()
                ->performedOn($questionnaire)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'survey_id' => $questionnaire->survey_id,
                    'responses_count' => count($request_array),
                ])
                ->log('questionnaire_submit');
        }

        /** send email if field "informed" is checked */
        if ($questionnaire->survey->inform) {
            try {
                $user = User::first();
                if ($user) {
                    Mail::to($user->email, $user->name)
                        ->send(new QuestionnaireSubmitted($questionnaire));
                }
            } catch (\Exception $e) {
                Presenter::mail('Error in mailer. kBSaSOfrFchbehAa.' . $e->getMessage());
            }
        }

        /** use cookies to check if user has filled the same survey questionnaire */
        try {
            /** cookie exists */
            if (\Cookie::get('survey_'.$request->survey_id)) {
                $adminUrl = url('/admin/questionnaires/');
                // send message with ip & survey_id's
                Presenter::mail(
                    'Survey '.$request->survey_id." questionnaire filled twice in the same browser.\n"
                    .'Old questionnaire: '.$adminUrl.\Cookie::get('questionnaire')."\n"
                    .'New questionnaire: '.$adminUrl.$questionnaire->id."\n"
                );
            }
            /** set new cookie */
            \Cookie::queue(\Cookie::make('survey_'.$request->survey_id, true, 2880));
            \Cookie::queue(\Cookie::make('questionnaire', $questionnaire->id, 2880));
        } catch (\Exception $e) {
            $message = 'Could not handle cookies. Error vWhDRFPtoQMnGMes. Code: '.$e->getCode();
            Presenter::log($message, 'cookies');
            Presenter::mail($message);
        }

        /** Thank you message */
        $content = '
            <div class="alert alert-success col-md-8 col-md-offset-2" style="margin-top:30px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h3 class="text-center">'.__('Το ερωτηματολόγιο υποβλήθηκε με επιτυχία!').'</h3>
            </div>';

        return view('frontend.index', compact('content'));
    }

    public function index()
    {
        /** redirect to admin panel if user is logged-in */
        if (\Auth::check() && \Gate::allows('survey_access')) {
            return redirect(route('admin.home'));
        }
        $landing_text = <<<'HTML'
        <div class="jumbotron bg-white" style="background-color:white;">
            <h1 class="lead text-light display-fix">survey</h1>
            <p class="lead">Εφαρμογή για την συμπλήρωση ερωτηματολογίων</p>
        </div>
HTML;
        $content = env('LANDING_TEXT', $landing_text);

        // Log activity for non-authorized users only
        if (!auth()->check()) {
            activity()
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->url(),
                ])
                ->log('landing_page_view');
        }

        return view('frontend.index', compact('content'));
    }
}
