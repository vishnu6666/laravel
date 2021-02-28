<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscription_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('subscriptionHistoryId')->nullable();
            $table->integer('userId');
            $table->integer('planId');
            $table->string('planName', 50)->nullable();
            $table->string('packageName')->nullable();
            $table->double('planAmount', 8, 2)->default(00.00);
            $table->enum('planType', ['Weekly', 'Monthly']);
            $table->integer('subscriptionValidity')->nullable();
            $table->date('subscriptionExpiryDate')->nullable();
            $table->string('paymentType')->nullable();
            $table->double('amount', 8, 2)->default(00.00);
            $table->boolean('isTrial')->comment('1=> Triel, 0 => notTriel')->default(1);
            $table->boolean('isAutoPay')->comment('1=> AutoPay, 0 => not AutoPay')->default(0);
            $table->boolean('isCancel')->comment('1=> Cancel, 0 => not Cancel')->default(0);
            $table->integer('promocodeId')->nullable();
            $table->integer('referralcodeId')->nullable();
            $table->string('appliedPromocode')->nullable();
            $table->double('discountAmount', 8, 2)->default(00.00);
            $table->enum('discountType', ['null','referral','promocode'])->nullable();
            $table->enum('paymentStatus', ['success', 'failed']);
            $table->string('transactionId')->nullable();
            $table->longText('paymentResponse')->nullable();
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
        Schema::table('user_subscription_histories', function (Blueprint $table) {
            //
        });
    }
}
