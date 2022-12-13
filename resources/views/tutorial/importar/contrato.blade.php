<div class="span6">
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Atenção!</strong> Este tutorial aplica-se exclusivamente para o módulo <strong>Contrato</strong>.
    </div>
</div>
<hr>
<p>&nbsp;</p>
<p>1. Limpe o arquivo excel:</p>
    <ul>
        <li>Presione Ctrl+L;</li>
        <li>Selecione a opção Localizar e Susbstituir;</li>
        <li>Remova um por um dos seguintes caracteres: "<strong>\</strong>", "<strong>'</strong>", "<strong>,</strong>", "<strong>;</strong>" e "<strong>:</strong>".
            <br>Pois, certamente estes caracteres irão interferir na leitura do arquivo CSV.</li>
    </ul>

<p>2. Mantenha o padrão das colunas: <strong>multa</strong> (00), <strong>parcela_valor/m_parcela_valor</strong> (00.00) e <strong>inadimplencia dd/mm/aaaa</strong>;</p>
<img src="{!! asset('images/importar_colunas_importantes.png') !!}" width="600px" align="right" style="margin-right: 5px; margin-top: 5px;">

<p>3. O arquivo deve conter 23 colunas na exata ordem a seguir:</p>
<ul style="margin: 5 px;">
    <li>00 - UNIDADE</li>
    <li>01 - COD</li>
    <li>02 - CTR</li>
    <li>03 - CPF-CNPJ</li>
    <li>04 - TELEFONE</li>
    <li>05 - TELEFONE</li>
    <li>06 - TELEFONE</li>
    <li>07 - email</li>
    <li>08 - cep</li>
    <li>09 - endereco</li>
    <li>10 - bairro</li>
    <li>11 - cidade</li>
    <li>12 - estado</li>
    <li>13 - NOME</li>
    <li>14 - fase</li>
    <li>15 - inadimplencia</li>
    <li>16 - m_parcela_pg</li>
    <li>17 - m_parcelas</li>
    <li>18 - m_parcela_valor</li>
    <li>19 - s_parcela_pg</li>
    <li>20 - s_parcelas</li>
    <li>21 - s_parcela_valor</li>
    <li>22 - multa</li>
</ul>

<p>4. Para exportar a planilha em CSV:</p>
<img src="{!! asset('images/importar_csv_export.png') !!}" width="680px" align="right" style="margin-right: 5px; margin-top: 5px;">
<ul>
    <li>Presione F12;</li>
    <li>No campo "Nome" coloque a informação que desejar;</li>
    <li>No campo "Tipo" selecione a opção: <strong>CSV UTF-8 (Delimitado por vírgulas) (*.csv)</strong>;
        <br>Tome cuidado para não se confudir e ecolher outra opção de csv, pois pode
        <br>acarretar em problemas com a formatação do texto.
    </li>
</ul>

<p>5. Limite de linhas por arquivo:</p>
Para melhor performance na importação, é recomendável utilizar até 500 linhas
<br>por arquivo csv, pois caso exceda muito essa quantidade, o banco de dados
<br>pode encerrar a importação do arquivo, gerando assim uma fila incompleta
<br>que resultará em erro. Mas não se preocupe, pois não serão inseridos
<br>resigstros nas tabelas desde que não processe a fila.
<br><br>
<p>6. <a href="{!! asset('images/fila_modelo_final.xlsx') !!}" target="_blani">Clique arqui</a> para fazer o download de um modelo em excel para importação de Contrato!</p>