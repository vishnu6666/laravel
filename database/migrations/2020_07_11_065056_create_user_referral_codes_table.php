<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReferralCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_referral_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('referralFrom');
            $table->integer('referralTo');
            $table->string('referralCode');
            $table->boolean('isApplied')->comment('1=> Applied, 0 => notApplied')->default(1);
            $table->boolean('isSubscribed')->comment('1=> Subscribed, 0 => NotSubscribed')->default(1);
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
        Schema::dropIfExists('user_referral_codes');
    }
}
