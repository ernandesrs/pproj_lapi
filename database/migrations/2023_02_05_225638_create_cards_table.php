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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId("user_id")->constrained("users", "id")->cascadeOnDelete();
            // $table->unsignedBigInteger('user_id');
            $table->string('hash', 255);
            $table->string('brand');
            $table->string('holder_name');
            $table->string('last_digits');
            $table->string('expiration_date', 5);

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
        Schema::dropIfExists('cards');
    }
};