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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('dcrypt_password',255)->nullable()->after('password');
            $table->char('type',1)->default(1)->comment('1:admin,2:sub-admin')->after('api_token');
            $table->char('status',1)->default(1)->comment('1:enabled,2:disabled')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
};
