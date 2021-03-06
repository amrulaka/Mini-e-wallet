<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalanceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balance_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_balance_id')->references('id')->on('user_balance');
            $table->integer('balance_bank_id')->references('id')->on('blance_bank');
            $table->integer('balance_before')->default(0);
            $table->integer('balance_after')->default(0);
            $table->string('activity')->nullable();
            $table->enum('type', ['credit', 'debit'])->nullable();
            $table->string('ip')->nullable();
            $table->string('location')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('author')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_balance_history');
    }
}
