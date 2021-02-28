<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPackagesSubscriptionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_packages_subscription_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userId');
            $table->integer('subscriptionHistoriesId')->comment('Foreign key from subscription_histories');
            $table->integer('sportPackageId')->comment('Foreign key from packages  table');
            $table->boolean('isTrial')->comment('1= Trial, 0 = notTrial')->default(1);
            $table->boolean('isNew')->comment('1= new subscribe, 0 = subscribed')->default(1);
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
        Schema::dropIfExists('user_packages_subscription_histories');
    }
}
