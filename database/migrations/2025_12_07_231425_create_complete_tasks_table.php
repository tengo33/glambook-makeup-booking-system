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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->datetime('scheduled_at')->nullable();
        $table->boolean('is_done')->default(false);
        
        // Client fields
        $table->string('client_name')->nullable();
        $table->string('first_name')->nullable();
        $table->string('middle_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('suffix', 10)->nullable();
        $table->string('phone')->nullable();
        
        // Service fields
        $table->string('service_type')->nullable();
        $table->string('selected_package')->nullable();
        $table->text('selected_addons')->nullable();
        $table->decimal('total_price', 10, 2)->nullable();
        $table->string('status')->default('pending');
        
        // Appointment fields
        $table->date('appointment_date')->nullable();
        $table->time('appointment_time')->nullable();
        $table->text('additional_notes')->nullable();
        
        // Location fields
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('zip_code')->nullable();
        $table->string('country')->nullable();
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->text('location_notes')->nullable();
        
        // ML fields
        $table->integer('predicted_duration')->nullable();
        $table->decimal('predicted_no_show_score', 5, 4)->nullable();
        
        // User relationship
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('tasks');
}
};
