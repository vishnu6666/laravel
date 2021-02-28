<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('senderId')->comment('sender userId')->default(null);
            $table->integer('userId')->default(null);
            $table->integer('notificationType')->comment('1 = game , 2 = package , 3 = offer 4=sendByAdmin')->default(null);
            $table->string('module')->default(null);
            $table->string('title')->default(null);
            $table->text('content')->default(null); 
            $table->string('media')->default(null);
            $table->string('packageName')->default(null); 
            $table->boolean('isRead')->comment('after action done set 1')->default(0);

            $table->string('action')->default(null); 
            $table->text('data'); 
            $table->string('positiveText')->comment('positive button text')->default(null); 
            $table->string('negativeText')->comment('negative button text')->default(null); 
            $table->integer('isActionDone')->comment('after action done set 1')->default(0);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
