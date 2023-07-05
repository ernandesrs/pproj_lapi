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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->constrained("users", "id")->cascadeOnDelete();
            $table->foreignId("preferred_card_id")->nullable()->constrained("cards", "id")->nullOnDelete();
            $table->string("method_preferred", 12)->default(\App\Models\Payment\PaymentMethod::PAY_METHOD_UNDEFINED);

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
        Schema::dropIfExists('payment_methods');
    }
};