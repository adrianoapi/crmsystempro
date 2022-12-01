<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('cod_unidade', 100)->nullable(true);
            $table->string('cod_curso', 100)->nullable(true);
            $table->string('name', 100)->nullable(true);
            $table->string('responsavel', 100)->nullable(true);
            $table->string('cpf_cnpj', 30)->nullable(true);
            $table->string('ctr', 20)->nullable(true);
            $table->string('telefone', 20)->nullable(true);
            $table->string('telefone_com', 20)->nullable(true);
            $table->string('celular', 20)->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('cep', 9)->nullable(true);
            $table->string('endereco')->nullable(true);
            $table->integer('numero')->nullable(true);
            $table->string('complemento', 20)->nullable(true);
            $table->string('bairro')->nullable(true);
            $table->string('cidade')->nullable(true);
            $table->date('nascimento')->nullable(true);
            $table->string('estado', 2)->nullable(true);
            $table->boolean('negociado')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
