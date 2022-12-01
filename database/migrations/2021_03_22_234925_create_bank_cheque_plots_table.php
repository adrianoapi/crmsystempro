<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankChequePlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_cheque_plots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bank_cheque_id');
            $table->date('vencimento')->nullable(true);
            $table->string('banco', 10)->nullable(true);
            $table->string('agencia', 10)->nullable(true);
            $table->string('conta', 20)->nullable(true);
            $table->string('cheque', 20)->nullable(true);
            $table->decimal('valor', 10, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('bank_cheque_id')->references('id')->on('bank_cheques')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_cheque_plots');
    }
}
