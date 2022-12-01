@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" id="content">

    @include('layouts.navigation')

    <div id="main">
        <div class="container-fluid">

            <div class="row-fluid">
                <div class="span12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                <i class="icon-reorder"></i>
                                Basic Widget
                            </h3>
                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-hover table-nomargin table-colored-header">
                                <thead>
                                    <tr>
                                        <th>Rendering engine</th>
                                        <th>Browser</th>
                                        <th class='hidden-350'>Platform(s)</th>
                                        <th class='hidden-1024'>Engine version</th>
                                        <th class='hidden-480'>CSS grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Trident</td>
                                        <td>
                                            Internet
                                            Explorer 4.0
                                        </td>
                                        <td class='hidden-350'>Win 95+</td>
                                        <td class='hidden-1024'>4</td>
                                        <td class='hidden-480'>X</td>
                                    </tr>
                                    <tr>
                                        <td>Presto</td>
                                        <td>Nokia N800</td>
                                        <td class='hidden-350'>N800</td>
                                        <td class='hidden-1024'>-</td>
                                        <td class='hidden-480'>A</td>
                                    </tr>
                                    <tr>
                                        <td>Misc</td>
                                        <td>NetFront 3.4</td>
                                        <td class='hidden-350'>Embedded devices</td>
                                        <td class='hidden-1024'>-</td>
                                        <td class='hidden-480'>A</td>
                                    </tr>
                                    <tr>
                                        <td>Misc</td>
                                        <td>Dillo 0.8</td>
                                        <td class='hidden-350'>Embedded devices</td>
                                        <td class='hidden-1024'>-</td>
                                        <td class='hidden-480'>X</td>
                                    </tr>
                                    <tr>
                                        <td>Misc</td>
                                        <td>Links</td>
                                        <td class='hidden-350'>Text only</td>
                                        <td class='hidden-1024'>-</td>
                                        <td class='hidden-480'>X</td>
                                    </tr>
                                    <tr>
                                        <td>Misc</td>
                                        <td>Lynx</td>
                                        <td class='hidden-350'>Text only</td>
                                        <td class='hidden-1024'>-</td>
                                        <td class='hidden-480'>X</td>
                                    </tr>
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
