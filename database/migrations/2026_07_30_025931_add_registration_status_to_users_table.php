<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('registration_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('role');
        });

        // User yang sudah ada sebelum fitur ini dibuat dianggap sudah approved,
        // supaya akun lama (termasuk admin) tidak ikut ter-lock.
        DB::table('users')->update(['registration_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('registration_status');
        });
    }
};