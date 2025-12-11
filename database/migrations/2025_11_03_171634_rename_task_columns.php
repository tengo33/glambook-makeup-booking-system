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
            $table->renameColumn('title', 'client_name');
            $table->renameColumn('description', 'service_details');
            $table->renameColumn('scheduled_at', 'appointment_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('client_name', 'title');
            $table->renameColumn('service_details', 'description');
            $table->renameColumn('appointment_at', 'scheduled_at');
        });
    }
};
