<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionnaire;
use App\Questionnaire;
use App\Response;
use App\Survey;
use Illuminate\Http\Request;
use gateweb\common\Cipher;
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
    public function create(Survey $survey)
    {
        if($survey->completed)
            Presenter::message('Survey is completed.','warning');
        $questionnaire = new Questionnaire();
        return view('public.create',compact('survey','questionnaire'));
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
            abort(404,__('Survey is completed'));

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

        $content = '
            <div class="alert alert-success col-md-8 col-md-offset-2" style="margin-top:30px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h3 class="text-center">'.__('Thank you!').'</h3>
            </div>'
            ;       
        return view('public.index',compact('content'));        
    }

    public function index(){
        $content = <<<HTML
            <div class="jumbotron">
                <h1>Survey application</h1>
                <p>Houston, we have contact!...</p>
                <!-- <p><a class="btn btn-primary btn-lg">Learn more</a></p> -->
            </div>
HTML;
        return view('public.index',compact('content'));
    }
    

}
