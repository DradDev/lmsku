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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'institution_id')) {
                    $table->foreignId('institution_id')
                        ->nullable()
                        ->after('role')
                        ->constrained('institutions')
                        ->nullOnDelete();
                }

                if (! Schema::hasColumn('users', 'institution_type')) {
                    $table->enum('institution_type', ['company', 'individual'])
                        ->nullable()
                        ->after('institution_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'institution_id')) {
                    $table->dropForeign(['institution_id']);
                    $table->dropColumn('institution_id');
                }

                if (Schema::hasColumn('users', 'institution_type')) {
                    $table->dropColumn('institution_type');
                }
            });
        }
    }
};
