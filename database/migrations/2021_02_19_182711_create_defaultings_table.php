<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defaultings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            $table->string('student_name', 100)->nullable(true);
            $table->date('dt_inadimplencia')->nullable(true);
            $table->integer('m_parcelas')->nullable(true);
            $table->integer('m_parcela_pg')->nullable(true);
            $table->decimal('m_parcela_valor', 10, 2)->nullable(true);
            $table->integer('s_parcelas')->nullable(true);
            $table->integer('s_parcela_pg')->nullable(true);
            $table->decimal('s_parcela_valor', 10, 2)->nullable(true);
            $table->decimal('multa', 10, 2)->nullable(true);
            $table->boolean('negociado')->default(false);
            $table->boolean('boleto')->default(false);
            $table->enum('fase', ['segunda', 'terceira'])->default('segunda');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('defaultings');
    }
}
