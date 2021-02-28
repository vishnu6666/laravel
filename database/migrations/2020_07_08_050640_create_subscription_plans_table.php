<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('planName', 50);
            $table->string('planSubTitle', 50);
            $table->integer('planFullPackages');
            $table->string('planFullPackagesTitle');
            $table->integer('planDuration')->comment('Denoted triel plan expire days.');
            $table->double('planWeeklyPrice', 8, 2);
            $table->double('planMonthlyPrice', 8, 2);
            $table->boolean('isActive')->comment('1=> Active, 0 => Inactive')->default(1);
            $table->boolean('isTrial')->comment('1=> Trial, 0 => Paid')->default(0);
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
        Schema::dropIfExists('subscription_plans');
    }
}
