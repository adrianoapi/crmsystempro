<?php

namespace App\Http\Controllers;

use App\Queued;
use App\Student;
use App\BankCheque;
use App\Graphic;
use App\Defaulting;
use App\BankChequePlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetController extends Controller
{
    private $title  = 'CHEQUE - IMPORTAÇÃO';

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function db()
    {
        if(Auth::user()->level <= 1){
            die('Você não tem permissão!');
        }

        Queued::truncate();
        BankCheque::truncate();
        Graphic::truncate();
        Defaulting::truncate();
        BankChequePlot::truncate();
        Student::truncate();
    }

}
