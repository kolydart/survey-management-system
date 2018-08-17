<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionnaire;
use App\Questionnaire;
use App\Response;
use App\Survey;
use Illuminate\Http\Request;
use gateweb\common\Presenter;
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
        if($survey->completed){
            Presenter::message('Η έρευνα έχει ολοκληρωθεί.','warning');
            $content = '';
            return view('public.main',compact('content'));
        }
        else{
            $questionnaire = new Questionnaire;
            return view('public.create',compact('survey','questionnaire'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionnaire $request)
    {

        $validated = $request->validated();
dd($validated);
        // return \gateweb\common\Presenter::dd($request->all());
        foreach ($validated as $key => $value) {
            echo "$key: $value<br>";
        }
        
        // $validatedData = $request->validate([
        //         'title' => 'required|unique:posts|max:255',
        //         'body' => 'required',
        //     ]);        
        // $questionnaire_id = Questionnaire::create();
        // foreach ($responses as $response) {
        //     Response::create([
        //         'questionnaire_id' => $response->questionnaire_id,
        //         'question_id' => $response->question_id,
        //         'answer_id' => $response->answer_id,
        //         'content' => $response->content
        //     ]);
        // }
        // $questionnaire_id = Questionnaire::create();
        
        // $item_id = $questionnaire_id;

        // (new LogUserAgent())->snapshot(['item_id'=>$item_id],false);
    }

    public function index(){
        $content = <<<HTML
            <div class="jumbotron">
                <h1>Survey application</h1>
                <p>Houston, we have contact!...</p>
                <!-- <p><a class="btn btn-primary btn-lg">Learn more</a></p> -->
            </div>
HTML;
        return view('public.main',compact('content'));
    }
    

}
