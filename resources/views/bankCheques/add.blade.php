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
							<form action="{{route('bankCheques.store')}}" method="POST" class='form-vertical'>
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
											<label for="dt_vencimento" class="control-label">Data inadimplencia</label>
											<div class="controls controls-row">
												<input type="text" name="dt_vencimento" id="dt_vencimento" value="{{date('d/m/Y')}}" placeholder="00/00/0000" max="10" class="input-block-level" required>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
                                    <div class="span2">
										<div class="control-group">
											<label for="banco" class="control-label">Banco</label>
											<div class="controls controls-row">
												<input type="text" name="banco" id="banco" placeholder="00" max="100" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="agencia" class="control-label">Agência</label>
											<div class="controls controls-row">
												<input type="text" name="agencia" id="agencia" placeholder="00" max="100" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="cheque" class="control-label">Cheque</label>
											<div class="controls controls-row">
												<input type="text" name="cheque" id="cheque" placeholder="00" max="100" step="1"  class="input-block-level" required>
											</div>
										</div>
									</div>
                                    <div class="span3">
										<div class="control-group">
											<label for="valor" class="control-label">Valor Cheque</label>
											<div class="controls controls-row">
												<input type="text" name="valor" id="valor" value="" placeholder="00,00"  max="100" step=".01" class="money input-block-level" required>
											</div>
										</div>
									</div>
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

(function( $ ) {
  $(function() {
    $('#dt_vencimento').mask('00/00/0000');
    $('.money').mask('#.##0,00', {reverse: true});
  });
})(jQuery);

</script>

@include('students.cep')

@endsection
