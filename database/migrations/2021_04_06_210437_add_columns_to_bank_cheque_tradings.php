<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBankChequeTradings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_cheque_tradings', function (Blueprint $table) {
            $table->enum('pagamento', ['dinheiro', 'cartao', 'cheque', 'boleto', 'deposito'])->default('dinheiro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_cheque_tradings', function (Blueprint $table) {
            $table->dropColumn('pagamento');
        });
    }
}
