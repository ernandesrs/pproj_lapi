<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // $table->unsignedBigInteger('user_id');
            $table->foreignId("user_id")->constrained("users", "id")->cascadeOnDelete();

            $table->string('gateway');
            $table->string('transaction_id');
            $table->date('starts_in');
            $table->date('ends_in');
            $table->string('type', 8)->default('new');
            $table->string('status', 8)->default('pending');

            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

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
        Schema::dropIfExists('subscriptions');
    }
};