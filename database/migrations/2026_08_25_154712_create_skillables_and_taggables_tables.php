<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the new polymorphic tables
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->morphs('taggable'); // Creates taggable_type and taggable_id
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->timestamps();
            
            // Prevent duplicate tags on the same entity
            $table->unique(['tag_id', 'taggable_id', 'taggable_type'], 'taggable_unique_index');
        });

        Schema::create('skillables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->morphs('skillable'); // Creates skillable_type and skillable_id
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->boolean('is_main')->default(false);
            $table->timestamps();
            
            // Prevent duplicate skills on the same entity
            $table->unique(['skill_id', 'skillable_id', 'skillable_type'], 'skillable_unique_index');
        });

        // 2. Safe Data Transfer (Opsi A: Penyelamatan Data)
        $this->transferData();

        // 3. Drop the old redundant tables
        Schema::dropIfExists('project_tags');
        Schema::dropIfExists('master_course_tags');
        Schema::dropIfExists('project_skills');
        Schema::dropIfExists('master_course_skills');
        Schema::dropIfExists('question_skills');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Recreate old tables (without data transfer for brevity, normally we'd move it back if strictly required, 
        // but typically down() for complex refactors just recreates the schema)
        
        Schema::create('project_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->timestamps();
        });

        Schema::create('master_course_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_course_id')->constrained('master_courses')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('project_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->boolean('is_main')->default(false);
            $table->timestamps();
            $table->unique(['project_id', 'skill_id']);
        });

        Schema::create('master_course_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_course_id')->constrained('master_courses')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        Schema::create('question_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->timestamps();
        });

        // 2. Drop the new tables
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('skillables');
    }

    /**
     * Helper method to safely transfer data
     */
    private function transferData(): void
    {
        // --- TAGGABLES ---
        
        if (Schema::hasTable('project_tags')) {
            DB::table('project_tags')->orderBy('id')->chunk(100, function ($records) {
                $inserts = [];
                foreach ($records as $record) {
                    $inserts[] = [
                        'tag_id' => $record->tag_id,
                        'taggable_type' => 'App\Models\Project',
                        'taggable_id' => $record->project_id,
                        'weight' => $record->weight ?? 1.00,
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => $record->updated_at ?? now(),
                    ];
                }
                DB::table('taggables')->insertOrIgnore($inserts);
            });
        }

        if (Schema::hasTable('master_course_tags')) {
            DB::table('master_course_tags')->orderBy('id')->chunk(100, function ($records) {
                $inserts = [];
                foreach ($records as $record) {
                    $inserts[] = [
                        'tag_id' => $record->tag_id,
                        'taggable_type' => 'App\Models\MasterCourse',
                        'taggable_id' => $record->master_course_id,
                        'weight' => 1.00,
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => $record->updated_at ?? now(),
                    ];
                }
                DB::table('taggables')->insertOrIgnore($inserts);
            });
        }

        // --- SKILLABLES ---

        if (Schema::hasTable('project_skills')) {
            DB::table('project_skills')->orderBy('id')->chunk(100, function ($records) {
                $inserts = [];
                foreach ($records as $record) {
                    $inserts[] = [
                        'skill_id' => $record->skill_id,
                        'skillable_type' => 'App\Models\Project',
                        'skillable_id' => $record->project_id,
                        'weight' => $record->weight ?? 1.00,
                        'is_main' => $record->is_main ?? false,
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => $record->updated_at ?? now(),
                    ];
                }
                DB::table('skillables')->insertOrIgnore($inserts);
            });
        }

        if (Schema::hasTable('master_course_skills')) {
            DB::table('master_course_skills')->orderBy('id')->chunk(100, function ($records) {
                $inserts = [];
                foreach ($records as $record) {
                    $inserts[] = [
                        'skill_id' => $record->skill_id,
                        'skillable_type' => 'App\Models\MasterCourse',
                        'skillable_id' => $record->master_course_id,
                        'weight' => 1.00,
                        'is_main' => $record->is_main ?? false,
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => $record->updated_at ?? now(),
                    ];
                }
                DB::table('skillables')->insertOrIgnore($inserts);
            });
        }

        if (Schema::hasTable('question_skills')) {
            DB::table('question_skills')->orderBy('id')->chunk(100, function ($records) {
                $inserts = [];
                foreach ($records as $record) {
                    $inserts[] = [
                        'skill_id' => $record->skill_id,
                        'skillable_type' => 'App\Models\Question',
                        'skillable_id' => $record->question_id,
                        'weight' => $record->weight ?? 1.00,
                        'is_main' => false,
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => $record->updated_at ?? now(),
                    ];
                }
                DB::table('skillables')->insertOrIgnore($inserts);
            });
        }
    }
};
