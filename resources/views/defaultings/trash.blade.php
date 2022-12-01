@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" id="content">

    @include('layouts.navigation')

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
                                <a href="{{route('defaultings.index')}}" class="btn btn-primary">
                                <i class="icon-reorder"></i> Voltar</a>
                            </span>
                            <span class="tabs">

                            </span>
                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-hover table-nomargin table-bordered table-colored-header">
                                <thead>
                                    <tr>
                                        <th>Fase</th>
                                        <th>Nome</th>
                                        <th>Negociado</th>
                                        <th>Boleto</th>
                                        <th>Fones</th>
                                        <th>Total Líquido</th>
                                        <th>Total Geral</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($defaultings as $value)
                                    <tr>
                                        <td>{{$value->student->fase}}</td>
                                        <td>
                                            {{$value->student->name}}
                                            <br><small>Unid: {{$value->student->cod_unidade}} / Curso: {{$value->student->cod_curso}} / CTR: {{$value->student->ctr}} / CPF: {{$value->student->celular}}</small>
                                        </td>
                                        <td><?php

                                            $m_parcelas = $value->m_parcelas;
                                            $m_parcela_pg = $value->m_parcela_pg;
                                            $m_pendente = $value->m_parcelas - $value->m_parcela_pg;
                                            $m_parcela_valor = $value->m_parcela_valor;

                                            $s_parcelas = $value->s_parcelas;
                                            $s_parcela_pg = $value->s_parcela_pg;
                                            $s_pendente = $value->s_parcelas - $value->s_parcela_pg;
                                            $s_parcela_valor = $value->s_parcela_valor;

                                            if($value->student->negociado){
                                                echo '<button class="btn btn-small  btn-success">SIM</button>';
                                            }else{
                                                echo '<button class="btn btn-small  btn-danger">NÃO</button>';
                                            }
                                        ?></td>
                                        <td><?php
                                            if($value->student->boleto){
                                                echo '<button class="btn btn-small  btn-success">SIM</button>';
                                            }else{
                                                echo '<button class="btn btn-small  btn-danger">NÃO</button>';
                                            }
                                        ?></td>
                                        <td>{{$value->student->telefone}}/{{$value->student->celular}}</td>
                                        <td>
                                            <?php
                                                #Total Líquido
                                                $valor = str_replace(',', '.', str_replace('.', '', $s_parcela_valor));
                                                $s_valor_total = ($value->s_parcelas - $value->s_parcela_pg) * $valor;

                                                $multa = str_replace(',', '.', str_replace('.', '', $value->multa));
                                                $multa = $multa * $s_valor_total / 100;

                                                $valor = str_replace(',', '.', str_replace('.', '', $m_parcela_valor));
                                                $m_valor_total = ($m_parcelas - $m_parcela_pg) * $valor;

                                                $total = $m_valor_total + $multa;
                                                echo number_format($total, 2, ',', '.');
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            #Total Geral
                                            $total = $m_valor_total + $s_valor_total;
                                            echo number_format($total, 2, ',', '.');
                                           ?>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <script>
                            $("#negociado").change( function() {
                                $("#pesquisar").val('');
                            });
                            $("#boleto").change( function() {
                                $("#pesquisar").val('');
                            });
                            </script>
                            {{ $defaultings->links('layouts.pagination') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
