<?php

namespace App\Http\Controllers;

use App\Graphic;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class GraphicController extends UtilController
{
    private $title  = 'GRAFICA - ENNT';

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
        $tipo = '';

        if(array_key_exists('filtro',$_GET))
        {

            # Pega todos os id de estudantes onde
            # algum dos campos atenda ao menos
            # uma coluna abaixo.
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
                $students  = Graphic::whereIn('student_id', $ids)
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
                $students = Graphic::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }

            if(strlen($_GET['tipo']))
            {
                $tipo     = $_GET['tipo'] == "grafica_1" ? "grafica_1" : "grafica_2";
                $students = Graphic::whereIn('student_id', $ids)
                ->where('tipo', $tipo)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;
            }

            $graphics = Graphic::whereIn('student_id', $ids)
                                    ->where('active', true)
                                    ->orderBy('student_name', 'asc')
                                    ->paginate(100);

        }else{
            $graphics = Graphic::where('active', true)->orderBy('student_name', 'asc')->paginate(100);
        }

        $title = $this->title. " listagem";

        return view('graphics.index', [
            'title' => $title,
            'graphics' => $graphics,
            'pesuisar' => $pesuisar,
            'negociado' => $negociado,
            'boleto' => $boleto,
            'unidade' => $unidade,
            'ctr' => $ctr,
            'tipos' => $this->graphicTipos(),
            'tipo' => $tipo
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
                $students  = Graphic::whereIn('student_id', $ids)
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
                $students = Graphic::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }


            $graphics = Graphic::whereIn('student_id', $ids)
                                    ->where('active', true)
                                    ->orderBy('student_name', 'asc')
                                    ->paginate(300);

        }else{
            $graphics = Graphic::where('active', true)->orderBy('student_name', 'asc')->paginate(300);
        }

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $html = view('graphics.pdf', ['graphics' => $graphics])->render();

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
        $tipo   = '';

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
                $students  = Graphic::whereIn('student_id', $ids)
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
                $students = Graphic::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }

            if(strlen($_GET['tipo']))
            {
                $tipo     = $_GET['tipo'] == "grafica_1" ? "grafica_1" : "grafica_2";
                $students = Graphic::whereIn('student_id', $ids)
                ->where('tipo', $tipo)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;
            }


            $graphics = Graphic::whereIn('student_id', $ids)
                                    ->where('active', true)
                                    ->orderBy('student_name', 'asc')
                                    ->paginate(30000);

        }else{
            $graphics = Graphic::where('active', true)->orderBy('student_name', 'asc')->paginate(30000);
        }

        $fileName = 'grafica_'.time().'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Uni','Cod','Ctr','Cpf','Nome','Telefone','Celular','Comercial','Vencimento','Negociado','Boleto','Valor');
        $callback = function() use($graphics, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($graphics as $value)
            {
                $negociado = ($value->negociado) ? 'SIM' : 'NAO';
                $boleto    = ($value->boleto)    ? 'SIM' : 'NAO';
                fputcsv($file, array(
                    $value->student->cod_unidade,
                    $value->student->cod_curso,
                    $value->student->ctr,
                    $value->student->cpf_cnpj,
                    utf8_decode($value->student->name),
                    $value->student->telefone,
                    $value->student->celular,
                    $value->student->telefone_com,
                    $value->dt_vencimento,
                    $negociado,
                    $boleto,
                    $value->total
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
        ->join('graphics', 'students.id', '=', 'graphics.student_id')
        ->where('graphics.active', true)
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

        return view('graphics.add', ['students' => $students, 'title' => $title]);
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
        $segundaFase = new Graphic();
        $segundaFase->user_id       = Auth::id();
        $segundaFase->student_id    = $request->student_id;
        $segundaFase->student_name  = $student[0]->name;
        $segundaFase->dt_vencimento = $request->dt_vencimento;

        $segundaFase->valor   = $request->valor;
        $segundaFase->parcela = $request->parcela;
        $segundaFase->total   = $request->total;

        $segundaFase->save();

        return redirect()->route('graphics.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Graphic  $graphic
     * @return \Illuminate\Http\Response
     */
    public function show(Graphic $graphic)
    {
        $title = $this->title. " negociar";
        $student = Student::where('id', $graphic->student_id)->get();

        return view('graphics.show', [
            'title' => $title,
            'graphic' => $graphic,
            'student' => $student,
            'estados' => $this->getEstados(),
            'tipos' => $this->graphicTipos()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Graphic  $graphic
     * @return \Illuminate\Http\Response
     */
    public function edit(Graphic $graphic)
    {
        $title = $this->title. " alterar";
        $students = Student::where('active', true)->orderBy('name', 'asc')->paginate(1000);

        return view('graphics.edit', [
            'title' => $title,
            'students' => $students,
            'graphic' => $graphic,
            'tipos' => $this->graphicTipos()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Graphic  $graphic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Graphic $graphic)
    {
        $graphic->student_id       = $request->student_id;
        $graphic->dt_vencimento = $request->dt_vencimento;

        $graphic->valor = $request->valor;
        $graphic->parcela = $request->parcela;
        $graphic->total = $request->total;
        $graphic->tipo = $request->tipo;

        $graphic->save();

        return redirect()->route('graphics.show', [
            'graphic' => $graphic->id,
            'tipos' => $this->graphicTipos()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Graphic  $graphic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Graphic $graphic)
    {
        $graphic->active = false;
        $graphic->save();
        return redirect()->route('graphics.index');
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
