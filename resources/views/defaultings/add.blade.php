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
							<form action="{{route('defaultings.store')}}" method="POST" class='form-vertical'>
							@csrf
								<div class="row-fluid">
									<div class="span10">
										<div class="control-group">
											<label for="student_id" class="control-label">Aluno</label>
											<div class="controls controls-row">
												<select name="student_id" id="student_id" class='select2-me input-block-level' required>
													@foreach($students as $student)
													<option value="{{$student->id}}">{{$student->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="dt_inadimplencia" class="control-label">Data inadimplencia</label>
											<div class="controls controls-row">
												<input type="text" name="dt_inadimplencia" id="dt_inadimplencia" value="{{date('d/m/Y')}}" placeholder="00/00/0000" max="10" class="input-block-level" required>
											</div>
										</div>
									</div>
								</div>

                                <h5>Material</h5>
								<div class="row-fluid">
                                    <div class="span2">
										<div class="control-group">
											<label for="m_parcelas" class="control-label">Parcelas total</label>
											<div class="controls controls-row">
												<input type="number" name="m_parcelas" id="m_parcelas" onkeypress="calcular()" onselect="calcular()" placeholder="00" max="100" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="m_parcela_pg" class="control-label">Parcelas Pagas</label>
											<div class="controls controls-row">
												<input type="number" name="m_parcela_pg" id="m_parcela_pg" onkeypress="calcular()" onselect="calcular()" placeholder="00" max="255" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label for="m_parcela_ab" class="control-label">Parcelas Abertas</label>
											<div class="controls controls-row">
												<input type="number" name="m_parcela_ab" id="m_parcela_ab" onkeypress="calcular()" onselect="calcular()" placeholder="00" max="100" step="1"  class="input-block-level" disabled>
											</div>
										</div>
									</div>
                                    <div class="span3">
										<div class="control-group">
											<label for="m_parcela_valor" class="control-label">Valor Parcela</label>
											<div class="controls controls-row">
												<input type="text" name="m_parcela_valor" id="m_parcela_valor" onkeypress="calcular()" onselect="calcular()" value="00,00"  max="100" step=".01" class="money input-block-level" required>
											</div>
										</div>
									</div>
									<div class="span3">
										<div class="control-group">
											<label for="m_parcela_total" class="control-label">Total</label>
											<div class="controls controls-row">
												<input type="text" name="m_parcela_total" id="m_parcela_total" onkeypress="calcular()" onselect="calcular()" value="00,00"  max="100" step=".01" class="money input-block-level" disabled>
											</div>
										</div>
									</div>
								</div>

                                <h5>Serviço</h5>
								<div class="row-fluid">
                                    <div class="span2">
										<div class="control-group">
											<label for="s_parcelas" class="control-label">Parcelas total</label>
											<div class="controls controls-row">
												<input type="number" name="s_parcelas" id="s_parcelas" onkeypress="calcular()" onselect="calcular()" placeholder="00" max="100" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="s_parcela_pg" class="control-label">Parcelas Pagas</label>
											<div class="controls controls-row">
												<input type="number" name="s_parcela_pg" id="s_parcela_pg" onkeypress="calcular()" onselect="calcular()" placeholder="00" max="255" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label for="s_parcela_ab" class="control-label">Parcelas Abertas</label>
											<div class="controls controls-row">
												<input type="number" name="s_parcela_ab" id="s_parcela_ab" onkeypress="calcular()" onselect="calcular()" placeholder="00" max="100" step="1"  class="input-block-level" disabled>
											</div>
										</div>
									</div>
                                    <div class="span3">
										<div class="control-group">
											<label for="s_parcela_valor" class="control-label">Valor Parcela</label>
											<div class="controls controls-row">
												<input type="text" name="s_parcela_valor" id="s_parcela_valor" onkeypress="calcular()" onselect="calcular()" value="00,00"  max="100" step=".01" class="money input-block-level" required>
											</div>
										</div>
									</div>
									<div class="span3">
										<div class="control-group">
											<label for="s_parcela_total" class="control-label">Total</label>
											<div class="controls controls-row">
												<input type="text" name="s_parcela_total" id="s_parcela_total" onkeypress="calcular()" onselect="calcular()" value="00,00" max="100" step=".01" class="money input-block-level" disabled>
											</div>
										</div>
									</div>
								</div>

								<div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="multa" class="control-label">Multa (%)</label>
                                            <div class="controls controls-row">
                                                <input type="text" name="multa" id="multa" onkeypress="calcular()" onselect="calcular()" value="10,00" max="100" step=".01"  class="money input-block-level" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="total" class="control-label">Total</label>
                                            <div class="controls controls-row">
                                                <input type="text" name="total" id="total" onkeypress="calcular()" onselect="calcular()" max="100" step=".01"  class="input-block-level" disabled>
                                            </div>
                                        </div>
                                    </div>
								</div>

								<div class="row-fluid">
									<div class="form-actions">
                                    <button type="button" class="btn btn-primary">Calcular</button>
										<button type="submit" class="btn btn-primary">Salvar</button>
										<a href="{{route('defaultings.index')}}" class="btn">Cancelar</a>
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

function mascaraValor(value)
{
    value = value.toString().replace(".", "");
    value = value.toString().replace(",", ".");
    return parseFloat(value);
}

function calcular()
{
    var m_parcelas = parseInt($("#m_parcelas").val());
    var m_parcela_pg = parseInt($("#m_parcela_pg").val());
    var m_parcela_valor = $("#m_parcela_valor").val();

    var m_parcela_pendente = m_parcelas - m_parcela_pg;
    $("#m_parcela_ab").val(m_parcela_pendente);

    var m_total = m_parcela_pendente * mascaraValor(m_parcela_valor);

    $("#m_parcela_total").val(currencyFormat(m_total));
    //------------------------------------------------

    var s_parcelas = parseInt($("#s_parcelas").val());
    var s_parcela_pg = parseInt($("#s_parcela_pg").val());
    var s_parcela_valor = $("#s_parcela_valor").val();

    var s_parcela_pendente = s_parcelas - s_parcela_pg;
    $("#s_parcela_ab").val(s_parcela_pendente);

    var s_total = s_parcela_pendente * mascaraValor(s_parcela_valor);

    $("#s_parcela_total").val(currencyFormat(s_total));
    //------------------------------------------------

    var multa = mascaraValor($("#multa").val());
    multa = multa * s_total / 100;

    var total = m_total + s_total + Math.round(multa);
    $("#total").val(currencyFormat(total));

}

autoCalcula();
function autoCalcula(){
    calcular();
    setTimeout(function(){autoCalcula();},1100);
}

function currencyFormat (num) {
    return num.toLocaleString('pt-BR');
}

(function( $ ) {
  $(function() {
    $('#dt_inadimplencia').mask('00/00/0000');
    $('.money').mask('#.##0,00', {reverse: true});
  });
})(jQuery);

</script>

@include('students.cep')

@endsection
