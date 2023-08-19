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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->constrained("users", "id")->cascadeOnDelete();

            $table->string("name", 15)->comment("Um nome breve e descritivo para facilitar a identificação do endereço");
            $table->string("street", 75);
            $table->integer("number");
            $table->string("zipcode", 20);
            $table->string("country", 2);
            $table->string("state", 2);
            $table->string("city", 50);
            $table->string("neighborhood", 50);
            $table->string("complementary", 100)->nullable();

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
        Schema::dropIfExists('addresses');
    }
};