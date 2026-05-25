<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable()->after('user_id')->constrained('tables')->nullOnDelete();

            $table->decimal('tax', 12, 2)->default(0)->after('total');
            $table->decimal('service_charge', 12, 2)->default(0)->after('tax');
            $table->decimal('grand_total', 12, 2)->default(0)->after('service_charge');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])->default('pending')->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropColumn(['table_id', 'tax', 'service_charge', 'grand_total']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['completed', 'cancelled'])->default('completed')->after('notes');
        });
    }
};
