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
                        <form action="{{route('graphics.update', ['graphic' => $graphic->id])}}" method="POST" class='form-vertical'>
                                @csrf
                                @method('PUT')

								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label for="student_id" class="control-label">Aluno</label>
											<div class="controls controls-row">
                                                <input type="text" name="name" id="name" value="{{$graphic->student->name}}" placeholder="nome aluno" max="10" class="input-block-level" disabled>
                                                <input type="hidden" name="student_id" value="{{$graphic->student_id}}">
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label for="student_id" class="control-label">Tipo</label>
											<div class="controls controls-row">
                                                <select name="tipo" id="tipo" class='input-block-level'>
													<option value="">Tipo</option>
													@foreach($tipos as $key => $value)
														<option value="{{$key}}" {{$graphic->tipo == $key ? 'selected':''}} ? 'selected':''}}>{{$value}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="dt_vencimento" class="control-label">Data de vencimento</label>
											<div class="controls controls-row">
												<input type="text" name="dt_vencimento" id="dt_vencimento" value="{{$graphic->dt_vencimento}}" placeholder="00/00/0000" max="10" class="input-block-level" required>
											</div>
										</div>
									</div>
								</div>

                                <h5>Material</h5>
								<div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="valor" class="control-label">Valor</label>
                                            <div class="controls controls-row">
                                                <input type="text" name="valor" id="valor" value="{{$graphic->valor}}" value="00,00"  max="100" step=".01" class="money input-block-level" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
										<div class="control-group">
											<label for="parcela" class="control-label">Parcelas</label>
											<div class="controls controls-row">
												<input type="number" name="parcela" id="parcela" value="{{$graphic->parcela}}" placeholder="0" max="255" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
									<div class="span3">
                                        <div class="control-group">
                                            <label for="total" class="control-label">Total</label>
                                            <div class="controls controls-row">
                                                <input type="text" name="total" id="total" value="{{$graphic->total}}" value="00,00"  max="100" step=".01" class="money input-block-level" required>
                                            </div>
                                        </div>
                                    </div>
								</div>

								<div class="row-fluid">
									<div class="form-actions">
										<button type="submit" class="btn btn-primary">Salvar</button>
										<a href="{{route('graphics.show', ['graphic' => $graphic->id])}}" class="btn">Cancelar</a>
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

(function( $ ) {
  $(function() {
    $('#dt_vencimento').mask('00/00/0000');
    $('.money').mask('#.##0,00', {reverse: true});
  });
})(jQuery);

</script>

@include('students.cep')

@endsection
