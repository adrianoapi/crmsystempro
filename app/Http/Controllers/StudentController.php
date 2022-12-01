<?php

namespace App\Http\Controllers;

use App\Student;
use App\Defaulting;
use App\Graphic;
use App\BankCheque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    private $title  = 'Alunos';

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
        $pesuisar = NULL;
        if(array_key_exists('filtro',$_GET))
        {
            if(strlen($_GET['pesquisar']))
            {
                $pesuisar = $_GET['pesquisar'];
                $students = Student::where('name', 'like', '%' . $pesuisar . '%')
                ->orderBy('name', 'asc')
                ->paginate(100);
            }else{
                $students = Student::where('active', true)->orderBy('name', 'asc')->paginate(100);
            }
        }else{
            $students = Student::where('active', true)->orderBy('name', 'asc')->paginate(100);
        }
        $title = $this->title. " listagem";


        return view('students.index', ['title' => $title, 'students' => $students, 'pesuisar' => $pesuisar]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($value)
    {
        $title = $this->title. " cadastrar";

        return view('students.add', [
            'title' => $title,
            'modulo' => $value,
            'estados' => $this->getEstados()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Student();
        $model->user_id     = Auth::id();
        $model->name        = $request->name;
        $model->cod_unidade = $request->cod_unidade;
        $model->cod_curso   = $request->cod_curso;
        $model->responsavel = $request->responsavel;
        $model->cpf_cnpj    = $request->cpf_cnpj;
        $model->nascimento    = $request->nascimento;
        $model->ctr = $request->ctr;
        $model->telefone = $request->telefone;
        $model->telefone_com = $request->telefone_com;
        $model->celular = $request->celular;
        $model->email = $request->email;
        $model->cep = $request->cep;
        $model->endereco = $request->endereco;
        $model->numero = $request->numero;
        $model->complemento = $request->complemento;
        $model->bairro = $request->bairro;
        $model->cidade = $request->cidade;
        $model->estado = $request->estado;
        #$model->fase = $request->fase;
        #$model->negociado = $request->negociado == 'true' ? true : false;
        #$model->boleto = $request->boleto == 'true' ? true : false;
        $model->save();

        if($request->modulo == 'cheque'){
            $bankCheque = new BankCheque();
            $bankCheque->user_id       = Auth::id();
            $bankCheque->student_id    = $model->id;
            $bankCheque->student_name  = $request->name;
            $bankCheque->dt_vencimento = date('Y-m-d');

            $bankCheque->valor   = '0.00';
            $bankCheque->cheque  = '0';
            $bankCheque->agencia = '0';
            $bankCheque->banco   = '0';

            if($bankCheque->save())
            {
                return redirect()->route('bankCheques.show', ['bankCheque' => $bankCheque->id]);
            }else{
                die('erro ao cadastrar "cheque"');
            }

        }elseif($request->modulo  == 'contrato'){
            $segundaFase = new Defaulting();
            $segundaFase->user_id          = Auth::id();
            $segundaFase->student_id       = $model->id;
            $segundaFase->student_name     = $request->name;
            $segundaFase->dt_inadimplencia = date('Y-m-d');

            $segundaFase->m_parcelas = 0;
            $segundaFase->m_parcela_pg = 0;
            $segundaFase->m_parcela_valor = '0.00';

            $segundaFase->s_parcelas = 0;
            $segundaFase->s_parcela_pg = 0;
            $segundaFase->s_parcela_valor = '0.00';

            $segundaFase->multa = '10.00';

            if($segundaFase->save())
            {
                return redirect()->route('defaultings.show', ['defaulting' => $segundaFase->id]);
            }else{
                die('erro ao cadastrar "cadastro fase"');
            }

        }elseif($request->modulo  == 'grafica'){
            $grafica = new Graphic();
            $grafica->user_id       = Auth::id();
            $grafica->student_id    = $model->id;
            $grafica->student_name  = $request->name;
            $grafica->dt_vencimento = date('Y-m-d');

            $grafica->valor   = '0.00';
            $grafica->parcela = 0;
            $grafica->total   = '0.00';

            if($grafica->save())
            {
                return redirect()->route('graphics.show', ['graphic' => $grafica->id]);
            }else{
                die('erro ao cadastrar "grafica"');
            }

        }

        return redirect()->route('alunos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $title = $this->title. " alterar";
        $defaulting = Defaulting::where('student_id', $student->id)->limit(1)->get();

        return view('students.edit', [
            'title' => $title,
            'student' => $student,
              'estados' => $this->getEstados(),
               'defaulting' => $defaulting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $student->user_id     = Auth::id();
        $student->name        = $request->name;
        $student->cod_unidade = $request->cod_unidade;
        $student->cod_curso   = $request->cod_curso;
        $student->responsavel = $request->responsavel;
        $student->cpf_cnpj    = $request->cpf_cnpj;
        $student->nascimento    = $request->nascimento;
        $student->ctr = $request->ctr;
        $student->telefone = $request->telefone;
        $student->telefone_com = $request->telefone_com;
        $student->celular = $request->celular;
        $student->email = $request->email;
        $student->cep = $request->cep;
        $student->endereco = $request->endereco;
        $student->numero = $request->numero;
        $student->complemento = $request->complemento;
        $student->bairro = $request->bairro;
        $student->cidade = $request->cidade;
        $student->estado = $request->estado;

        $student->save();

        if($request->defaulting_id)
        {
            $dataulting = Defaulting::find($request->defaulting_id);
            $dataulting->student_name = $request->name;
            if(Auth::user()->level > 1){
                $dataulting->fase = $request->fase;
            }
            $dataulting->negociado = $request->negociado == 'true' ? true : false;
            $dataulting->boleto = $request->boleto == 'true' ? true : false;
            $dataulting->save();

            $graphic = Graphic::where('student_id', $student->student_id)->first();
            if(!empty($graphic)){
                $graphic->student_name = $request->name;
                $graphic->save();
            }

            $bankCheque = BankCheque::where('student_id', $student->student_id)->first();
            if(!empty($bankCheque)){
                $bankCheque->student_name = $request->name;
                $bankCheque->save();
            }

            return redirect()->route('defaultings.show', ['defaulting' => $request->defaulting_id]);

        }elseif($request->graphic_id){
            $graphic = Graphic::find($request->graphic_id);
            $graphic->student_name = $request->name;
            $graphic->negociado = $request->negociado == 'true' ? true : false;
            $graphic->boleto = $request->boleto == 'true' ? true : false;
            if(Auth::user()->level > 1){
                $graphic->tipo = $request->tipo;
            }
            $graphic->save();

            $dataulting = Defaulting::where('student_id', $student->student_id)->first();
            if(!empty($dataulting)){
                $dataulting->student_name = $request->name;
                $dataulting->save();
            }

            $bankCheque = BankCheque::where('student_id', $student->student_id)->first();
            if(!empty($bankCheque)){
                $bankCheque->student_name = $request->name;
                $bankCheque->save();
            }

            return redirect()->route('graphics.show', ['graphic' => $request->graphic_id]);

        }elseif($request->bank_cheque_id){
            $graphic = BankCheque::find($request->bank_cheque_id);
            $graphic->student_name = $request->name;
            $graphic->negociado = $request->negociado == 'true' ? true : false;
            $graphic->boleto = $request->boleto == 'true' ? true : false;
            $graphic->save();

            $graphic = Graphic::where('student_id', $student->student_id)->first();
            if(!empty($graphic)){
                $graphic->student_name = $request->name;
                $graphic->save();
            }

            $dataulting = Defaulting::where('student_id', $student->student_id)->first();
            if(!empty($dataulting)){
                $dataulting->student_name = $request->name;
                $dataulting->save();
            }

            return redirect()->route('bankCheques.show', ['bankCheque' => $request->bank_cheque_id]);

        }else{
            return redirect()->route('alunos.index');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->active = false;
        $student->save();

        return redirect()->route('alunos.index');
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
