<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGameHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_game_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userId');
            $table->integer('subscriptionHistoriesId');
            $table->integer('gameId');
            $table->boolean('isTrial')->comment('1=> Trial, 0 => notTrial')->default(1);
            $table->boolean('isSubscribed')->comment('1=> Subscribed, 0 => notSubscribed')->default(0);
            $table->boolean('isKeepNotification')->comment('1 = notification on , 0 = notification of')->default(1);
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_game_history', function (Blueprint $table) {
            //
        });
    }
}
