<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumnsToGraphics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('graphics', function (Blueprint $table) {
            $table->enum('tipo', ['grafica_1', 'grafica_2'])->default('grafica_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('graphics', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}
