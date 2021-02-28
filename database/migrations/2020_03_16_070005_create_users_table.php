<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('userType', array('SuperAdmin','Admin', 'User'))->default(null);
            $table->string('name')->nullable();
            $table->string('email', 63);
            $table->string('stripeId')->nullable();
            $table->string('cardToken')->nullable();
            $table->string('mobileNumber', 10)->nullable();
            $table->string('referralCode', 9)->nullable();
            $table->string('password', 255);
            $table->string('showPassword', 255);
            $table->string('socialId')->nullable();
            $table->string('socialType')->nullable();

            $table->string('otp', 4)->nullable();
            $table->string('profilePic')->nullable();
            $table->string('deviceToken')->nullable();
            $table->string('fcmToken', 255)->nullable();
            $table->string('packageId')->nullable();
            
            $table->string('deviceType', 255)->nullable();
            $table->boolean('isEmailVerified')->default(0);
            $table->boolean('isMobileVerified')->default(0);
            $table->boolean('isActive')->default(0);
            $table->boolean('termsAndConditions')->default(0);

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
        Schema::dropIfExists('users');
    }
}
