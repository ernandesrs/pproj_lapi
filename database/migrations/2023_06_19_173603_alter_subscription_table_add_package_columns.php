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
        Schema::table("subscriptions", function (Blueprint $table) {
            $table->unsignedBigInteger("package_id")->after("id");
            $table->text("package_metadata")->after("package_id");

            $table->foreign("package_id")->references("id")->on("packages")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("subscriptions", function (Blueprint $table) {
            $table->dropColumn("package_id");
            $table->dropColumn("package_metadata");
        });
    }
};