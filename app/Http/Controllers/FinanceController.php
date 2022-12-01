<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FinanceController extends UtilController
{
    private $title  = 'CAIXA';

    private $dtInicial;
    private $dtFinal;

    public function __construct()
    {
        $this->middleware('auth');

        $this->dtInicial = date('Y-m-01');
        $this->dtFinal   = date('Y-m-t' );
    }

    public function index()
    {
        $title = $this->title. " Dashboard";
        $pagamento = true;

        if(array_key_exists('pagamento', $_GET))
        {
            $this->dtInicial = strlen($_GET['dt_inicio']) > 2 ? $this->dataSql($_GET['dt_inicio']) : $this->dtInicial;
            $this->dtFinal   = strlen($_GET['dt_fim'   ]) > 2 ? $this->dataSql($_GET['dt_fim'   ]) : $this->dtFinal;

            $pagamento = ($_GET['pagamento'] == 'sim') ? true : false;
        }

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

        $pgtEfetuado = ($pagamento) ? 'IS NOT NULL'  : 'IS NULL';
        $dataLimit   = ($pagamento) ? 'dt_pagamento' : 'vencimento';

        $expensive = DB::select("SELECT gr.tipo, de.fase, st.cod_unidade, st.cod_curso, st.ctr, st.name, st.cpf_cnpj, st.telefone, st.telefone_com, st.celular,
        if(bc.id > 0,bc.id, if(de.id > 0, de.id, gr.id)) AS id,
        if(bc.id > 0,'cheque', if(de.id > 0, 'contrato', 'grafica')) AS modulo,
        if(bc.id > 0, bct.parcela, if(de.id > 0, det.parcela, grt.parcela)) AS parcela,
        if(bc.id > 0, bct.valor, if(de.id > 0, det.valor, grt.valor)) AS valor,
        if(bc.id > 0, bct.vencimento, if(de.id > 0, det.vencimento, grt.vencimento)) AS vencimento,
        #Mysql function
        DATE_FORMAT(if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)), '%d/%m/%Y') AS dt_pagamento,
        if(bc.id > 0, bct.valor_pago, if(de.id > 0, det.valor_pago, grt.valor_pago)) AS valor_pago,
        if(bc.id > 0, bct.pagamento, if(de.id > 0, det.pagamento, grt.pagamento)) AS pagamento
        FROM students AS st
        LEFT JOIN bank_cheques AS bc
               ON (st.id = bc.student_id)
        LEFT JOIN bank_cheque_tradings AS bct
               ON (bc.id = bct.bank_cheque_id)
        LEFT JOIN defaultings  AS de
               ON (st.id = de.student_id)
        LEFT JOIN defaulting_tradings AS det
               ON (de.id = det.defaulting_id)
        LEFT JOIN graphics     AS gr
               ON (st.id = gr.student_id)
        LEFT JOIN graphic_tradings AS grt
               ON (gr.id = grt.graphic_id)
        where
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) >= '{$this->dtInicial}' AND
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) <= '{$this->dtFinal}'   AND
        if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)) {$pgtEfetuado} {$query}
        order by st.name ASC
        limit 30000"
        );

        return view('finances.index', [
            'caixa'     => $expensive,
            'title'     => $title,
            'dt_inicio' => $this->dataBr($this->dtInicial),
            'dt_fim'    => $this->dataBr($this->dtFinal),
            'pagamento' => $pagamento,
            'modulo' => $modulo,
            'tipos' => $this->graphicTipos()
        ]);
    }

    public function byDay()
    {
        $title = $this->title. " DIÁRIO Dashboard";
        $pagamento = true;

        if(array_key_exists('pagamento', $_GET))
        {
            $this->dtInicial = strlen($_GET['dt_inicio']) > 2 ? $this->dataSql($_GET['dt_inicio']) : $this->dtInicial;
            $this->dtFinal   = strlen($_GET['dt_fim'   ]) > 2 ? $this->dataSql($_GET['dt_fim'   ]) : $this->dtFinal;
        }

        $pgtEfetuado = ($pagamento) ? 'IS NOT NULL'  : 'IS NULL';
        $dataLimit   = ($pagamento) ? 'dt_pagamento' : 'vencimento';

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

                }elseif($_GET['modulo'] == 'grafica'){
                    $modulo = 'grafica';
                    $query = "AND grt.id > 0";
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

        $expensive = DB::select("SELECT gr.tipo, de.fase, st.cod_unidade, st.cod_curso, st.ctr, st.name, st.cpf_cnpj, st.telefone, st.telefone_com, st.celular,
        if(bc.id > 0,bc.id, if(de.id > 0, de.id, gr.id)) AS id,
        if(bc.id > 0,'cheque', if(de.id > 0, 'contrato', 'grafica')) AS modulo,
        if(bc.id > 0, bct.parcela, if(de.id > 0, det.parcela, grt.parcela)) AS parcela,
        if(bc.id > 0, bct.valor, if(de.id > 0, det.valor, grt.valor)) AS valor,
        if(bc.id > 0, bct.vencimento, if(de.id > 0, det.vencimento, grt.vencimento)) AS vencimento,
        #Mysql function
        DATE_FORMAT(if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)), '%d/%m/%Y') AS dt_pagamento,
        if(bc.id > 0, bct.valor_pago, if(de.id > 0, det.valor_pago, grt.valor_pago)) AS valor_pago,
        if(bc.id > 0, bct.pagamento, if(de.id > 0, det.pagamento, grt.pagamento)) AS pagamento
        FROM students AS st
        LEFT JOIN bank_cheques AS bc
               ON (st.id = bc.student_id)
        LEFT JOIN bank_cheque_tradings AS bct
               ON (bc.id = bct.bank_cheque_id)
        LEFT JOIN defaultings  AS de
               ON (st.id = de.student_id)
        LEFT JOIN defaulting_tradings AS det
               ON (de.id = det.defaulting_id)
        LEFT JOIN graphics     AS gr
               ON (st.id = gr.student_id)
        LEFT JOIN graphic_tradings AS grt
               ON (gr.id = grt.graphic_id)
        where
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) >= '{$this->dtInicial}' AND
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) <= '{$this->dtFinal}'   AND
        if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)) {$pgtEfetuado} {$query}
        order by st.name ASC
        limit 30000"
        );

        $newArr = [];
        
        foreach($expensive as $value):

            # Verifica se a data de pagamento já existe no array
            if(array_key_exists($value->dt_pagamento, $newArr))
            {
                # Verifica se existe o módulo dentro do array
                if(array_key_exists($value->modulo, $newArr[$value->dt_pagamento]))
                {
                    # Checa se não é do tipo contatro/gráfica, pois possuem formas diferentes de caluculo
                    if($value->modulo != 'contrato' && $value->modulo != 'grafica')
                    {
                        $newArr[$value->dt_pagamento][$value->modulo]['valor_pago'] += $value->valor_pago;
                    }else{
                        # Se for contrato
                        if($value->modulo == 'contrato')
                        {
                            # Verifica se a fase existe dentro do array>data>modulo:
                            # Se existir, atualiza o valor
                            # Se não existir, adiciona um valor
                            if(array_key_exists($value->fase, $newArr[$value->dt_pagamento][$value->modulo]))
                            {
                                $newArr[$value->dt_pagamento][$value->modulo][$value->fase]['valor_pago'] += $value->valor_pago;
                            }else{
                                $newArr[$value->dt_pagamento][$value->modulo][$value->fase]['valor_pago'] = $value->valor_pago;
                            }
                        }else{
                            # Então é gráfica
                            # Verifica se a fase existe dentro do array>data>modulo:
                            # Se existir, atualiza o valor
                            # Se não existir, adiciona um valor
                            if(array_key_exists($value->tipo, $newArr[$value->dt_pagamento][$value->modulo]))
                            {
                                $newArr[$value->dt_pagamento][$value->modulo][$value->tipo]['valor_pago'] += $value->valor_pago;
                            }else{
                                $newArr[$value->dt_pagamento][$value->modulo][$value->tipo]['valor_pago'] = $value->valor_pago;
                            }
                        }
                    }
                }else{
                    # Checa se não é do tipo contatro/gráfica, pois possuem formas diferentes de caluculo
                    if($value->modulo != 'contrato' && $value->modulo != 'grafica')
                    {
                        $newArr[$value->dt_pagamento][$value->modulo]['valor_pago'] = $value->valor_pago;
                    }else{
                        # Se for contrato
                        if($value->modulo == 'contrato')
                        {
                            $newArr[$value->dt_pagamento][$value->modulo][$value->fase]['valor_pago'] = $value->valor_pago;
                        }else{
                            # Então é gráfica
                            $newArr[$value->dt_pagamento][$value->modulo][$value->tipo]['valor_pago'] = $value->valor_pago;
                        }
                    }
                }
            }else{
                # Checa se não é do tipo contatro/gráfica, pois possuem formas diferentes de caluculo
                if($value->modulo != 'contrato' && $value->modulo != 'grafica')
                {
                    $newArr[$value->dt_pagamento][$value->modulo]['valor_pago'] = $value->valor_pago;
                }else{
                    # Se for contrato
                    if($value->modulo == 'contrato')
                    {
                        $newArr[$value->dt_pagamento][$value->modulo][$value->fase]['valor_pago'] = $value->valor_pago;
                    }else{
                        # Então é gráfica
                        $newArr[$value->dt_pagamento][$value->modulo][$value->tipo]['valor_pago'] = $value->valor_pago;
                    }
                }
            }
        endforeach;
        
        return view('finances.byDay', [
            'caixa'     => $newArr,
            'title'     => $title,
            'dt_inicio' => $this->dataBr($this->dtInicial),
            'dt_fim'    => $this->dataBr($this->dtFinal),
            'calendario_inicio' => $this->dtInicial,
            'calendario_fim'    => $this->dtFinal,
            'pagamento' => $pagamento,
            'modulo' => $modulo,
        ]);
    }

    public function unidade()
    {
        $title = $this->title. " UNIDADE Dashboard";
        $pagamento = true;

        if(array_key_exists('pagamento', $_GET))
        {
            $this->dtInicial = strlen($_GET['dt_inicio']) > 2 ? $this->dataSql($_GET['dt_inicio']) : $this->dtInicial;
            $this->dtFinal   = strlen($_GET['dt_fim'   ]) > 2 ? $this->dataSql($_GET['dt_fim'   ]) : $this->dtFinal;
        }

        $pgtEfetuado = ($pagamento) ? 'IS NOT NULL'  : 'IS NULL';
        $dataLimit   = ($pagamento) ? 'dt_pagamento' : 'vencimento';

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

                }elseif($_GET['modulo'] == 'grafica'){
                    $modulo = 'grafica';
                    $query = "AND grt.id > 0";
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

        $expensive = DB::select("SELECT gr.tipo, de.fase, st.cod_unidade, st.cod_curso, st.ctr, st.name, st.cpf_cnpj, st.telefone, st.telefone_com, st.celular,
        if(bc.id > 0,bc.id, if(de.id > 0, de.id, gr.id)) AS id,
        if(bc.id > 0,'cheque', if(de.id > 0, 'contrato', 'grafica')) AS modulo,
        if(bc.id > 0, bct.parcela, if(de.id > 0, det.parcela, grt.parcela)) AS parcela,
        if(bc.id > 0, bct.valor, if(de.id > 0, det.valor, grt.valor)) AS valor,
        if(bc.id > 0, bct.vencimento, if(de.id > 0, det.vencimento, grt.vencimento)) AS vencimento,
        #Mysql function
        DATE_FORMAT(if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)), '%d/%m/%Y') AS dt_pagamento,
        if(bc.id > 0, bct.valor_pago, if(de.id > 0, det.valor_pago, grt.valor_pago)) AS valor_pago,
        if(bc.id > 0, bct.pagamento, if(de.id > 0, det.pagamento, grt.pagamento)) AS pagamento
        FROM students AS st
        LEFT JOIN bank_cheques AS bc
               ON (st.id = bc.student_id)
        LEFT JOIN bank_cheque_tradings AS bct
               ON (bc.id = bct.bank_cheque_id)
        LEFT JOIN defaultings  AS de
               ON (st.id = de.student_id)
        LEFT JOIN defaulting_tradings AS det
               ON (de.id = det.defaulting_id)
        LEFT JOIN graphics     AS gr
               ON (st.id = gr.student_id)
        LEFT JOIN graphic_tradings AS grt
               ON (gr.id = grt.graphic_id)
        where
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) >= '{$this->dtInicial}' AND
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) <= '{$this->dtFinal}'   AND
        if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)) {$pgtEfetuado} {$query}
        order by st.cod_unidade ASC
        limit 30000"
        );

        $newArr = [];

        foreach($expensive as $value):
            if(array_key_exists($value->cod_unidade, $newArr))
            {
                if(array_key_exists($value->modulo, $newArr[$value->cod_unidade]))
                {
                    if($value->modulo != 'contrato' && $value->modulo != 'grafica')
                    {
                        $newArr[$value->cod_unidade][$value->modulo]['valor_pago'] += $value->valor_pago;
                    }else{
                         # Se for contrato
                         if($value->modulo == 'contrato')
                         {
                            if(array_key_exists($value->fase, $newArr[$value->cod_unidade][$value->modulo]))
                            {
                                $newArr[$value->cod_unidade][$value->modulo][$value->fase]['valor_pago'] += $value->valor_pago;
                            }else{
                                $newArr[$value->cod_unidade][$value->modulo][$value->fase]['valor_pago'] = $value->valor_pago;
                            }
                         }else{
                            # Então é gráfica
                            if(array_key_exists($value->tipo, $newArr[$value->cod_unidade][$value->modulo]))
                            {
                                $newArr[$value->cod_unidade][$value->modulo][$value->tipo]['valor_pago'] += $value->valor_pago;
                            }else{
                                $newArr[$value->cod_unidade][$value->modulo][$value->tipo]['valor_pago'] = $value->valor_pago;
                            }
                         }
                    }
                }else{
                    if($value->modulo != 'contrato' && $value->modulo != 'grafica')
                    {
                        $newArr[$value->cod_unidade][$value->modulo]['valor_pago'] = $value->valor_pago;
                    }else{
                        # Se for contrato
                        if($value->modulo == 'contrato')
                        {
                            $newArr[$value->cod_unidade][$value->modulo][$value->fase]['valor_pago'] = $value->valor_pago;
                        }else{
                            # Então é gráfica
                            $newArr[$value->cod_unidade][$value->modulo][$value->tipo]['valor_pago'] = $value->valor_pago;
                        }
                    }
                }
            }else{
                if($value->modulo != 'contrato' && $value->modulo != 'grafica')
                {
                    $newArr[$value->cod_unidade][$value->modulo]['valor_pago'] = $value->valor_pago;
                }else{
                    # Se for contrato
                    if($value->modulo == 'contrato')
                    {
                        $newArr[$value->cod_unidade][$value->modulo][$value->fase]['valor_pago'] = $value->valor_pago;
                    }else{
                        # Então é gráfica
                        $newArr[$value->cod_unidade][$value->modulo][$value->tipo]['valor_pago'] = $value->valor_pago;
                    }
                }
            }
        endforeach;
        
        return view('finances.unidade', [
            'caixa'     => $newArr,
            'title'     => $title,
            'dt_inicio' => $this->dataBr($this->dtInicial),
            'dt_fim'    => $this->dataBr($this->dtFinal),
            'calendario_inicio' => $this->dtInicial,
            'calendario_fim'    => $this->dtFinal,
            'pagamento' => $pagamento,
            'modulo' => $modulo,
        ]);
    }

    public function csv()
    {
        $title = $this->title. " Dashboard";
        $pagamento = true;

        if(array_key_exists('pagamento', $_GET))
        {
            $this->dtInicial = strlen($_GET['dt_inicio']) > 2 ? $this->dataSql($_GET['dt_inicio']) : $this->dtInicial;
            $this->dtFinal   = strlen($_GET['dt_fim'   ]) > 2 ? $this->dataSql($_GET['dt_fim'   ]) : $this->dtFinal;

            $pagamento = ($_GET['pagamento'] == 'sim') ? true : false;
        }

        $pgtEfetuado = ($pagamento) ? 'IS NOT NULL'  : 'IS NULL';
        $dataLimit   = ($pagamento) ? 'dt_pagamento' : 'vencimento';

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

        $expensive = DB::select("SELECT gr.tipo, de.fase, st.cod_unidade, st.cod_curso, st.ctr, st.name, st.cpf_cnpj, st.telefone, st.telefone_com, st.celular,
        if(bc.id > 0,bc.id, if(de.id > 0, de.id, gr.id)) AS id,
        if(bc.id > 0,'cheque', if(de.id > 0, 'contrato', 'grafica')) AS modulo,
        if(bc.id > 0, bct.parcela, if(de.id > 0, det.parcela, grt.parcela)) AS parcela,
        if(bc.id > 0, bct.valor, if(de.id > 0, det.valor, grt.valor)) AS valor,
        if(bc.id > 0, bct.vencimento, if(de.id > 0, det.vencimento, grt.vencimento)) AS vencimento,
        if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)) AS dt_pagamento,
        if(bc.id > 0, bct.valor_pago, if(de.id > 0, det.valor_pago, grt.valor_pago)) AS valor_pago,
        if(bc.id > 0, bct.pagamento, if(de.id > 0, det.pagamento, grt.pagamento)) AS pagamento
        FROM students AS st
        LEFT JOIN bank_cheques AS bc
               ON (st.id = bc.student_id)
        LEFT JOIN bank_cheque_tradings AS bct
               ON (bc.id = bct.bank_cheque_id)
        LEFT JOIN defaultings  AS de
               ON (st.id = de.student_id)
        LEFT JOIN defaulting_tradings AS det
               ON (de.id = det.defaulting_id)
        LEFT JOIN graphics     AS gr
               ON (st.id = gr.student_id)
        LEFT JOIN graphic_tradings AS grt
               ON (gr.id = grt.graphic_id)
        where
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) >= '{$this->dtInicial}' AND
        if(bc.id > 0, bct.{$dataLimit}, if(de.id > 0, det.{$dataLimit}, grt.{$dataLimit})) <= '{$this->dtFinal}'   AND
        if(bc.id > 0, bct.dt_pagamento, if(de.id > 0, det.dt_pagamento, grt.dt_pagamento)) {$pgtEfetuado} {$query}
        order by st.name ASC
        limit 30000"
        );

        $fileName = 'caixa_'.time().'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $pagamentoLabel = ($pagamento)  ? 'Pago' : 'Receber';

        $columns = array(
        'Modulo',
        'Unidade',
        'Curso',
        'CTR',
        'Nome',
        'CPF/CNPJ',
        'Contato',
        'Parcela',
        'Dt Pagamento',
        $pagamentoLabel,
        'Pagamento');



        $callback = function() use($expensive, $columns, $pagamento) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($expensive as $value)
            {
                if($value->modulo == 'cheque'){
                   $modulo = 'Cheque';
                }elseif($value->modulo == 'contrato'){
                    $modulo = ucfirst($value->fase);
                }else{
                    #Grafica
                    $tipos  = $this->graphicTipos();
                    $modulo = utf8_decode($tipos[$value->tipo]);
                }

                $dt      = ($pagamento) ? $value->dt_pagamento : $value->vencimento;
                $valor   = ($pagamento) ? $value->valor_pago : $value->valor;
                $contato =  $value->telefone."/".$value->telefone_com."/".$value->celular;

                fputcsv($file, array(
                    $modulo,
                    $value->cod_unidade,
                    $value->cod_curso,
                    $value->ctr,
                    utf8_decode($value->name),
                    $value->cpf_cnpj,
                    $contato,
                    $value->parcela,
                    $dt,
                    $valor,
                    $value->pagamento
                ));
            }

            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function dataSql($value)
    {
        $date = str_replace('/', '-', $value);
        return date("Y-m-d", strtotime($date));
    }

    public function dataBr($value)
    {
        return date("d/m/Y", strtotime($value));
    }

}
