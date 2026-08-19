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
        if (Schema::hasTable('master_courses') && Schema::hasColumn('master_courses', 'category_id')) {
            Schema::table('master_courses', function (Blueprint $table) {
                try {
                    $table->dropForeign(['category_id']);
                } catch (\Throwable $e) {
                    // Ignore if foreign key constraint does not exist
                }
                $table->dropColumn('category_id');
            });
        }

        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'category_id')) {
            Schema::table('projects', function (Blueprint $table) {
                try {
                    $table->dropForeign(['category_id']);
                } catch (\Throwable $e) {
                    // Ignore if foreign key constraint does not exist
                }
                $table->dropColumn('category_id');
            });
        }

        Schema::dropIfExists('categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('master_courses') && !Schema::hasColumn('master_courses', 'category_id')) {
            Schema::table('master_courses', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            });
        }

        if (Schema::hasTable('projects') && !Schema::hasColumn('projects', 'category_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            });
        }
    }
};
