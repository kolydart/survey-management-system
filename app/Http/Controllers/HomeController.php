<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $responses = \App\Response::latest()->limit(10)->get(); 
        $questionnaires = \App\Questionnaire::latest()->limit(10)->get(); 

        return view('home', compact( 'responses', 'questionnaires' ));
    }
}
