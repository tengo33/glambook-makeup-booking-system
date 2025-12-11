<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'actual_duration')) {
                $table->integer('actual_duration')->nullable()->after('appointment_at')->comment('Actual duration in minutes');
            }
            
            if (!Schema::hasColumn('tasks', 'predicted_duration')) {
                $table->integer('predicted_duration')->nullable()->after('actual_duration')->comment('ML predicted duration in minutes');
            }
            
            if (!Schema::hasColumn('tasks', 'predicted_no_show_score')) {
                $table->decimal('predicted_no_show_score', 3, 2)->nullable()->after('predicted_duration')->comment('ML no-show probability (0-1)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            Schema::dropColumnIfExists('tasks', ['actual_duration', 'predicted_duration', 'predicted_no_show_score']);
        });
    }
};