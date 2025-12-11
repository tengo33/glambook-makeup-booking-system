<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        // Only add time_buffer after predicted_duration
        if (!Schema::hasColumn('tasks', 'time_buffer')) {
            $table->integer('time_buffer')->default(60)->after('predicted_duration');
        }
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        if (Schema::hasColumn('tasks', 'time_buffer')) {
            $table->dropColumn('time_buffer');
        }
    });
}
};
