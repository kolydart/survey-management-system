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
    	// $questionnaire_id = Questionnaire::create();

        (new LogUserAgent())->snapshot(['item_id'=>3],false);
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


}
