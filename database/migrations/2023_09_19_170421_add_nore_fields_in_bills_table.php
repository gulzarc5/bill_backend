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
        Schema::table('bills', function (Blueprint $table) {
            $table->string('irn_no',255)->nullable()->after('id');
            $table->string('ack_no',255)->nullable()->after('irn_no');
            $table->string('doc_no',255)->nullable()->after('ack_no');
            $table->string('client_igst',255)->nullable()->after('client_id');
            $table->string('supply_type',255)->nullable()->after('client_id');
            $table->string('e_way_bill_no',255)->nullable()->after('doc_no');
            $table->string('e_way_bill_rate',255)->nullable()->after('e_way_bill_no');
            $table->string('e_way_bill_valid_date',255)->nullable()->after('e_way_bill_rate');
            $table->double('discount',10,2)->default(0.00)->after('sgst');
            $table->double('cash_recieved',10,2)->default(0.00)->after('discount');
            $table->double('outstanding_amount',10,2)->default(0.00)->after('cash_recieved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            //
        });
    }
};
