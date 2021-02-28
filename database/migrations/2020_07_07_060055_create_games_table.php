<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gameName', 50);
            $table->string('gameFullName', 100);
            $table->string('gameImage')->nullable();
            $table->date('launchDate')->nullable();
            $table->integer('gameTotalTips')->default(0);
            $table->integer('gameTodayTips')->default(0);
            $table->integer('totalUsersCount')->default(0);
            $table->boolean('isActive')->comment('1=> Active, 0 => Inactive')->default(1);
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
        Schema::dropIfExists('games');
    }
}
