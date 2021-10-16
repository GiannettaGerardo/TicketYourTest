<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionarioAnamnesiController extends Controller
{
    /**
     * Restituisce la vista per compilare il questionario anamnesi
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function visualizzaFormQuestionarioAnamnesi() {
        return view('formTestAnamnesi');
    }
}
