<?php

namespace App\Http\Controllers;

use App\Defaulting;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class DefaultingController extends Controller
{
    private $title  = 'CONTRATO - EVOLUTIME';

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
        $ids = [];
        $unidade = NULL;
        $ctr = NULL;
        $students = NULL;
        $pesuisar = NULL;
        $negociado = '';
        $boleto = '';

        if(array_key_exists('filtro',$_GET))
        {

            if(strlen($_GET['pesquisar']))
            {
                $pesuisar = $_GET['pesquisar'];
                $students = Student::where('name', 'like', '%' . $pesuisar . '%')
                ->where('active', true)
                ->orWhere('cpf_cnpj', 'like', '%' . $pesuisar . '%')
                ->orWhere('telefone', 'like', '%' . $pesuisar . '%')
                ->orWhere('telefone_com', 'like', '%' . $pesuisar . '%')
                ->orWhere('celular', 'like', '%' . $pesuisar . '%')
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }else{
                $students  = Student::where('active', true)->get();
                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }

            if(strlen($_GET['unidade']))
            {
                $unidade = $_GET['unidade'];
                $students = Student::whereIn('id', $ids)
                ->where('cod_unidade', 'like', '%' . $unidade . '%')
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }
            if(strlen($_GET['ctr']))
            {
                $ctr = $_GET['ctr'];
                $students = Student::whereIn('id', $ids)
                ->where('ctr', 'like', '%' . $ctr . '%')
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }

           if(strlen($_GET['negociado']))
            {
                $negociado = $_GET['negociado'] == 'sim' ? true : false;
                $students  = Defaulting::whereIn('student_id', $ids)
                ->where('negociado', $negociado)
                ->where('active', true)
                ->get();


                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $negociado = $_GET['negociado'];
            }



            if(strlen($_GET['boleto']))
            {
                $boleto = $_GET['boleto'] == 'sim' ? true : false;
                $students = Defaulting::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
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

                    }elseif($_GET['modulo'] == 'grafica'){
                        $modulo = 'grafica';
                        $query = "AND grt.id > 0";
                    }else{
                        $modulo = $_GET['modulo'];
                        if($modulo == 'contrato_segunda'){
                            $query = 'segunda';
                        }else{
                            $query = 'terceira';
                        }
                    }
                }

            }

            if(!empty($query))
            {
                $defaultings = Defaulting::whereIn('student_id', $ids)
                ->where('active', true)
                ->where('fase', $query)
                ->orderBy('student_name', 'asc')
                ->paginate(100);
            }else{
                $defaultings = Defaulting::whereIn('student_id', $ids)
                ->where('active', true)
                ->orderBy('student_name', 'asc')
                ->paginate(100);
            }


        }else{
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
                            $query = 'segunda';
                        }else{
                            $query = 'terceira';
                        }
                    }
                }

            }

            if(!empty($query))
            {
                $defaultings = Defaulting::where('active', true)
            ->where('fase', $query)
            ->orderBy('student_name', 'asc')->paginate(100);
            }else{
                $defaultings = Defaulting::where('active', true)
            ->orderBy('student_name', 'asc')->paginate(100);

            }

        }

        $title = $this->title. " listagem";


        return view('defaultings.index', [
            'title' => $title,
            'defaultings' => $defaultings,
            'pesuisar' => $pesuisar,
            'negociado' => $negociado,
            'boleto' => $boleto,
            'unidade' => $unidade,
            'ctr' => $ctr,
            'modulo' => $modulo,
        ]);
    }

    public function pdf()
    {
        $ids = [];
        $unidade = NULL;
        $ctr = NULL;
        $students = NULL;
        $pesuisar = NULL;
        $negociado = '';
        $boleto = '';

        if(array_key_exists('filtro',$_GET))
        {

            if(strlen($_GET['pesquisar']))
            {
                $pesuisar = $_GET['pesquisar'];
                $students = Student::where('name', 'like', '%' . $pesuisar . '%')
                ->where('active', true)
                ->orWhere('cpf_cnpj', 'like', '%' . $pesuisar . '%')
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }else{
                $students  = Student::where('active', true)->get();
                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }

            if(strlen($_GET['unidade']))
            {
                $unidade = $_GET['unidade'];
                $students = Student::whereIn('id', $ids)
                ->where('cod_unidade', 'like', '%' . $unidade . '%')
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }
            if(strlen($_GET['ctr']))
            {
                $ctr = $_GET['ctr'];
                $students = Student::whereIn('id', $ids)
                ->where('ctr', 'like', '%' . $ctr . '%')
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }

           if(strlen($_GET['negociado']))
            {
                $negociado = $_GET['negociado'] == 'sim' ? true : false;
                $students  = Defaulting::whereIn('student_id', $ids)
                ->where('negociado', $negociado)
                ->where('active', true)
                ->get();


                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $negociado = $_GET['negociado'];
            }



            if(strlen($_GET['boleto']))
            {
                $boleto = $_GET['boleto'] == 'sim' ? true : false;
                $students = Defaulting::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }

            $defaultings = Defaulting::whereIn('student_id', $ids)
                                        ->where('active', true)
                                        ->orderBy('student_name', 'asc')
                                        ->paginate(300);

        }else{
            $defaultings = Defaulting::where('active', true)->orderBy('student_name', 'asc')->paginate(300);
        }

        $title = $this->title. " listagem";

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $html = view('defaultings.pdf', ['defaultings' => $defaultings])->render();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4');
        $dompdf->set_paper('letter', 'landscape');
        $dompdf->render();
        $dompdf->stream();

    }

    public function csv()
    {
        $ids = [];
        $unidade = NULL;
        $ctr = NULL;
        $students = NULL;
        $pesuisar = NULL;
        $negociado = '';
        $boleto = '';

        if(array_key_exists('filtro',$_GET))
        {

            if(strlen($_GET['pesquisar']))
            {
                $pesuisar = $_GET['pesquisar'];
                $students = Student::where('name', 'like', '%' . $pesuisar . '%')
                ->where('active', true)
                ->orWhere('cpf_cnpj', 'like', '%' . $pesuisar . '%')
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }else{
                $students  = Student::where('active', true)->get();
                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }

            if(strlen($_GET['unidade']))
            {
                $unidade = $_GET['unidade'];
                $students = Student::whereIn('id', $ids)
                ->where('cod_unidade', 'like', '%' . $unidade . '%')
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }
            if(strlen($_GET['ctr']))
            {
                $ctr = $_GET['ctr'];
                $students = Student::whereIn('id', $ids)
                ->where('ctr', 'like', '%' . $ctr . '%')
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->id);
                endforeach;
            }

           if(strlen($_GET['negociado']))
            {
                $negociado = $_GET['negociado'] == 'sim' ? true : false;
                $students  = Defaulting::whereIn('student_id', $ids)
                ->where('negociado', $negociado)
                ->where('active', true)
                ->get();


                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $negociado = $_GET['negociado'];
            }



            if(strlen($_GET['boleto']))
            {
                $boleto = $_GET['boleto'] == 'sim' ? true : false;
                $students = Defaulting::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
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

                    }elseif($_GET['modulo'] == 'grafica'){
                        $modulo = 'grafica';
                        $query = "AND grt.id > 0";
                    }else{
                        $modulo = $_GET['modulo'];
                        if($modulo == 'contrato_segunda'){
                            $query = 'segunda';
                        }else{
                            $query = 'terceira';
                        }
                    }
                }

            }

            if(!empty($query))
            {
                $defaultings = Defaulting::whereIn('student_id', $ids)
                                        ->where('active', true)
                                        ->where('fase', $query)
                                        ->orderBy('student_name', 'asc')
                                        ->paginate(30000);
            }else{
                $defaultings = Defaulting::whereIn('student_id', $ids)
                                        ->where('active', true)
                                        ->orderBy('student_name', 'asc')
                                        ->paginate(30000);

            }



        }else{
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
                            $query = 'segunda';
                        }else{
                            $query = 'terceira';
                        }
                    }
                }

            }

            if(!empty($query))
            {
                $defaultings = Defaulting::where('active', true)
                ->where('fase', $query)
                ->orderBy('student_name', 'asc')->paginate(30000);
            }else{
                $defaultings = Defaulting::where('active', true)
                ->orderBy('student_name', 'asc')->paginate(30000);
            }

        }

        $fileName = 'contrato_'.time().'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );


        $columns = array('Fase','Uni','Cod', 'Ctr','Cpf','Nome','Telefone','Celular','Comercial','Inadimplencia','Negociado','Boleto');
        $callback = function() use($defaultings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($defaultings as $value)
            {
                $negociado = ($value->negociado) ? 'SIM' : 'NAO';
                $boleto    = ($value->boleto)    ? 'SIM' : 'NAO';
                fputcsv($file, array(
                    $value->fase,
                    $value->student->cod_unidade,
                    $value->student->cod_curso,
                    $value->student->ctr,
                    $value->student->cpf_cnpj,
                    utf8_decode($value->student->name),
                    $value->student->telefone,
                    $value->student->celular,
                    $value->student->telefone_com,
                    $value->dt_inadimplencia,
                    $negociado,
                    $boleto
                ));
            }

            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = $this->title. " cadastrar";

        $results = DB::table('students')
        ->join('defaultings', 'students.id', '=', 'defaultings.student_id')
        ->where('defaultings.active', true)
        ->select('students.id')
        ->get();

        $ids = [];
        foreach($results as $value):
            array_push($ids, $value->id);
        endforeach;

        $students = Student::where('active', true)
                    ->whereNotIn('id', $ids)
                    ->orderBy('name', 'asc')
                    ->paginate(10000);

        return view('defaultings.add', ['students' => $students, 'title' => $title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = Student::where('id', $request->student_id)->get();
        $segundaFase = new Defaulting();
        $segundaFase->user_id          = Auth::id();
        $segundaFase->student_id       = $request->student_id;
        $segundaFase->student_name     = $student[0]->name;
        $segundaFase->dt_inadimplencia = $request->dt_inadimplencia;

        $segundaFase->m_parcelas = $request->m_parcelas;
        $segundaFase->m_parcela_pg = $request->m_parcela_pg;
        $segundaFase->m_parcela_valor = $request->m_parcela_valor;

        $segundaFase->s_parcelas = $request->s_parcelas;
        $segundaFase->s_parcela_pg = $request->s_parcela_pg;
        $segundaFase->s_parcela_valor = $request->s_parcela_valor;

        $segundaFase->multa = $request->multa;

        $segundaFase->save();

        return redirect()->route('defaultings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Defaulting  $defaulting
     * @return \Illuminate\Http\Response
     */
    public function show(Defaulting $defaulting)
    {
        $title = $this->title. " negociar";
        $student = Student::where('id', $defaulting->student_id)->get();

        return view('defaultings.show', [
            'title' => $title,
            'defaulting' => $defaulting,
            'student' => $student,
            'estados' => $this->getEstados()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Defaulting  $defaulting
     * @return \Illuminate\Http\Response
     */
    public function edit(Defaulting $defaulting)
    {
        $title = $this->title. " alterar";
        $students = Student::where('active', true)->orderBy('name', 'asc')->paginate(1000);

        return view('defaultings.edit', ['title' => $title, 'students' => $students, 'defaulting' => $defaulting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Defaulting  $defaulting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Defaulting $defaulting)
    {
        $defaulting->student_id       = $request->student_id;
        $defaulting->dt_inadimplencia = $request->dt_inadimplencia;

        $defaulting->m_parcelas = $request->m_parcelas;
        $defaulting->m_parcela_pg = $request->m_parcela_pg;
        $defaulting->m_parcela_valor = $request->m_parcela_valor;

        $defaulting->s_parcelas = $request->s_parcelas;
        $defaulting->s_parcela_pg = $request->s_parcela_pg;
        $defaulting->s_parcela_valor = $request->s_parcela_valor;

        $defaulting->multa = $request->multa;

        $defaulting->save();

        return redirect()->route('defaultings.show', ['defaulting' => $defaulting->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Defaulting  $defaulting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Defaulting $defaulting)
    {
        $defaulting->active = false;
        $defaulting->save();
        return redirect()->route('defaultings.index');
    }

    public function trash()
    {
        $title = $this->title. " lixeira";
        $defaultings = Defaulting::where('active', false)
                                ->orderBy('student_name', 'asc')
                                ->paginate(100);
        return view('defaultings.trash', [
            'title' => $title,
            'defaultings' => $defaultings,
        ]);
    }

    public function getEstados()
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
    }
}
