@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid" id="content">

    @include('layouts.navigation')

    <style>
        tbody tr:nth-child(even)
        {
            background: #cce6ff;
        }
        tbody tr:nth-child(odd)
        {
            background: #e6f3ff;
        }
    </style>

    <div id="main">
        <div class="container-fluid">

            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-reorder"></i>
                                {{$title}}
                            </h3>
                            <span class="tabs">

                            @if(!count($queueds))
                               <form enctype="multipart/form-data" action="{{route('importacao.upload')}}" method="POST" class="span12" style="margin: 0;padding:0;">
                                    @csrf

                                    <label>Upload Arquivo CSV</label>
                                    <input size='50' type='file' name='filename'>
                                    <input type="hidden" name="modulo" value="{{$modulo}}">
                                    <input type='submit' name='submit' value='Enviar CSV'>

                                </form>
                            @else
                                <form action="{{route('importacao.destroy', ['queued' => $queueds[0]->id])}}" method="POST" onSubmit="return confirm('Deseja excluir?');" style="padding: 0px;margin:0px;">
                                    @csrf
                                    @method('delete')
                                    <a href="{{route('importacao.process', ['queued'   => $queueds[0]->id])}}" class="btn-success btn" rel="tooltip" title="" data-original-title="Salver registros">Registrar fila <i class="icon-save"></i></a>
                                    <button type="submit" class="btn-danger btn" rel="tooltip" title="" data-original-title="Excluir registros">Excluir fila <i class="icon-trash"></i></button>
                                </form>
                            @endif

                            </span>
                            <span class="tabs">

                            </span>
                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-nomargin table-bordered">
                                <thead>
                                    <th>Linha</th>
                                    <th>Pessoa</th>
                                    @if($modulo == 'contrato')
                                    <th>FASE</th>
                                    @else
                                    <th>Valor</th>
                                    @endif
                                    @if($modulo == 'cheque')
                                    <th>Cheque</th>
                                    @endif
                                    @if($modulo == 'contrato')
                                    <th>MATERIAL</th>
                                    <th>SERVIÇO</th>
                                    @endif
                                </thead>
                                <tbody>
                                <?php

                                foreach($queueds as $queued):

                                    $body = json_decode($queued->body);
                                    $i=0;

                                if($queued->module == "contrato")
                                {
                                    foreach($body as $value):
                                   ?>
                                    <tr>
                                        <td style="vertical-align: top">{{++$i}}</td>
                                        <td style="vertical-align: top">
                                            <strong>unidade:</strong> {{$value->students->cod_unidade}}<br>
                                            <strong>nome:</strong> {{$value->students->name}}<br>
                                            <strong>cod:</strong> {{$value->students->cod_curso}}<br>
                                            <strong>ctr:</strong> {{$value->students->ctr}}<br>
                                            <strong>cpf/cnpj:</strong> {{$value->students->cpf_cnpj}}<br>
                                            <strong>telefones:</strong> [{{$value->students->telefone}}] [{{$value->students->telefone_com}}] [{{$value->students->celular}}]<br>
                                            <strong>cep:</strong> {{$value->students->cep}}<br>
                                            <strong>endereço:</strong> {{$value->students->endereco}}<br>
                                            <strong>bairro:</strong> {{$value->students->bairro}}<br>
                                            <strong>cidade:</strong> {{$value->students->cidade}}<br>
                                            <strong>estado:</strong> {{$value->students->estado}}<br>
                                            <strong>email:</strong> {{$value->students->email}}<br>
                                        </td>
                                        <td style="vertical-align: top">
                                            <strong>fase:</strong> {{$value->defaultings->fase}}<br>
                                        </td>
                                        <td style="vertical-align: top">
                                            <strong>Vencimento:</strong> {{$value->defaultings->dt_inadimplencia}}<br>
                                            <strong>Parcela Paga:</strong> {{$value->defaultings->m_parcela_pg}}<br>
                                            <strong>Total Parcela:</strong> {{$value->defaultings->m_parcelas}}<br>
                                            <strong>Valor Material:</strong> {{$value->defaultings->m_parcela_valor}}<br><br>
                                        </td>
                                        <td style="vertical-align: top">
                                            <strong>Parcela Paga:</strong> {{$value->defaultings->s_parcela_pg}}<br>
                                            <strong>Total Parcela:</strong> {{$value->defaultings->s_parcelas}}<br>
                                            <strong>Valor Serviço:</strong> {{$value->defaultings->s_parcela_valor}}<br>
                                            <strong>Multa:</strong> {{$value->defaultings->multa}}<br>
                                        </td>
                                    </tr>
                                <?php
                                    endforeach;
                                }


                                    if($queued->module == "grafica")
                                    {
                                        foreach($body as $value):
                                ?>
                                        <tr>
                                            <td style="vertical-align: top">{{++$i}}</td>
                                            <td style="vertical-align: top">
                                                <strong>unidade:</strong> {{$value->students->cod_unidade}}<br>
                                                <strong>nome:</strong> {{$value->students->name}}<br>
                                                <strong>cod:</strong> {{$value->students->cod_curso}}<br>
                                                <strong>ctr:</strong> {{$value->students->ctr}}<br>
                                                <strong>cpf/cnpj:</strong> {{$value->students->cpf_cnpj}}<br>
                                                <strong>telefones:</strong> [{{$value->students->telefone}}] [{{$value->students->telefone_com}}] [{{$value->students->celular}}]<br>
                                            </td>
                                            <td style="vertical-align: top">
                                                <strong>tipo:</strong> {{$value->graphics->tipo}}<br>
                                                <strong>valor:</strong> {{$value->graphics->valor}}<br>
                                                <strong>dt_vencimento:</strong> {{$value->graphics->dt_vencimento}}<br>
                                                <strong>parcela:</strong> {{$value->graphics->parcela}}<br>
                                                <strong>total:</strong> {{$value->graphics->total}}<br>
                                            </td>
                                        </tr>
                                <?php
                                        endforeach;
                                    }

                                    if($queued->module == "cheque")
                                    {
                                        foreach($body as $value):
                                    ?>
                                        <tr>
                                            <td style="vertical-align: top">{{++$i}}</td>
                                            <td style="vertical-align: top">
                                                <strong>unidade:</strong> {{$value->students->cod_unidade}}<br>
                                                <strong>nome:</strong> {{$value->students->name}}<br>
                                                <strong>cod:</strong> {{$value->students->cod_curso}}<br>
                                                <strong>ctr:</strong> {{$value->students->ctr}}<br>
                                                <strong>cpf/cnpj:</strong> {{$value->students->cpf_cnpj}}<br>
                                                <strong>telefones:</strong> [{{$value->students->telefone}}] [{{$value->students->telefone_com}}] [{{$value->students->celular}}]<br>
                                            </td>
                                            <td style="vertical-align: top">
                                                <strong>valor:</strong> {{$value->bank_cheques->valor}}
                                            </td>
                                            <td>
                                                @foreach($value->bank_cheque_plots as $plot)
                                                <ul>
                                                    <li><strong>banco:</strong> {{$plot->banco}}</li>
                                                    <li><strong>agencia:</strong> {{$plot->agencia}}</li>
                                                    <li><strong>conta:</strong> {{$plot->conta}}</li>
                                                    <li><strong>cheque:</strong> {{$plot->cheque}}</li>
                                                    <li><strong>vencimento:</strong> {{$plot->vencimento}}</li>
                                                    <li><strong>valor:</strong> {{$plot->valor}}</li>
                                                </ul>
                                                @endforeach
                                            </td>
                                        </tr>

                                    <?php
                                        endforeach;
                                    }

                                endforeach;
                                ?>
                                </tbody>

                            </table>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
