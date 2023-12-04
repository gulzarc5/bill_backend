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
        Schema::create('price_maps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('material_id')->nullable();
            $table->bigInteger('glass_mm_id')->nullable();
            $table->double('price',10,2)->default(0.00)->comment('quotation');
            $table->char('status',1)->default(1)->comment('1:enabled,2:disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_maps');
    }
};
