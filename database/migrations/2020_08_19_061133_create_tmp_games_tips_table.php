<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmpGamesTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_games_tips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('spreadsheetsId')->comment('Foreign key from spreadsheets');
            $table->integer('userId')->comment('Result import by');

            $table->date('date')->nullable();
            $table->integer('gameId')->comment('Foreign key from games table');
            $table->string('competition')->nullable();
            $table->integer('track')->default(0);
            $table->text('tips')->nullable();
            $table->double('odds', 8, 2)->default(00.00);
            $table->string('units')->nullable();
            $table->enum('IsWin', array('win','loss','pending'))->default('pending');
            $table->string('profitLosForTips')->nullable();
            $table->string('weeklyProfitLos')->nullable();
            $table->string('weeklyPot')->nullable();
            $table->string('monthlyProfitLos')->nullable();
            $table->string('monthlyPot')->nullable();
            $table->string('allTimeProfitLos')->nullable();
            $table->string('allTimePot')->nullable();
            $table->string('tipsImage')->nullable();
            $table->text('text')->nullable();
            $table->boolean('isResultUpdate')->comment('1=> result update , 0 => not update')->default(0);

            $table->string('profitLosForDay')->nullable();
            $table->string('dailyPot')->nullable();
            $table->string('profitLossCumulative')->nullable();
            $table->string('pot')->nullable();
            $table->string('profitOneUnit')->nullable();
            $table->string('profitTwoUnit')->nullable();
            $table->string('profitThreeUnit')->nullable();
            $table->double('percentage', 8, 2)->default(80.00);

            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent();
            $table->timestamp('deletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tmp_games_tips', function (Blueprint $table) {
            //
        });
    }
}
