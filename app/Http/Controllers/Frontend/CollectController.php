<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionnaire;
use App\Questionnaire;
use App\Response;
use App\Survey;
use App\User;
use Illuminate\Http\Request;
use gateweb\common\Cipher;
use gateweb\common\Mailer;
use gateweb\common\Presenter;
use gateweb\common\Router;
use gateweb\common\database\LogUserAgent;

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
        $survey = Survey::where('alias',$alias)->firstOrFail();

        (new LogUserAgent())->snapshot(['item_id'=>$survey->id],false);

        if($survey->completed){
            Presenter::message('Survey is completed.','warning');
            return view('frontend.index',['content'=>'']);
        }
        else{
            $questionnaire = new Questionnaire();
            return view('frontend.create',compact('survey','questionnaire'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @example data received: 
     *  [
     *    "2136_id" => "68"
     *    "2141_id_71" => "71"
     *    "2141_id_74" => "74"
     *    "2141_content_74" => "asdf"
     *  ]
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionnaire $request)
    {
        /** abort if survey is completed */
        if(Survey::find($request->survey_id)->completed == 1)
            abort(404,__('The survey has been completed.'));

        $router = new Router();

        /**
         * get name if exists
         * variable name: "check"
         * 
         */
        if(\Auth::id())
            $name = \Auth::user()->name;
        elseif($router->get_var('check') && env('CIPHER_KEY')){
            $name = (new Cipher)->decrypt($router->get_var('check'), env('CIPHER_KEY'));
        }else{
            $name = null;
        }

        /** create questionnaire */
        $questionnaire = Questionnaire::create(['survey_id'=>$request->survey_id, 'name' => $name]);

        $request_array = $request->except(['_token','survey_id']); // notice that $request->validated() does not return wildcarderd field names

        /** create responses */
        foreach ($request_array as $key => $value) {
            $arr = explode('_',$key);

            if($arr[1] == 'id'){
                $question_id = $router->sanitize($arr[0],'int');
                $answer_id = $router->sanitize($value,'int');
                $content = $router->sanitize(
                    isset($request_array[$question_id.'_content_'.$answer_id])?$request_array[$question_id.'_content_'.$answer_id]:'',
                    'text',
                    ''
                );
                Response::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $question_id,
                    'answer_id' => $answer_id,
                    'content' => $content
                ]);

            } elseif($arr[1] != 'content'){
                Presenter::mail("Error rkBECq.\nResponse was not created.\n$key => $value\n");
                Presenter::message(__('A question was not submitted, due to invalid data.')." Question $question_id");
            }

        }
        
        (new LogUserAgent())->snapshot(['item_id'=>$questionnaire->id],false);

        /** send email if field "informed" is checked */
        if ($questionnaire->survey->inform) {
            $mailer = new Mailer();
            $mailer->set_subject("New questionnaire for survey «".$questionnaire->survey->title."»");
            $mailer->set_body(route('admin.questionnaires.show',$questionnaire->id));
            $mailer->set_to(User::first()->email, User::first()->name);
            if (!$mailer->Send()){
               Presenter::mail("Error in mailer. kBSaSOfrFchbehAa.".$mailer->get_error());
            }
        }

        /** use cookies to check if user has filled the same survey questionnaire */
        try {
            /** cookie exists */
            if(\Cookie::get('survey_'.$request->survey_id)){
                $rtr = clone $router;
                $rtr->set_path('/admin/questionnaires/');
                // send message with ip & survey_id's
                Presenter::mail(
                    "Survey " . $request->survey_id . " questionnaire filled twice in the same browser.\n"
                    ."Old questionnaire: ". $rtr->get_url().\Cookie::get('questionnaire')."\n"
                    ."New questionnaire: ". $rtr->get_url().$questionnaire->id."\n"
                );
            }
            /** set new cookie */
            \Cookie::queue(\Cookie::make('survey_'.$request->survey_id, true, 2880));
            \Cookie::queue(\Cookie::make('questionnaire', $questionnaire->id, 2880));
        } catch (\Exception $e) {
            $message='Could not handle cookies. Error vWhDRFPtoQMnGMes. Code: '.$e->getCode();
            Presenter::log($message,'cookies');
            Presenter::mail($message);
        }


        /** Thank you message */
        $content = '
            <div class="alert alert-success col-md-8 col-md-offset-2" style="margin-top:30px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h3 class="text-center">'.__('Thank you!').'</h3>
                <p class="text-center">'.__('Το ερωτηματολόγιο υποβλήθηκε με επιτυχία!').'</p>
            </div>'
            ;       
        return view('frontend.index',compact('content'));
    }

    public function index(){
        /** redirect to admin panel if user is logged-in */
        if (\Auth::check() && \Gate::allows('survey_access')) {
            return redirect(route('admin.home'));
        }
        $landing_text = <<<HTML
        <div class="jumbotron bg-white" style="background-color:white;">
            <h1 class="lead text-light display-fix">survey</h1>
            <p class="lead">Εφαρμογή για την συμπλήρωση ερωτηματολογίων</p>
        </div>
HTML;
        $content = env('LANDING_TEXT',$landing_text);
        (new LogUserAgent())->snapshot(null,false);

        return view('frontend.index',compact('content'));
    }
    

}
