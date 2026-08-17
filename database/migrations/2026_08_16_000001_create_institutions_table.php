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
        if (! Schema::hasTable('institutions')) {
            Schema::create('institutions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('code', 15)->unique();
                $table->enum('type', ['company', 'institution', 'startup', 'community', 'other'])->default('company');
                $table->text('description')->nullable();
                $table->string('website')->nullable();
                $table->string('logo_path')->nullable();
                $table->boolean('is_verified')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
