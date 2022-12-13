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

class QueuedController extends Controller
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
    public function index()
    {
        $tile = "Importação";

        if(array_key_exists('modulo',$_GET))
        {
            $modulo = $_GET['modulo'];

        }else{
            die('Móudlo não encontrado!!!');
        }

        $queued = Queued::where('module', $modulo)->where('process', false)->get();


        return view('queueds.index', [
            'title' => $tile,
            'queueds' => $queued,
            'modulo' => $modulo,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Queued  $queued
     * @return \Illuminate\Http\Response
     */
    public function processar(Queued $queued)
    {

        if($queued->id && !$queued->proccess)
        {
            $status = false;
            $body   = json_decode($queued->body);

            if($queued->module == 'contrato')
            {
                foreach($body as $value):

                    # Save students
                    $student = new Student();
                    $student->user_id      = Auth::id();
                    $student->cod_unidade  = $value->students->cod_unidade;
                    $student->cod_curso    = $value->students->cod_curso;
                    $student->ctr          = $value->students->ctr;
                    $student->cpf_cnpj     = $value->students->cpf_cnpj;
                    $student->ctr          = $value->students->ctr;
                    $student->telefone     = $value->students->telefone;
                    $student->telefone_com = $value->students->telefone_com;
                    $student->celular      = $value->students->celular;
                    $student->name         = $value->students->name;
                    $student->email        = $value->students->email;
                    $student->cep          = $value->students->cep;
                    $student->endereco     = $value->students->endereco;
                    $student->bairro       = $value->students->bairro;
                    $student->cidade       = $value->students->cidade;
                    $student->estado       = $value->students->estado;

                    if($student->save())
                    {
                        $status = true;

                        $defaulting = new Defaulting();
                        $defaulting->user_id          = Auth::id();
                        $defaulting->student_id       = $student->id;
                        $defaulting->dt_inadimplencia = $value->defaultings->dt_inadimplencia;

                        $defaulting->m_parcelas      = $value->defaultings->m_parcelas;
                        $defaulting->m_parcela_pg    = $value->defaultings->m_parcela_pg;
                        $defaulting->m_parcela_valor = str_replace('.', ',',$value->defaultings->m_parcela_valor);

                        $defaulting->s_parcelas      = $value->defaultings->s_parcelas;
                        $defaulting->s_parcela_pg    = $value->defaultings->s_parcela_pg;
                        $defaulting->s_parcela_valor = str_replace('.', ',',$value->defaultings->s_parcela_valor);

                        $defaulting->multa = $value->defaultings->multa;
                        $defaulting->save();
                    }

                endforeach;
            }

            if($queued->module == 'grafica')
            {
                foreach($body as $value):

                    # Save students
                    $student = new Student();
                    $student->user_id      = Auth::id();
                    $student->cod_unidade  = $value->students->cod_unidade;
                    $student->cod_curso    = $value->students->cod_curso;
                    $student->ctr          = $value->students->ctr;
                    $student->cpf_cnpj     = $value->students->cpf_cnpj;
                    $student->ctr          = $value->students->ctr;
                    $student->telefone     = $value->students->telefone;
                    $student->telefone_com = $value->students->telefone_com;
                    $student->celular      = $value->students->celular;
                    $student->name         = $value->students->name;

                    if($student->save())
                    {
                        $status = true;

                        $graphic = new Graphic();
                        $graphic->user_id       = Auth::id();
                        $graphic->student_id    = $student->id;
                        $graphic->tipo          = $value->graphics->tipo;
                        $graphic->student_name  = $student->name;
                        $graphic->dt_vencimento = $value->graphics->dt_vencimento;
                        $graphic->valor         = str_replace('.', ',',$value->graphics->valor);
                        $graphic->parcela       = $value->graphics->parcela;
                        $graphic->total         = str_replace('.', ',',$value->graphics->total);
                        $graphic->save();
                    }

                endforeach;
            }

            if($queued->module == 'cheque')
            {
                foreach($body as $value):

                    # Save students
                    $student = new Student();
                    $student->user_id      = Auth::id();
                    $student->cod_unidade  = $value->students->cod_unidade;
                    $student->cod_curso    = $value->students->cod_curso;
                    $student->ctr          = $value->students->ctr;
                    $student->cpf_cnpj     = $value->students->cpf_cnpj;
                    $student->ctr          = $value->students->ctr;
                    $student->telefone     = $value->students->telefone;
                    $student->telefone_com = $value->students->telefone_com;
                    $student->celular      = $value->students->celular;
                    $student->name         = $value->students->name;

                    if($student->save())
                    {
                        $status = true;

                        $bankCheque = new BankCheque();
                        $bankCheque->user_id       = Auth::id();
                        $bankCheque->student_id    = $student->id;
                        $bankCheque->student_name  = $student->name;
                        $bankCheque->valor         = str_replace('.', ',',$value->bank_cheques->valor);

                        if($bankCheque->save())
                        {
                            $i = 0;
                            foreach($value->bank_cheque_plots as $plot):

                                $model = new BankChequePlot();
                                $model->user_id     = Auth::id();
                                $model->bank_cheque_id = $bankCheque->id;
                                $model->banco      = $plot->banco;
                                $model->agencia    = $plot->agencia;
                                $model->conta      = $plot->conta;
                                $model->cheque     = $plot->cheque;
                                $model->vencimento = $plot->vencimento;
                                $model->valor      = str_replace('.', ',',$plot->valor);
                                $model->save();
                                $i++;

                            endforeach;
                        }
                    }
                endforeach;
            }

        }

        if($status){
            $queued->process = true;
            $queued->save();
            return redirect()->route('importacao.index', ['modulo' => $queued->module]);
        }else{
            die('Um erro aconteceu!');
        }

    }

    public function autoComplete($string, int $number = 5)
    {
        $newString = $string;
        for($i = strlen($string); $i < $number; $i++)
        {
            $newString = '0'.$newString;
        }
        return $newString;
    }

    /**
     * Deve retornar tipo 9.999,99
     */
    public function tratarValorMoeda($value)
    {
        if(strpos($value, ",") !== false){
            $arrMed  = explode(",", $value);
            $number = preg_replace('/[^0-9]/', '', $arrMed[0]).".".$arrMed[1];
        } elseif(strpos($value, ".") !== false){
            // Quando o csv já vem formatado em decilam x.xx
            $number = $value;
        }else{
            $number = preg_replace('/[^0-9]/', '', $value).".00";
        }
        return $number;
    }

    public function upload(Request $request)
    {
        if(array_key_exists('modulo',$_POST))
        {
            $modulo = $_POST['modulo'];

        }else{
            die('Móudulo não encontrado!!!');
        }

        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($_FILES['filename']['type'],$mimes)){
            $arrayBody = [];
        } else {
         die("Erro: Arquivo inválido!");
        }

        $handle  = fopen($_FILES['filename']['tmp_name'], "r");
        $linhas  = 0;

        if($modulo == 'contrato')
        {
            while (($data = fgetcsv($handle, 20000, ",")) !== FALSE)
            { 
                if($linhas)
                {
                    $row = explode(';', $data[0]);
                   

                    if(!empty($row[13]))
                    {
                        if(!array_key_exists(22, $row)){
                            echo "Algum campo está com valor incorreto, o que acarretou na quebra da importação:".
                            "<ul>".
                            "<li>Campo moeda deve ser valor decimal sem vírgula, exp: 000.00</li>".
                            "<li>Não pode ter vírgula no nome, endereço ou qualquer outro campo</li>".
                            "</ul>";
                            
                            dd($row[13]);
                        }
                        $arrayBody[] = [
                            'students' => [
                                'cod_unidade' => $this->autoComplete($row[0],3),
                                'cod_curso' => $this->autoComplete($row[1], 3),
                                'ctr' => $this->autoComplete($row[2], 5),
                                'cpf_cnpj' => preg_replace("/[^0-9]/", "",$row[3]),
                                'telefone' => $row[4],
                                'telefone_com' => $row[5],
                                'celular' => $row[6],
                                'email' => $row[7],
                                'cep' => $row[8],
                                'endereco' => $row[9],
                                'bairro' => $row[10],
                                'cidade' => $row[11],
                                'estado' => $row[12],
                                'name' => $row[13],
                            ],
                            'defaultings' => [
                                'fase' => $row[14],
                                'dt_inadimplencia' => $this->setDate($row[15]),
                                'm_parcela_pg' => !empty($row[16]) ? $row[16] : "0",
                                'm_parcelas' => !empty($row[17]) ? $row[17] : "0",
                                'm_parcela_valor' => !empty($row[18]) ? $this->tratarValorMoeda($row[18]) : "0",
                                's_parcela_pg' => !empty($row[19]) ? $row[19] : "0",
                                's_parcelas' => !empty($row[20]) ? $row[20] : "0",
                                's_parcela_valor' => !empty($row[21]) ? $this->tratarValorMoeda($row[21]) : "0",
                                'multa' => $row[22],
                            ]

                        ];
                    }
                }
                $linhas++;
                
            }
        }

        if($modulo == 'grafica')
        {

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                $row = explode(';', $data[0]);

                if(!empty($row[7]))
                {
                    $arrayBody[] = [
                        'students' => [
                            'cod_unidade' => $this->autoComplete($row[0],3),
                            'cod_curso' => $this->autoComplete($row[1], 3),
                            'ctr' => $this->autoComplete($row[2], 5),
                            'cpf_cnpj' => preg_replace("/[^0-9]/", "",$row[3]),
                            'telefone' => $row[4],
                            'telefone_com' => $row[5],
                            'celular' => $row[6],
                            'name' => utf8_encode($row[7]),
                        ],
                        'graphics' => [
                            'tipo' => $row[12],
                            'dt_vencimento' => $this->setDate($row[8]),
                            'valor' => $this->tratarValorMoeda($row[9]),
                            'parcela' => $row[10],
                            'total' => $row[11],
                        ],

                    ];
                 
                }

            }
        }

        
        if($modulo == 'cheque')
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                $row = explode(';', $data[0]);

                if(!empty($row[7]))
                {
                    # Vai construrir as parcelas
                    $plots = [];
                    $i = 0; # 0 => 8 // separa as parcelas do cheque
                    $j = 0; # 0 => 5 // faz a contagem dentro das parcelas
                    $k = 0; # indice que nao zera nunca dentro do while
                    foreach($row as $value):

                        if( $i > 8)
                        {
                            if(!empty($row[$i])){
                                $plots[$k][$this->setLabel($j)] = ($j == 4) ? $this->setDate($row[$i]) : $row[$i];
                                $j++;
                                if($j > 5){
                                    $k++;
                                    $j=0;
                                }
                            }
                        }
                        $i++;

                    endforeach;

                    $arrayBody[] = [
                        'students' => [
                                        'cod_unidade' => $this->autoComplete($row[0], 3),
                                        'cod_curso' => $this->autoComplete($row[1], 3),
                                        'ctr' => $this->autoComplete($row[2], 5),
                                        'cpf_cnpj' => preg_replace("/[^0-9]/", "",$row[3]),
                                        'telefone' => $row[4],
                                        'telefone_com' => $row[5],
                                        'celular' => $row[6],
                                        'name' => utf8_encode($row[7]),
                        ],
                        'bank_cheques' => [
                                        'user_id' => 1,
                                        'student_id' => NULL,
                                        'valor' => $this->tratarValorMoeda($row[8]),
                        ],
                        'bank_cheque_plots' => $plots,
                    ];
                }
            }
        }

        fclose($handle);
        

        $model = new Queued();
        $model->user_id = Auth::id();
        $model->module  = $modulo;
        if($modulo == 'contrato'){
            $model->body    = $this->alternativeArray2Json($arrayBody);
        }else{
            $model->body    = json_decode($arrayBody);
        }

        if(empty($model->body))
        {
            die('Erro: Não foi possível construir o corpo do arquivo ou está vazio!');
        }

        if($model->save())
        {

            return redirect()->route('importacao.index', ['modulo' => $model->module]);

        }else{
            die('Ocorreu um erro em seu arquivo: <ul>
                <li>Ou grande de mais</li>
                <li>Ou formatado errado</li>
                <li>Ou caracteres especianis que não alphanumericos</li>
            </ul>');
        }

    }

    private function setDate($date)
    {
        $newDate = $date;
        if(strpos($date,"/"))
        {
            $date    = explode('/', $date);
            $newDate = $date[2].'-'.$date[1].'-'.$date[0];
        }
        return $newDate;
    }

    private function setLabel($string)
    {
        switch ($string) {
            case 0:
                $label = 'banco';
                break;
            case 1:
                $label = 'agencia';
                break;
            case 2:
                $label = 'conta';
                break;
            case 3:
                $label = 'cheque';
                break;
            case 4:
                $label = 'vencimento';
                break;
            default:
                $label = 'valor';
                break;
        }

        return $label;
    }

    public function alternativeArray2Json(array $data)
    {
        $json = "[";

        $i = 0;
        foreach($data as $key => $value):

            $virgulaUm = $i > 0 ? "," : "";
            $json .= $virgulaUm."{";

            #students
            $iS=0;
            $json .= "\"students\":{";
            foreach($value['students'] as $keyS => $student):

                $virgulaDois = $iS > 0 ? "," : "";
                $json .= "{$virgulaDois}";
                $json .= "\"{$keyS}\":\"".$student."\"";
                $iS++;

                
            endforeach;
            $json .= "},"; # fecha students

            #defaultings
            $iD=0;
            $json .= "\"defaultings\":{";
            foreach($value['defaultings'] as $keyD => $default):

                $virgulaTres = $iD > 0 ? "," : "";
                $json .= "{$virgulaTres}";
                $json .= "\"{$keyD}\":\"{$default}\"";
                $iD++;

                
            endforeach;
            $json .= "}"; # fecha defaultings
          
            $i++;
            /*if($i > 500)
            {
                break;
            }*/
        
            $json .= "}"; # fecha o objeto

        endforeach; // primeiro foreach


        $json .= "]";

        return $json;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Queued  $queued
     * @return \Illuminate\Http\Response
     */
    public function show(Queued $queued)
    {
        return view('queueds.show', [
            'title' => $this->title,
            'queued' => $queued,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Queued  $queued
     * @return \Illuminate\Http\Response
     */
    public function edit(Queued $queued)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Queued  $queued
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Queued $queued)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Queued  $queued
     * @return \Illuminate\Http\Response
     */
    public function destroy(Queued $queued)
    {
        $queued->delete();
        return redirect()->route('importacao.index', ['modulo' => $queued->module]);
    }

    public function history()
    {
        $tile = "Histórico";

        if(array_key_exists('modulo',$_GET))
        {
            $modulo = $_GET['modulo'];

        }else{
            die('Móudlo não encontrado!!!');
        }

        $queued = Queued::where('module', $modulo)->where('process', true)->orderBy('id', 'desc')->get();

        return view('queueds.history', [
            'title' => $tile,
            'queueds' => $queued,
            'modulo' => $modulo,
        ]);


    }

}
