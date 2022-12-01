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

                            </span>
                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-hover table-nomargin table-bordered table-colored-header table-striped">
                                <tbody>
                                    <thead>
                                    <tr>
                                        <th colspan="2">
                                            <form action="{{route('history.index')}}" method="GET" class="span12" style="margin: 0;padding:0;">
                                                <div class="span12">
                                                    <div class="control-group">
                                                        <div class="controls controls-row">
                                                            <select name="modulo" id="modulo" class='input-block-level'>
                                                                <option value="" {{$modulo == '' ? 'selected':''}}>Modulo?</option>
                                                                <option value="contrato_segunda" {{$modulo == 'contrato_segunda' ? 'selected':''}}>2ª Fase</option>
                                                                <option value="contrato_terceira" {{$modulo == 'contrato_terceira' ? 'selected':''}}>3ª Fase</option>
                                                                <option value="cheque" {{$modulo == 'cheque' ? 'selected':''}}>CHEQUE</option>
                                                                @foreach($tipos as $key => $value)
                                                                    <option value="{{$key}}" {{$modulo == $key ? 'selected':''}} ? 'selected':''}}>{{$value}}</option>
                                                                @endforeach
                                                            </select> 
                                                        </div>
                                                    </div>
                                                </div>

                                        </th>
                                        <th colspan="1"></th>
                                        <th colspan="2"></th>
                                        <th>
                                                <div class="span12">
                                                    <div class="control-group">
                                                        <div class="controls controls-row">
                                                            <span class="input-group-append">
                                                                <button type="submit" class="btn btn-sm" style="margin-top:-10px;">Pesquisar</button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </th>
                                    </tr>
                                        <tr>
                                            <th>Contato</th>
                                            <th>Módulo</th>
                                            <th>Nome</th>
                                            <th>Retorno</th>
                                            <th>Observação</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    @foreach($historical as $value)
                                        <tr>
                                            <td>{{$value->created_at}}</td>
                                            <td>
                                                @if($value->modulo == 'cheque')
                                                    Cheque
                                                @elseif($value->modulo == 'contrato')
                                                    {{ucfirst($value->fase)}}
                                                @else
                                                    {{$tipos[$value->tipo]}}
                                                @endif
                                            </td>
                                            <td>
                                            @if($value->modulo == 'cheque')
                                            <a href="{{route('bankCheques.show', ['graphic' => $value->modulo_id])}}">{{$value->name}}</a>
                                            @elseif($value->modulo == 'contrato')
                                            <a href="{{route('defaultings.show', ['defaulting' => $value->modulo_id])}}">{{$value->name}}</a>
                                            @else
                                            <a href="{{route('graphics.show', ['graphic' => $value->modulo_id])}}">{{$value->name}}</a>
                                            @endif
                                            </td>
                                            <td>{{$value->dt_retorno}}</td>
                                            <td>{{$value->observacao}}</td>
                                            <td>
                                                <form action="{{route('history.update')}}" method="POST" onSubmit="return confirm('Deseja encerrar?');" style="padding: 0px;margin:0px;">
                                                    @csrf
                                                    @method('post')
                                                    <input type="hidden" name="id" value="{{$value->id}}">
                                                    <input type="hidden" name="modulo" value="{{$value->modulo}}">
                                                    <input type="hidden" name="tela" value="listagem">
                                                    <button type="submit" class="btn btn-warning" rel="tooltip" title="" data-original-title="Encerrar"><i class="fa fa-trash"></i> Encerrar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
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
