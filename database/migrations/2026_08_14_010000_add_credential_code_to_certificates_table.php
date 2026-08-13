<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (! Schema::hasColumn('certificates', 'credential_code')) {
                    $table->string('credential_code')->nullable()->after('project_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (Schema::hasColumn('certificates', 'credential_code')) {
                    $table->dropColumn('credential_code');
                }
            });
        }
    }
};
