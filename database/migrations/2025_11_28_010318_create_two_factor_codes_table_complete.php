<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoFactorCodesTableComplete extends Migration
{
    public function up()
    {
        Schema::create('two_factor_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code', 6); // The 6-digit verification code
            $table->boolean('used')->default(false); // Whether the code has been used
            $table->timestamp('expires_at'); // When the code expires
            $table->timestamps(); // created_at and updated_at
            
            // Add index for faster lookups
            $table->index(['user_id', 'code', 'used']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('two_factor_codes');
    }
}