<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilController extends Controller
{
    public function graphicTipos()
    {
        return [
            'grafica_1' => 'Gráfica 1',
            'grafica_2' => 'Gráfica 2'
        ];
    }
}
