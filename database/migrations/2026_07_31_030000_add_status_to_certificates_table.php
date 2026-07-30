<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (! Schema::hasColumn('certificates', 'status')) {
                    $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('is_verified');
                }
                if (! Schema::hasColumn('certificates', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('status');
                }
                if (! Schema::hasColumn('certificates', 'verified_by')) {
                    $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
                }
            });

            // Backfill: Set existing verified certificates to status='verified'
            DB::table('certificates')->where('is_verified', true)->update([
                'status' => 'verified',
                'verified_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (Schema::hasColumn('certificates', 'verified_by')) {
                    $table->dropForeign(['verified_by']);
                    $table->dropColumn('verified_by');
                }
                if (Schema::hasColumn('certificates', 'verified_at')) {
                    $table->dropColumn('verified_at');
                }
                if (Schema::hasColumn('certificates', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
};
