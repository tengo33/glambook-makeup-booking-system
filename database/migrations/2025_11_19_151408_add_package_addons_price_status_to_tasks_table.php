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
        Schema::table('tasks', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('tasks', 'phone')) {
                $table->string('phone')->nullable()->after('client_name');
            }
            
            if (!Schema::hasColumn('tasks', 'package')) {
                $table->string('package')->nullable()->after('service_details');
            }
            
            if (!Schema::hasColumn('tasks', 'addons')) {
                $table->text('addons')->nullable()->after('package');
            }
            
            if (!Schema::hasColumn('tasks', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('addons');
            }
            
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status')->default('scheduled')->after('price');
            }
            
            if (!Schema::hasColumn('tasks', 'additional_notes')) {
                $table->text('additional_notes')->nullable()->after('appointment_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['phone', 'package', 'addons', 'price', 'status', 'additional_notes']);
        });
    }
};