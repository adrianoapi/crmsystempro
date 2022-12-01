<table border=1>
    <thead>
        <tr>
            <th>Fase</th>
            <th>Uni</th>
            <th>Cod</th>
            <th>Ctr</th>
            <th>Cpf</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Celular</th>
            <th>Comercial</th>
            <th>Negociado</th>
            <th>Boleto</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($defaultings as $value)
        <tr>
            <td>{{$value->fase}}</td>
            <td>{{$value->student->cod_unidade}}</td>
            <td>{{$value->student->cod_curso}}</td>
            <td>{{$value->student->ctr}}</td>
            <td>{{$value->student->cpf_cnpj}}</td>
            <td>{{$value->student->name}}</td>
            <td>{{$value->student->telefone}}</td>
            <td>{{$value->student->celular}}</td>
            <td>{{$value->student->comercial}}</td>
            <td><?php
                if($value->negociado){
                    echo 'SIM';
                }else{
                    echo 'NÃO';
                }
            ?></td>
            <td><?php
                if($value->boleto){
                    echo 'SIM';
                }else{
                    echo 'NÃO';
                }
            ?></td>
        </tr>
    @endforeach
    </tbody>
</table>
