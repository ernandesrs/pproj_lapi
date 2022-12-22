<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('gender', 1)->default('n');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('verification_token', 50)->nullable()->default(null);
            $table->string('photo')->nullable()->default(null);
            $table->integer('level')->default(0);
            $table->fullText(["first_name", "last_name", "username", "email"]);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
