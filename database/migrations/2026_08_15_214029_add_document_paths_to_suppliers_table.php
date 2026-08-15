<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('blank_cheque_path')->nullable()->after('bank_country');
            $table->string('gst_document_path')->nullable()->after('blank_cheque_path');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['blank_cheque_path', 'gst_document_path']);
        });
    }
};
