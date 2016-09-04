<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthdatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthdatas', function (Blueprint $table) {
            $table->increments('id');
            $table->char('date', 7);
            $table->tinyInteger('month_id');
            $table->tinyInteger('year');
            $table->smallInteger('revenue')->nullable();
            $table->smallInteger('cost')->nullable();
            $table->smallInteger('profit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('monthdatas');
    }
}
