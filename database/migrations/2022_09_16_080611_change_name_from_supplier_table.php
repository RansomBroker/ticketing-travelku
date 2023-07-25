<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameFromSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->integer('produsen_id');
            $table->integer('seat');
            $table->longText('schedule')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn('produsen_id');
            $table->dropColumn('seat');
            $table->dateTime('schedule')->change();
        });
    }
}
