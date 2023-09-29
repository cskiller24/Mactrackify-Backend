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
            $table->foreignId('team_id')->constrained();
            $table->foreignId('team_leader_id')->constrained('users');
            $table->foreignId('brand_ambassador_id')->constrained('users');
            $table->string('customer_name');
            $table->string('customer_contact');
            $table->string('customer_age');
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
