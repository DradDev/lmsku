<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('material_id')->index();
            $table->unsignedBigInteger('course_offering_id')->nullable()->index();
            $table->timestamps();

            $table->unique(['user_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_materials');
    }
};
