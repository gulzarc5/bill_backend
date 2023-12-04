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
        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('product_name',255)->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('category_name',255)->nullable();
            $table->bigInteger('glass_mm_id')->nullable();
            $table->string('glass_mm',255)->nullable();
            $table->bigInteger('material_id')->nullable();
            $table->string('material_name',255)->nullable();
            $table->bigInteger('height')->nullable();
            $table->bigInteger('width')->nullable();         
            $table->double('per_sqfeet_amount',10,2)->default(0.00);
            $table->bigInteger('quantity')->nullable();
            $table->double('total_sq_feet',10,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_details');
    }
};
