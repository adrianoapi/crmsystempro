<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HistoryController extends UtilController
{
    private $title  = 'RETORNO';


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = $this->title. " Dashboard";

        $modulo = NULL;
        $query  = NULL;

        if(array_key_exists('modulo',$_GET))
        {
            if(!empty($_GET['modulo']))
            {
                if($_GET['modulo'] == 'cheque')
                {
                    $modulo = 'cheque';
                    $query = "AND bc.id > 0";

                }elseif($_GET['modulo'] == 'grafica_1'){
                    $modulo = 'grafica_1';
                    $query = "AND grt.id > 0 AND tipo = 'grafica_1'";
                }elseif($_GET['modulo'] == 'grafica_2'){
                    $modulo = 'grafica_2';
                    $query = "AND grt.id > 0 AND tipo = 'grafica_2'";
                }else{
                    $modulo = $_GET['modulo'];
                    if($modulo == 'contrato_segunda'){
                        $query = "AND det.id > 0 AND de.fase = 'segunda'";
                    }else{
                        $query = "AND det.id > 0 AND de.fase = 'terceira'";
                    }
                }
            }

        }

        $result = DB::select("SELECT gr.tipo, de.fase, st.cod_unidade, st.cod_curso, st.ctr, st.name, st.cpf_cnpj,
        if(bc.id > 0,bc.id, if(de.id > 0, de.id, gr.id)) AS modulo_id,
        if(bc.id > 0,bct.id, if(de.id > 0, det.id, grt.id)) AS id,
        if(bc.id > 0,'cheque', if(de.id > 0, 'contrato', 'grafica')) AS modulo,
        if(bc.id > 0, bct.observacao, if(de.id > 0, det.observacao, grt.observacao)) AS observacao,
        DATE_FORMAT(if(bc.id > 0, bct.created_at, if(de.id > 0, det.created_at, grt.created_at)), '%d/%m/%Y') AS created_at,
        DATE_FORMAT(if(bc.id > 0, bct.dt_retorno, if(de.id > 0, det.dt_retorno, grt.dt_retorno)), '%d/%m/%Y %H:%i:%s') AS dt_retorno,
        if(bc.id > 0, bct.schedule, if(de.id > 0, det.schedule, grt.schedule)) as schedule
        FROM students AS st
        LEFT JOIN bank_cheques AS bc
               ON (st.id = bc.student_id)
        LEFT JOIN bank_cheque_histories AS bct
               ON (bc.id = bct.bank_cheque_id)
        LEFT JOIN defaultings  AS de
               ON (st.id = de.student_id)
        LEFT JOIN defaulting_histories AS det
               ON (de.id = det.defaulting_id)
        LEFT JOIN graphics     AS gr
               ON (st.id = gr.student_id)
        LEFT JOIN graphic_histories AS grt
               ON (gr.id = grt.graphic_id)
        where
        if(bc.id > 0, bct.schedule, if(de.id > 0, det.schedule, grt.schedule)) = 'open'
        {$query}
        order by if(bc.id > 0, bct.dt_retorno, if(de.id > 0, det.dt_retorno, grt.dt_retorno)) ASC
        limit 30000"
        );


        return view('historical.index', [
            'title' => $title,
            'modulo' => $modulo,
            'historical' => $result,
            'tipos' => $this->graphicTipos()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        switch ($request->modulo) {
            case 'cheque':
                $modulo = \App\BankChequeHistory::where('id', $request->id)
                ->where('schedule','open')
                ->firstOrFail();
                break;
            case 'contrato':
                $modulo = \App\DefaultingHistory::where('id', $request->id)
                ->where('schedule','open')
                ->firstOrFail();
                break;
            default:
                $modulo = \App\GraphicHistory::where('id', $request->id)
                ->where('schedule','open')
                ->firstOrFail();
                break;
        }

        if(!empty($modulo))
        {
            $modulo->user_id  = Auth::id();
            $modulo->schedule = 'close';
            if($modulo->save())
            {
                if($request->tela == 'listagem'){
                    return redirect()->route('history.index');
                }else{

                    if($request->modulo == 'cheque')
                    {
                        return redirect()->route('bankCheques.show', ['bankCheque' => $modulo->bank_cheque_id]);
                    }
                    elseif($request->modulo == 'contrato')
                    {
                        return redirect()->route('defaultings.show', ['defaulting' => $modulo->defaulting_id]);
                    }
                    else
                    {
                        return redirect()->route('graphics.show', ['graphic' => $modulo->graphic_id]);
                    }
                }
            }
        }else{
            die('Registro não encontrado!');
        }

    }

}
