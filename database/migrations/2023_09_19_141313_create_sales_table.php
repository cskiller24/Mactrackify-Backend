<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('team_name');
            $table->string('team_leader_name');
            $table->string('brand_ambassador_name');
            $table->string('customer_name');
            $table->string('customer_contact');
            $table->string('product');
            $table->integer('product_quantity');
            $table->string('promo');
            $table->integer('promo_quantity');
            $table->text('signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
