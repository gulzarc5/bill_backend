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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->nullable();
            $table->double('total_sq_feet',10,2)->default(0.00);
            $table->double('amount',10,2)->default(0.00);
            $table->double('cgst',10,2)->default(0.00);
            $table->double('sgst',10,2)->default(0.00);
            $table->double('total_amount',10,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
