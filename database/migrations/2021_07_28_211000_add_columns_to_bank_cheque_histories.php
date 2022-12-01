<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBankChequeHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_cheque_histories', function (Blueprint $table) {
            $table->enum('schedule', ['open', 'close', 'none'])->default('none');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_cheque_histories', function (Blueprint $table) {
            $table->dropColumn('schedule');
        });
    }
}
