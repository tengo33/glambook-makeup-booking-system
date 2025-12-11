<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Only add columns if they DON'T already exist
            if (!Schema::hasColumn('tasks', 'client_name')) {
                $table->string('client_name')->after('id');
            }

            if (!Schema::hasColumn('tasks', 'service_details')) {
                $table->text('service_details')->nullable()->after('client_name');
            }

            if (!Schema::hasColumn('tasks', 'appointment_at')) {
                $table->dateTime('appointment_at')->after('service_details');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('tasks', 'client_name')) {
                $table->dropColumn('client_name');
            }

            if (Schema::hasColumn('tasks', 'service_details')) {
                $table->dropColumn('service_details');
            }

            if (Schema::hasColumn('tasks', 'appointment_at')) {
                $table->dropColumn('appointment_at');
            }

            // Optionally restore old columns
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('scheduled_at')->nullable();
        });
    }
};
