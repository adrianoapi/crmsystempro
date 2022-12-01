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

                            @if(!count($queueds))
                            @else
                                <form action="{{route('importacao.destroy', ['queued' => $queueds[0]->id])}}" method="POST" onSubmit="return confirm('Deseja excluir?');" style="padding: 0px;margin:0px;">
                                    @csrf
                                    @method('delete')
                                </form>
                            @endif

                            </span>
                            <span class="tabs">

                            </span>
                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-nomargin table-bordered">
                                <thead>
                                    <th>ID</th>
                                    <th>Upload</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                </thead>
                                <tbody>
                                <?php

                                foreach($queueds as $queued):

                                    ?>
                                    <tr>
                                        <td>{{$queued->id}}</td>
                                        <td>{{$queued->created_at}}</td>
                                        <td>{{$queued->user->name}}</td>
                                        <td>
                                            <a href="{{route('importacao.history.show', ['queued' => $queued->id])}}" target="_blank" class="btn" rel="tooltip" title="" data-original-title="Visualizar">
                                                <i class="icon-search"></i>
                                            </a>
                                        </td>
                                    </tr>
                                     <?php

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
