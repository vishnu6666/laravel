<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('userId')->nullable()->default(null); 
            $table->string('title')->default(null);          
            $table->longText('content')->nullable(); 
            $table->boolean('status')->default(1);
            $table->timestamp('createdAt')->default(null);
            $table->timestamp('updatedAt')->default(null);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_templates');
    }
}
