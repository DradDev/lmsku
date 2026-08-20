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
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'parent_id')) {
                // Drop foreign key if exists
                try {
                    $table->dropForeign(['parent_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key was not named default
                }
                $table->dropColumn('parent_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('description')->constrained('skills')->nullOnDelete();
        });
    }
};
