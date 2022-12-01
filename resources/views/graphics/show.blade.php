@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" id="content">

    @include('layouts.navigation')

    <div id="main">
        <div class="container-fluid">

            <div class="row-fluid">

                <div class="span12">
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3><i class="icon-th-list"></i> {{$title}}: {{$graphic->student->name}}</h3>
                        </div>
                        <div class="box-content nopadding">
                            <div class="tabs-container">
                                <ul class="tabs tabs-inline tabs-top">
                                    <li class='active'>
                                        <a href="#aluno" data-toggle='tab'><i class="icon-user"></i> Aluno</a>
                                    </li>
                                    <li>
                                        <a href="#negociacao" data-toggle='tab'><i class="icon-lock"></i> Negociação</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content padding tab-content-inline tab-content-bottom">

                                <div class="tab-pane" id="negociacao">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Negociado</th>
                                                    <th>Tipo</th>
                                                    <th>Parcela</th>
                                                    <th>Valor</th>
                                                    <th>Total</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php
                                                        if($graphic->negociado){
                                                            echo '<button class="btn btn-small  btn-success">SIM</button>';
                                                        }else{
                                                            echo '<button class="btn btn-small  btn-danger">NÃO</button>';
                                                        }
                                                    ?></td>
                                                    <td>{{$tipos[$graphic->tipo]}}</td>
                                                    <td>{{$graphic->parcela}}</td>
                                                    <td>{{$graphic->valor}}</td>
                                                    <td>{{$graphic->total}}</td>
                                                    <td>
                                                        <form action="{{route('graphics.destroy', ['graphic' => $graphic->id])}}" method="POST" onSubmit="return confirm('Deseja excluir?');" style="padding: 0px;margin:0px;">
                                                            @csrf
                                                            @method('delete')

                                                            <a href="{{route('graphics.edit', ['graphic' => $graphic->id])}}" class="btn" rel="tooltip" title="" data-original-title="Editar">
                                                                <i class="icon-edit"></i>
                                                            </a>

                                                            <button type="submit" class="btn btn-red" rel="tooltip" title="" data-original-title="Excluir">
                                                                <i class="icon-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <span class="add_form_field btn btn-teal">Adicionar Parcela &nbsp;
                                            <span  span style="font-size:16px; font-weight:bold;">+ </span>
                                        </span>
                                        <form action="{{route('graphicTradings.store')}}" method="POST" class='form-vertical'>
                                            @csrf
                                            <input type="hidden" name="graphic_id" value="{{$graphic->id}}">
                                            <div class="row-fluid container1">

                                            <div class="row-fluid">
                                                <div class="span1">
                                                    <div class="control-group">
                                                        <label for="parcela" class="control-label">Parcela</label>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <label for="data" class="control-label">Data</label>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <label for="valor" class="control-label">Valor</label>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <label for="data" class="control-label">Data Pagamento</label>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <label for="pagamento" class="control-label">Pagamento</label>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <label for="valor" class="control-label">Valor Pagamento</label>
                                                    </div>
                                                </div>
                                            </div>

                                            @foreach ($graphic->graphicTradings as $value)
                                            <div class="row-fluid">
                                                <div class="span1">
                                                    <div class="control-group">
                                                        <!--<label for="parcela" class="control-label">Parcela</label>-->
                                                        <div class="controls controls-row">
                                                            <input type="number" name="parcela[]" value="{{$value->parcela}}" id="parcela" placeholder="00" class="input-block-level" required=""></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <!--<label for="data" class="control-label">Data</label>-->
                                                        <div class="controls controls-row">
                                                            <input type="text" name="vencimento[]" value="{{$value->vencimento}}" id="vencimento" placeholder="00/00/0000" class="date input-block-level datepick" required="" maxlength="10"></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <!--<label for="valor" class="control-label">Valor</label>-->
                                                        <div class="controls controls-row">
                                                            <input type="text" name="valor[]" value="{{$value->valor}}" id="valor" placeholder="100,00" class="money input-block-level" required=""></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <!--<label for="data" class="control-label">Data Pg</label>-->
                                                        <div class="controls controls-row">
                                                            <input type="text" name="dt_pagamento[]" value="{{$value->dt_pagamento}}" id="dt_pagamento" placeholder="00/00/0000" class="date input-block-level datepick" maxlength="10"></div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <!--<label for="data" class="control-label">Data Pg</label>-->
                                                        <div class="controls controls-row">
                                                            <select name="pagamento[]" id="pagamento" class='select2-me input-block-level' required>
                                                                <option value="..." {{$value->pagamento == '...' ? 'selected':''}}>...</option>
                                                                <option value="dinheiro" {{$value->pagamento == 'dinheiro' ? 'selected':''}}>Dinheiro</option>
                                                                <option value="cartao"   {{$value->pagamento == 'cartao'   ? 'selected':''}}>Cartão</option>
                                                                <option value="cheque"   {{$value->pagamento == 'cheque'   ? 'selected':''}}>Cheque</option>
                                                                <option value="boleto"   {{$value->pagamento == 'boleto'   ? 'selected':''}}>Boleto</option>
                                                                <option value="deposito" {{$value->pagamento == 'deposito' ? 'selected':''}}>Deposito</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <div class="control-group">
                                                        <!--<label for="valor" class="control-label">Valor Pg</label>-->
                                                        <div class="controls controls-row">
                                                            <input type="text" name="valor_pago[]" value="{{$value->valor_pago}}" id="valor_pago" placeholder="100,00" class="money input-block-level"></div>
                                                    </div>
                                                </div>
                                                <a href="#" class="delete">Delete</a>
                                            </div>
                                            @endforeach

                                            </div>

                                            <div class="row-fluid">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <a href="{{route('graphics.index')}}" class="btn">Cancelar</a>
                                                </div>
                                            </div>
                                        </form>
                                </div>

                                <div class="tab-pane active" id="aluno">
                                    <form action="{{route('alunos.update', ['student' => $student[0]->id])}}" method="POST" class='form-vertical'>
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="graphic_id" value="{{$graphic->id}}">
                                    <div class="row-fluid">
                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="cod_unidade" class="control-label">Unidade*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="cod_unidade" id="cod_unidade" placeholder="00000"  value="{{$student[0]->cod_unidade}}" max="100" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="cod_curso" class="control-label">Curso*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="cod_curso" id="cod_curso" placeholder="00000"  value="{{$student[0]->cod_curso}}" max="100" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="ctr" class="control-label">CTR</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="ctr" id="ctr"  value="{{$student[0]->ctr}}" placeholder="000000" max="20" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span5">
                                            <div class="control-group">
                                                <label for="name" class="control-label">Nome completo*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="name" id="name"  value="{{$student[0]->name}}" placeholder="Insira o nome" max="100" class="input-block-level" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="nascimento" class="control-label">Nascimento</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="nascimento" id="nascimento"  value="{{$student[0]->nascimento}}" placeholder="(00) 0000-0000" max="20" class="date input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="cpf_cnpj" class="control-label">CPF/CNPJ*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="cpf_cnpj" id="cpf_cnpj"  value="{{$student[0]->cpf_cnpj}}" placeholder="000.000.000-00/0000" max="30" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span3">
                                            <div class="control-group">
                                                <label for="responsavel" class="control-label">Responsável</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="responsavel" id="responsavel"  value="{{$student[0]->responsavel}}" placeholder="Insira o nome do responsável" max="100" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="control-group">
                                                <label for="email" class="control-label">E-mail</label>
                                                <div class="controls controls-row">
                                                    <input type="email" name="email" id="email"  value="{{$student[0]->email}}" placeholder="Insira o e-mail" max="255" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="telefone" class="control-label">Telefone Fixo</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="telefone" id="telefone"  value="{{$student[0]->telefone}}" placeholder="(00) 0000-0000" max="20" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="telefone_com" class="control-label">Telefone Comercial</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="telefone_com" id="telefone_com"  value="{{$student[0]->telefone_com}}" placeholder="(00) 0000-0000" max="20" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="celular" class="control-label">Telefone celular</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="celular" id="celular"  value="{{$student[0]->celular}}" placeholder="(00) 00000-0000" max="20" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row-fluid">
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="cep" class="control-label">CEP*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="cep" id="cep"  value="{{$student[0]->cep}}" placeholder="00000000" max="9" class="input-block-level" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span8">
                                            <div class="control-group">
                                            <label for="endereco" class="control-label">Endereço*<samll><b><a href="javascript:void(0)" onClick="consultaCep()" id="a_cep">Auto completar</a></b></small></label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="endereco" id="endereco"  value="{{$student[0]->endereco}}" placeholder="Endereço" max="255" class="input-block-level" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="numero" class="control-label">Número*</label>
                                                <div class="controls controls-row">
                                                    <input type="number" name="numero" id="numero"  value="{{$student[0]->numero}}" placeholder="Número" max="99999" class="input-block-level" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="complemento" class="control-label">Complemento*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="complemento" id="textfield"  value="{{$student[0]->complemento}}" placeholder="complemento" max="20" class="input-block-level">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="bairro" class="control-label">Bairro*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="bairro" id="bairro"  value="{{$student[0]->bairro}}" placeholder="bairro" max="255" class="input-block-level" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="cidade" class="control-label">Cidade*</label>
                                                <div class="controls controls-row">
                                                    <input type="text" name="cidade" id="cidade"  value="{{$student[0]->cidade}}" placeholder="cidade" max="255" class="input-block-level" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="estado" class="control-label">Estado*</label>
                                                <div class="controls controls-row">
                                                    <select name="estado" id="estado" class='select2-me input-block-level' required>
                                                        @foreach($estados as $key => $value)
                                                        <option value="{{$key}}"  {{$key == $student[0]->estado ? 'selected':''}}>{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                    <div class="span2">
                                            <div class="control-group">
                                                <label for="negociado" class="control-label">Negociado*</label>
                                                <div class="controls controls-row">
                                                    <select name="negociado" id="negociado" class='select2-me input-block-level' required>
                                                        <option value="true" {{$graphic->negociado ? 'selected':''}}>SIM</option>
                                                        <option value="false" {{!$graphic->negociado ? 'selected':''}}>NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="negociado" class="control-label">Boleto Gerado</label>
                                                <div class="controls controls-row">
                                                    <select name="boleto" id="boleto" class='select2-me input-block-level' required>
                                                        <option value="true" {{$graphic->boleto ? 'selected':''}}>SIM</option>
                                                        <option value="false" {{!$graphic->boleto ? 'selected':''}}>NÃO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(Auth::user()->level > 1) {?>
                                            <div class="span2">
                                                <div class="control-group">
                                                    <label for="student_id" class="control-label">Tipo</label>
                                                    <div class="controls controls-row">
                                                        <select name="tipo" id="tipo" class='input-block-level'>
                                                            @foreach($tipos as $key => $value)
                                                                <option value="{{$key}}" {{$graphic->tipo == $key ? 'selected':''}} ? 'selected':''}}>{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }else{?>
                                            <div class="span2">
                                                <div class="control-group">
                                                    <label for="fase" class="control-label">Tipo</label>
                                                    <div class="controls controls-row">
                                                        <input type="text" name="tipo" id="tipo"  value="{{$tipos[$graphic->tipo]}}" placeholder="fase" max="255" class="input-block-level" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?> 
                                    </div>
                                    <div class="row-fluid">
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                            <a href="{{route('graphics.index')}}" class="btn">Cancelar</a>
                                        </div>
                                    </div>
                                </form>


@include('students.cep')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="row-fluid">

                <div class="span12">
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3><i class="icon-th-list"></i> Histórico</h3>
                        </div>
                        <div class="box-content ">

                            <textarea name="history" id="history" rows="5" class="input-block-level" placeholder="Descreva a negociação..."></textarea>

                            <div class="controls">
                                <label class='checkbox'>
                                <i class="icon-calendar"></i> <input type="checkbox" name="schedule" id="schedule">Agendar
                                </label>

                                <div id="calendario">

                                    <div class="row-fluid">

                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="password" class="control-label">Data*</label>
                                                <div class="controls">
                                                    <input type="text" name="agendamento-data" value="" id="agendamento-data" placeholder="00/00/0000" class="input-block-level datepick" maxlength="10">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="agendamento-hora" class="control-label">Hora*</label>
                                                <div class="controls controls-row">
                                                    <select name="agendamento-hora" id="agendamento-hora" class='select2-me input-block-level'>
                                                        <?php for($i = 0;$i < 24;$i++){
                                                            $esquerdo = NULL;
                                                            if($i <= 9){
                                                                $esquerdo = $esquerdo.'0';
                                                            } ?>
                                                        <option value="{{$esquerdo}}{{$i}}">{{$esquerdo}}{{$i}}</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="agendamento-minuto" class="control-label">Minuto*</label>
                                                <div class="controls controls-row">
                                                    <select name="agendamento-minuto" id="agendamento-minuto" class='select2-me input-block-level'>
                                                        <?php for($i = 0;$i < 60;$i++){
                                                            $esquerdo = NULL;
                                                            if($i <= 9){
                                                                $esquerdo = $esquerdo.'0';
                                                            } ?>
                                                        <option value="{{$esquerdo}}{{$i}}">{{$esquerdo}}{{$i}}</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="control-group">
                                <button type="button" class="btn btn-primary" onclick="saveHistory()">Registrar</button>
                            </div>

                        </div>

                        <div class="box-content nopadding">

                            <table id="history-table" class="table table-hover table-nomargin table-colored-header">

                                <tbody>
                                @foreach ($graphic->graphicHistories as $value)
                                <tr>
                                    <td>
                                        @if($value->schedule == 'open')
                                            <i class="icon-calendar"></i>&nbsp;-&nbsp;
                                            <i class="icon-unlock"></i>&nbsp;-&nbsp;
                                        @elseif($value->schedule == 'close')
                                            <i class="icon-calendar"></i>&nbsp;-&nbsp;
                                            <i class="icon-lock"></i>&nbsp;-&nbsp;
                                        @endif
                                    Data: <strong>{{$value->created_at}}</strong>&nbsp;-&nbsp;Usuário: <strong>{{$value->user->name}}</strong>
                                    <p>{{$value->observacao}}</p>

                                    @if($value->schedule == 'open')
                                        <form action="{{route('history.update')}}" method="POST" onSubmit="return confirm('Deseja encerrar?');" style="padding: 0px;margin:0px;">
                                            @csrf
                                            @method('post')
                                            <input type="hidden" name="id" value="{{$value->id}}">
                                            <input type="hidden" name="modulo" value="grafica">
                                            <input type="hidden" name="tela" value="formulario">
                                            <button type="submit" class="btn btn-warning" rel="tooltip" title="" data-original-title="Encerrar"><i class="fa fa-trash"></i> Encerrar</button>
                                        </form>
                                    @endif

                                    </td>
                                </tr>
                                @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>


            </div>


            <script>

            $(document).ready(function() {
                $('#calendario').hide();
                $('#schedule').click(function() {
                    if ($(this).is(':checked')) {
                        $('#calendario').show();
                    }else{
                        $('#agendamento-data').val('');
                        $('#calendario').hide();
                    }
                });

                var x = 1;
                var max_fields = 20;
                var wrapper = $(".container1");
                var add_button = $(".add_form_field");
                var inputs = '';
                inputs += '        <div class="row-fluid">';
                inputs += '           <div class="span1">';
                inputs += '                <div class="control-group">';
                inputs += '                   <!--<label for="parcela" class="control-label">Parcela</label>-->';
                inputs += '                    <div class="controls controls-row">';
                inputs += '                        <input type="number" name="parcela[]" id="parcela" placeholder="0" class="input-block-level" required>';
                inputs += '                    </div>';
                inputs += '                </div>';
                inputs += '            </div>';
                inputs += '            <div class="span2">';
                inputs += '                <div class="control-group">';
                inputs += '                    <!--<label for="data" class="control-label">Data</label>-->';
                inputs += '                   <div class="controls controls-row">';
                inputs += '                        <input type="text" name="vencimento[]" id="vencimento" placeholder="00/00/0000" class="date input-block-level datepick" required>';
                inputs += '                    </div>';
                inputs += '                </div>';
                inputs += '            </div>';
                inputs += '           <div class="span2">';
                inputs += '                <div class="control-group">';
                inputs += '                    <!--<label for="valor" class="control-label">Valor</label>-->';
                inputs += '                    <div class="controls controls-row">';
                inputs += '                        <input type="text" name="valor[]" id="valor" placeholder="100,00"  class="money input-block-level" required>';
                inputs += '                    </div>';
                inputs += '               </div>';
                inputs += '            </div>';
                inputs += '            <div class="span2">';
                inputs += '                <div class="control-group">';
                inputs += '                    <!--<label for="data" class="control-label">Data Pg</label>-->';
                inputs += '                   <div class="controls controls-row">';
                inputs += '                        <input type="text" name="dt_pagamento[]" id="dt_pagamento" placeholder="00/00/0000" class="date input-block-level datepick">';
                inputs += '                    </div>';
                inputs += '                </div>';
                inputs += '            </div>';
                inputs += '            <div class="span2">';
                inputs += '                <div class="control-group">';
                inputs += '                    <!--<label for="data" class="control-label">Data Pg</label>-->';
                inputs += '                   <div class="controls controls-row">';
                inputs += '                         <select name="pagamento[]" id="pagamento" class=\'select2-me input-block-level\' required>';
                inputs += '                             <option value="...">...</option>';
                inputs += '                             <option value="dinheiro">Dinheiro</option>';
                inputs += '                             <option value="cartao">Cartão</option>';
                inputs += '                             <option value="cheque">Cheque</option>';
                inputs += '                             <option value="boleto">Boleto</option>';
                inputs += '                             <option value="deposito">Deposito</option>';
                inputs += '                         </select>';
                inputs += '                    </div>';
                inputs += '                </div>';
                inputs += '           </div>';
                inputs += '           <div class="span2">';
                inputs += '                <div class="control-group">';
                inputs += '                    <!--<label for="valor" class="control-label">Valor</label>-->';
                inputs += '                    <div class="controls controls-row">';
                inputs += '                        <input type="text" name="valor_pago[]" id="valor_pago" placeholder="100,00"  class="money input-block-level">';
                inputs += '                    </div>';
                inputs += '               </div>';
                inputs += '            </div>';
                inputs += '       <a href="#" class="delete">Delete</a></div>';

                $(add_button).click(function(e) {
                    e.preventDefault();
                    if (x < max_fields) {
                        x++;

                        //$(wrapper).append('<div><input type="text" name="mytext[]"/><a href="#" class="delete">Delete</a></div>'); //add input box
                        $(wrapper).append(inputs); //add input box
                    } else {
                        alert('You Reached the limits')
                    }
                });

                $(wrapper).on("click", ".delete", function(e) {
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;
                })
            });

            // Mascaras formulario
            (function( $ ) {
            $(function() {
                $('.date').mask('00/00/0000');
                $('.money').mask('#.##0,00', {reverse: true});
            });
            })(jQuery);

            // Registrar historico
            function saveHistory()
            {
                var value       = $("#history" ).val();
                var agenData    = $("#agendamento-data").val();
                var agendHora   = $("#agendamento-hora").val();
                var agendMinuto = $("#agendamento-minuto").val();
                var schedule    = $("#schedule").is(":checked");

                console.log(agendHora);

                $.ajax({
                    url: "{{route('graphicHistories.store')}}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "observacao": value,
                        "schedule": schedule,
                        "data": agenData,
                        "hora": agendHora,
                        "minuto": agendMinuto,
                        "graphic_id": {{$graphic->id}},
                    },
                    dataType: 'json',
                        success: function(data){
                            console.log(data);
                            inserirLinha(data['attributes']);
                            $("#history").val('');
                        }
                });
            }

            function inserirLinha(data)
            {
                var schedule = '';
                if(data['schedule'] == 'open'){
                    schedule = '<i class="icon-calendar"></i>&nbsp;-&nbsp;';
                }
                var html = schedule;
                html +=  'Data: <strong>'+data['created_at']+'</strong>&nbsp;-&nbsp;';
                html +=  'Usuário: <strong>{{Auth::user()->name}}</strong>';
                html +=  '<p>'+data['observacao']+'</p>';
                $("#history-table").prepend("<tr><td>"+html+"</td></tr>");
            }

            $(document).ready(function () {
                $(document).on('focus', '.datepick', function () {
                    $(this).datepicker({
                        format: 'dd/mm/yyyy',
                        language: 'pt-BR'
                    });
                });
            });

            $('.datepick').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR'
            });

            </script>


        </div>
    </div>

</div>
@endsection
