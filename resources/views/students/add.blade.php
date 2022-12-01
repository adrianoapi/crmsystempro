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
							<form action="{{route('alunos.store')}}" method="POST" class='form-vertical'>
							@csrf
								<div class="row-fluid">
                                    <div class="span1">
                                        <div class="control-group">
                                            <label for="cod_unidade" class="control-label">Unidade*</label>
                                            <div class="controls controls-row">
                                            <input type="hidden" name="modulo" value="{{$modulo}}">
                                                <input type="text" name="cod_unidade" id="cod_unidade" placeholder="00000" max="100" class="input-block-level">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span1">
                                        <div class="control-group">
                                            <label for="cod_curso" class="control-label">Curso*</label>
                                            <div class="controls controls-row">
                                                <input type="text" name="cod_curso" id="cod_curso" placeholder="00000" max="100" class="input-block-level">
                                            </div>
                                        </div>
                                    </div>
									<div class="span4">
										<div class="control-group">
											<label for="name" class="control-label">Nome completo*</label>
											<div class="controls controls-row">
												<input type="text" name="name" id="name" placeholder="Insira o nome" max="100" class="input-block-level" required>
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="nascimento" class="control-label">Nacimento</label>
											<div class="controls controls-row">
												<input type="text" name="nascimento" id="nascimento" placeholder="00/00/0000" max="10" class="input-block-level">
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="cpf_cnpj" class="control-label">CPF/CNPJ*</label>
											<div class="controls controls-row">
												<input type="text" name="cpf_cnpj" id="cpf_cnpj" placeholder="000.000.000-00/0000" max="30" class="input-block-level" required>
											</div>
										</div>
									</div>

                                    <div class="span2">
										<div class="control-group">
											<label for="ctr" class="control-label">CTR</label>
											<div class="controls controls-row">
												<input type="text" name="ctr" id="ctr" placeholder="000000" max="20" class="input-block-level">
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
                                    <div class="span3">
										<div class="control-group">
											<label for="responsavel" class="control-label">Responsável</label>
											<div class="controls controls-row">
												<input type="text" name="responsavel" id="responsavel" placeholder="Insira o nome do responsável" max="100" class="input-block-level">
											</div>
										</div>
									</div>
                                    <div class="span3">
										<div class="control-group">
											<label for="email" class="control-label">E-mail</label>
											<div class="controls controls-row">
												<input type="email" name="email" id="email" placeholder="Insira o e-mail" max="255" class="input-block-level">
											</div>
										</div>
									</div>

									<div class="span2">
										<div class="control-group">
											<label for="telefone" class="control-label">Telefone Fixo</label>
											<div class="controls controls-row">
												<input type="text" name="telefone" id="telefone" placeholder="(00) 0000-0000" max="20" class="input-block-level">
											</div>
										</div>
									</div>
                                    <div class="span2">
										<div class="control-group">
											<label for="telefone_com" class="control-label">Telefone Comercial</label>
											<div class="controls controls-row">
												<input type="text" name="telefone_com" id="telefone_com" placeholder="(00) 0000-0000" max="20" class="input-block-level">
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label for="celular" class="control-label">Telefone celular</label>
											<div class="controls controls-row">
												<input type="text" name="celular" id="celular" placeholder="(00) 00000-0000" max="20" class="input-block-level">
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="row-fluid">
									<div class="span2">
										<div class="control-group">
											<label for="cep" class="control-label">CEP*</label>
											<div class="controls controls-row">
												<input type="text" name="cep" id="cep" placeholder="00000000" max="9" class="input-block-level">
											</div>
										</div>
									</div>
									<div class="span8">
										<div class="control-group">
											<label for="endereco" class="control-label">Endereço* <samll><b><a href="javascript:void(0)" onClick="consultaCep()" id="a_cep">Auto completar</a></b></small></label>
											<div class="controls controls-row">
												<input type="text" name="endereco" id="endereco" placeholder="Endereço" max="255" class="input-block-level">
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label for="numero" class="control-label">Número*</label>
											<div class="controls controls-row">
												<input type="number" name="numero" id="numero" placeholder="Número" max="99999" class="input-block-level">
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span2">
										<div class="control-group">
											<label for="complemento" class="control-label">Complemento</label>
											<div class="controls controls-row">
												<input type="text" name="complemento" id="textfield" placeholder="complemento" max="20" class="input-block-level">
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label for="bairro" class="control-label">Bairro*</label>
											<div class="controls controls-row">
												<input type="text" name="bairro" id="bairro" placeholder="bairro" max="255" class="input-block-level">
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label for="cidade" class="control-label">Cidade*</label>
											<div class="controls controls-row">
												<input type="text" name="cidade" id="cidade" placeholder="cidade" max="255" class="input-block-level" >
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label for="estado" class="control-label">Estado*</label>
											<div class="controls controls-row">
                                                <select name="estado" id="estado" class='select2-me input-block-level'>
                                                    @foreach($estados as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
											</div>
										</div>
									</div>
								</div>
								<!--<div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="negociado" class="control-label">Negociado*</label>
                                            <div class="controls controls-row">
                                                <select name="negociado" id="negociado" class='select2-me input-block-level'>
                                                    <option value="true">SIM</option>
                                                    <option value="false">NÃO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="row-fluid">
									<!--<div class="span12">
										<div class="control-group">
											<label for="observacao" class="control-label">Observação</label>
											<div class="controls controls-row">
												<textarea name="observacao" id="observacao" placeholder="observacao..." class="input-block-level"></textarea>
											</div>
										</div>
									</div>-->
									<div class="form-actions">
										<button type="submit" class="btn btn-primary">Salvar</button>
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
    $('#nascimento').mask('00/00/0000');
  });
})(jQuery);
</script>

@include('students.cep')

@endsection
