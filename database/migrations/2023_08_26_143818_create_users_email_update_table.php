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
        Schema::create('users_email_update', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->constrained("users", "id")->cascadeOnDelete();
            $table->string("new_email")->unique();
            $table->string("token", 50);

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
        Schema::dropIfExists('users_email_update');
    }
};