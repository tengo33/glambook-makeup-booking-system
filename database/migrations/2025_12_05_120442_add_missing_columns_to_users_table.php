<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Name fields
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->after('id');
            }
            
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            
            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('last_name');
            }
            
            if (!Schema::hasColumn('users', 'suffix')) {
                $table->string('suffix', 10)->nullable()->after('middle_name');
            }
            
            // 2FA fields
            if (!Schema::hasColumn('users', 'two_factor_code')) {
                $table->string('two_factor_code', 6)->nullable()->after('password');
            }
            
            if (!Schema::hasColumn('users', 'two_factor_expires_at')) {
                $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
            }
            
            // Email verification
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('two_factor_expires_at');
            }
            
            // Status field
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('email_verified_at');
            }
            
            // Role field (if not exists)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('artist')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Don't drop columns in production, just comment out
            // $table->dropColumn(['first_name', 'last_name', 'middle_name', 'suffix', 
            //                     'two_factor_code', 'two_factor_expires_at', 
            //                     'email_verified_at', 'is_active', 'role']);
        });
    }
};