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
                                <a href="{{route('alunos.create')}}" class="btn btn-primary">
                                <i class="icon-reorder"></i> Novo</a>
                            </span>
                            <span class="tabs">
                                <form action="{{route('alunos.index')}}" method="GET" class="span12" style="margin: 0;padding:0;">
                                    <div class="input-group span12">
                                        <input type="hidden" name="filtro" value="pesquisa">
                                        <input placeholder="Search" type="text" name="pesquisar" value="{{$pesuisar}}" class="form-control form-control-sm">
                                        <span class="input-group-append">
                                            <button type="submit" style="margin-top:-10px;" class="btn btn-sm btn-primary">Go!</button>
                                        </span>
                                    </div>
                                </form>
                            </span>
                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-hover table-nomargin table-colored-header">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Unidade</th>
                                        <th>CTR</th>
                                        <th>Negociado</th>
                                        <th>Celular</th>
                                        <th class='hidden-350'>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($students as $value)
                                    <tr>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->cod_curso}}</td>
                                        <td>{{$value->ctr}}</td>
                                        <td><?php
                                            if($value->negociado){
                                                echo '<button class="btn btn-small  btn-success">SIM</button>';
                                            }else{
                                                echo '<button class="btn btn-small  btn-danger">NÃO</button>';
                                            }
                                        ?></td>
                                        <td>{{$value->celular}}</td>
                                        <td>
                                            <form action="{{route('alunos.destroy', ['Student' => $value->id])}}" method="POST" onSubmit="return confirm('Deseja excluir?');" style="padding: 0px;margin:0px;">
                                                @csrf
                                                @method('delete')
                                                <a href="{{route('alunos.edit', ['Student' => $value->id])}}" class="btn" rel="tooltip" title="" data-original-title="Editar">
                                                    <i class="icon-edit"></i>
                                                </a>
                                                <button type="submit" class="btn" rel="tooltip" title="" data-original-title="Excluir">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $students->links('layouts.pagination') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
