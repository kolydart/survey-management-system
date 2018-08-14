<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use App\Response;
use App\Survey;
use Illuminate\Http\Request;
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
        $questionnaire = new Questionnaire;
        return view('public.create',compact('survey','questionnaire'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** 
         * @todo
         */
        return \gateweb\common\Presenter::dd($request->all());
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
        
        $item_id = $questionnaire_id;

        (new LogUserAgent())->snapshot(['item_id'=>$item_id],false);
    }

    public function index(){
        return view('public.welcome');
    }
    

}
