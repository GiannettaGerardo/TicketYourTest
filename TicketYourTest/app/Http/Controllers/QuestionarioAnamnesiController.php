<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class QuestionarioAnamnesiController
 */
class QuestionarioAnamnesiController extends Controller
{

    /**
     * Restituisce la vista per visualizzare gli errori relativi alla visualizzazione del questionario anamnesi.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaErroreQuestionarioAnamnesi() {
        return view('questionarioAnamnesiError');
    }


    /**
     * Restituisce la vista per compilare il questionario anamnesi
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormQuestionarioAnamnesi($token) {
        return view('formTestAnamnesi');
    }
}
