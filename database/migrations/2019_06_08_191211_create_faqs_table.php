<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.5.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question')->nullable();
            $table->text('title')->nullable();
            $table->text('answer')->nullable();
            $table->tinyInteger('isActive')->nullable()->default(1);
            $table->timestamp('createdAt')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updatedAt')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}
