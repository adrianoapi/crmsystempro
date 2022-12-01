@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid" id="content">

    @include('layouts.navigation')

    <div id="main">
        <div class="container-fluid">

			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3><i class="icon-th-list"></i> {{$title}}</h3>
						</div>
						<div class="box-content">


                            <span class="add_form_field btn btn-teal">Adicionar Parcela &nbsp;
                                <span  span style="font-size:16px; font-weight:bold;">+ </span>
                            </span>

                            <form action="{{route('bankChequePlots.store')}}" method="POST" class='form-vertical'>
                                @csrf
                                @method('POST')
                                <input type="hidden" name="bank_cheque_id" value="{{$bankCheque->id}}">
                                <div class="row-fluid container1">

                                    <div class="row-fluid">
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="vencimento" class="control-label">Vencimento</label>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="banco" class="control-label">Banco</label>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <div class="control-group">
                                                <label for="agencia" class="control-label">Agência</label>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="conta" class="control-label">Conta</label>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="cheque" class="control-label">Cheque</label>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <label for="valor" class="control-label">Valor</label>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach ($bankCheque->BankChequePlots as $value)
                                    <div class="row-fluid">
                                        <div class="span2">
                                            <div class="control-group">
                                                <!--<label for="vencimento" class="control-label">vencimento</label>-->
                                                <div class="controls controls-row">
                                                    <input type="text" name="vencimento[]" value="{{$value->vencimento}}" id="vencimento" placeholder="00/00/0000" class="date input-block-level datepick" required="" maxlength="10"></div>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <div class="control-group">
                                                <!--<label for="banco" class="control-label">banco</label>-->
                                                <div class="controls controls-row">
                                                    <input type="text" name="banco[]" value="{{$value->banco}}" id="banco" placeholder="123" class="input-block-level" required=""></div>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <div class="control-group">
                                                <!--<label for="agencia" class="control-label">agencia</label>-->
                                                <div class="controls controls-row">
                                                    <input type="text" name="agencia[]" value="{{$value->agencia}}" id="agencia" placeholder="456" class="input-block-level" required=""></div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <!--<label for="agencia" class="control-label">agencia</label>-->
                                                <div class="controls controls-row">
                                                    <input type="text" name="conta[]" value="{{$value->conta}}" id="conta" placeholder="12345" class="input-block-level" required=""></div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <!--<label for="cheque" class="control-label">cheque</label>-->
                                                <div class="controls controls-row">
                                                    <input type="text" name="cheque[]" value="{{$value->cheque}}" id="cheque" placeholder="789-0" class="input-block-level" required=""></div>
                                            </div>
                                        </div>
                                        <div class="span2">
                                            <div class="control-group">
                                                <!--<label for="valor" class="control-label">Valor</label>-->
                                                <div class="controls controls-row">
                                                    <input type="text" name="valor[]" value="{{$value->valor}}" id="valor" placeholder="100,00" class="money input-block-level" required=""></div>
                                            </div>
                                        </div>
                                        <a href="#" class="delete">Delete</a>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row-fluid">
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                        <a href="{{route('bankCheques.index')}}" class="btn">Cancelar</a>
                                    </div>
                                </div>
                            </form>
						</div>
					</div>
				</div>
			</div>


        </div>
    </div>

</div>

<script>

    $(document).ready(function() {
        var x = 1;
        var max_fields = 20;
        var wrapper = $(".container1");
        var add_button = $(".add_form_field");
        var inputs = '';
        inputs += '        <div class="row-fluid">';
        inputs += '           <div class="span2">';
        inputs += '                <div class="control-group">';
        inputs += '                    <div class="controls controls-row">';
        inputs += '                        <input type="text" name="vencimento[]" id="vencimento" placeholder="00/00/0000" class="date input-block-level datepick" required>';
        inputs += '                    </div>';
        inputs += '                </div>';
        inputs += '            </div>';
        inputs += '            <div class="span1">';
        inputs += '                <div class="control-group">';
        inputs += '                   <div class="controls controls-row">';
        inputs += '                        <input type="text" name="banco[]" id="banco" placeholder="123" class="input-block-level" required>';
        inputs += '                    </div>';
        inputs += '                </div>';
        inputs += '            </div>';
        inputs += '           <div class="span1">';
        inputs += '                <div class="control-group">';
        inputs += '                    <div class="controls controls-row">';
        inputs += '                        <input type="text" name="agencia[]" id="agencia" placeholder="456"  class="input-block-level" required>';
        inputs += '                    </div>';
        inputs += '               </div>';
        inputs += '            </div>';
        inputs += '           <div class="span2">';
        inputs += '                <div class="control-group">';
        inputs += '                    <div class="controls controls-row">';
        inputs += '                        <input type="text" name="conta[]" id="conta" placeholder="12345"  class="input-block-level" required>';
        inputs += '                    </div>';
        inputs += '               </div>';
        inputs += '            </div>';
        inputs += '            <div class="span2">';
        inputs += '                <div class="control-group">';
        inputs += '                   <div class="controls controls-row">';
        inputs += '                        <input type="text" name="cheque[]" id="cheque" placeholder="789-0" class="input-block-level">';
        inputs += '                    </div>';
        inputs += '                </div>';
        inputs += '            </div>';
        inputs += '           <div class="span2">';
        inputs += '                <div class="control-group">';
        inputs += '                    <div class="controls controls-row">';
        inputs += '                        <input type="text" name="valor[]" id="valor" placeholder="100,00"  class="money input-block-level">';
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

@include('students.cep')

@endsection
