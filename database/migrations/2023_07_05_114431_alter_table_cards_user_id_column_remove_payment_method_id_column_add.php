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
        Schema::table('cards', function (Blueprint $table) {

            $table->dropConstrainedForeignId("user_id");
            $table->foreignId("payment_method_id")->after("id")->constrained("payment_methods", "id")->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {

            $table->dropConstrainedForeignId("payment_method");
            $table->foreignId("user_id")->constrained("users", "id")->cascadeOnDelete();

        });
    }
};