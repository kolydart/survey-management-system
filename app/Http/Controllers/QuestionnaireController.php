<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use Illuminate\Http\Request;
use gateweb\common\database\LogUserAgent;

class QuestionnaireController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /** 
         * @todo
         */
        
        // $questionnaire_id = Questionnaire::create();
        
        $item_id = $questionnaire_id;

        (new LogUserAgent())->snapshot(['item_id'=>$item_id],false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function index(){
        return view('public.welcome');
    }
    

}
