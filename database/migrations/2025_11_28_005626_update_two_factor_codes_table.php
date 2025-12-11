<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTwoFactorCodesTable extends Migration
{
    public function up()
    {
        Schema::table('two_factor_codes', function (Blueprint $table) {
            // Check if column exists before adding
            if (!Schema::hasColumn('two_factor_codes', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('two_factor_codes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}