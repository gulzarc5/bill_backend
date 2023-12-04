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
        Schema::table('products', function (Blueprint $table) {
            $table->string('image',255)->nullable()->after('name');
            $table->string('height',255)->nullable()->after('image');
            $table->string('width',255)->nullable()->after('height');
            $table->bigInteger('glass_mm_id')->nullable()->after('width');
            $table->bigInteger('brand_id')->nullable()->after('glass_mm_id');
            $table->string('item_code',255)->nullable()->after('brand_id');
            $table->longText('description')->nullable()->after('item_code');
            $table->longText('location')->nullable()->after('description');
            $table->longText('Accesories')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
