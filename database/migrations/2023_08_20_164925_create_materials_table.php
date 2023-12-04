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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->nullable();
            $table->double('amount',10,2)->default(0.00)->comment('per_sq_feet');
            $table->double('milli_amount',10,2)->default(0.00)->comment('per_milli_sq_feet');
            $table->char('status',1)->default(1)->comment('1:enabled,2:disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
