<?php

namespace App\Http\Controllers;

use App\BankCheque;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class BankChequeController extends Controller
{
    private $title  = 'CHEQUE - EVOLUTIME';

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
                $students  = BankCheque::whereIn('student_id', $ids)
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
                $students = BankCheque::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }

            $bankCheques = BankCheque::whereIn('student_id', $ids)
                                        ->where('active', true)
                                        ->orderBy('student_name', 'asc')
                                        ->paginate(100);

        }else{
            $bankCheques = BankCheque::where('active', true)->orderBy('student_name', 'asc')->paginate(100);
        }

        $title = $this->title. " listagem";

        return view('bankCheques.index', [
            'title' => $title,
            'bankCheques' => $bankCheques,
            'pesuisar' => $pesuisar,
            'negociado' => $negociado,
            'boleto' => $boleto,
            'unidade' => $unidade,
            'ctr' => $ctr,
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
                $students  = BankCheque::whereIn('student_id', $ids)
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
                $students = BankCheque::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }

            $bankCheques = BankCheque::whereIn('student_id', $ids)
                                        ->where('active', true)
                                        ->orderBy('student_name', 'asc')
                                        ->paginate(300);

        }else{
            $bankCheques = BankCheque::where('active', true)->orderBy('student_name', 'asc')->paginate(300);
        }

        $title = $this->title. " listagem";

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $html = view('bankCheques.pdf', ['bankCheques' => $bankCheques])->render();

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
                $students  = BankCheque::whereIn('student_id', $ids)
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
                $students = BankCheque::whereIn('student_id', $ids)
                ->where('boleto', $boleto)
                ->where('active', true)
                ->get();

                $ids = [];
                foreach($students as $value):
                    array_push($ids, $value->student_id);
                endforeach;

                $boleto = $_GET['boleto'];
            }

            $bankCheques = BankCheque::whereIn('student_id', $ids)
                                        ->where('active', true)
                                        ->orderBy('student_name', 'asc')
                                        ->paginate(30000);

        }else{
            $bankCheques = BankCheque::where('active', true)->orderBy('student_name', 'asc')->paginate(30000);
        }

        $fileName = 'cheque_'.time().'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );


        $columns = array('Uni','Cod','Ctr','Cpf','Nome','Telefone','Celular','Comercial','Negociado','Boleto','Valor');
        $callback = function() use($bankCheques, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bankCheques as $value)
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
                    $negociado,
                    $boleto,
                    $value->valor
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
        ->join('bank_cheques', 'students.id', '=', 'bank_cheques.student_id')
        ->where('bank_cheques.active', true)
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

        return view('bankCheques.add', ['students' => $students, 'title' => $title]);
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
        $bankCheque = new BankCheque();
        $bankCheque->user_id       = Auth::id();
        $bankCheque->student_id    = $request->student_id;
        $bankCheque->student_name  = $student[0]->name;
        $bankCheque->dt_vencimento = $request->dt_vencimento;

        $bankCheque->valor = $request->valor;
        $bankCheque->cheque = $request->cheque;
        $bankCheque->agencia = $request->agencia;
        $bankCheque->banco = $request->banco;

        $bankCheque->save();

        return redirect()->route('bankCheques.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankCheque  $bankCheque
     * @return \Illuminate\Http\Response
     */
    public function show(BankCheque $bankCheque)
    {
        $title = $this->title. " negociar";
        $student = Student::where('id', $bankCheque->student_id)->get();

        return view('bankCheques.show', [
            'title' => $title,
            'bankCheque' => $bankCheque,
            'student' => $student,
            'estados' => $this->getEstados()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankCheque  $bankCheque
     * @return \Illuminate\Http\Response
     */
    public function edit(BankCheque $bankCheque)
    {
        $title = $this->title. " alterar";
        $students = Student::where('active', true)->orderBy('name', 'asc')->paginate(1000);

        return view('bankCheques.edit', [
            'title' => $title,
            'students' => $students,
            'bankCheque' => $bankCheque
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankCheque  $bankCheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankCheque $bankCheque)
    {
        $bankCheque->student_id    = $request->student_id;
        $bankCheque->dt_vencimento = $request->dt_vencimento;

        $bankCheque->valor   = $request->valor;
        $bankCheque->cheque  = $request->cheque;
        $bankCheque->agencia = $request->agencia;
        $bankCheque->banco   = $request->banco;

        $bankCheque->save();

        return redirect()->route('bankCheques.show', ['bankCheque' => $bankCheque->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankCheque  $bankCheque
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankCheque $bankCheque)
    {
        $bankCheque->active = false;
        $bankCheque->save();
        return redirect()->route('bankCheques.index');
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
