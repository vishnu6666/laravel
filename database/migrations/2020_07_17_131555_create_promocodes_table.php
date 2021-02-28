<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->string('promoCode', 50)->unique();
            $table->enum('discountType', ['Percentage', 'Flat']);
            $table->float('discountAmount', 10, 2);
            $table->float('minTotalAmount', 10, 2)->nullable(true)->default(null);
            $table->float('maxDiscountAmount', 10, 2)->nullable(true)->default(null);
            $table->date('startDate')->nullable(true)->default(null);
            $table->date('endDate')->nullable(true)->default(null);
            $table->string('planName');
            $table->string('planId')->nullable(true)->comment('null => any')->default(null);
            $table->boolean('isActive')->comment('1=> Active, 0 => notActive')->default(1);
            $table->boolean('isApplyMultiTime')->comment('1=> ApplyMultiTime, 0 => notApplyMultiTime')->default(0);
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
        Schema::dropIfExists('promocodes');
    }
}
