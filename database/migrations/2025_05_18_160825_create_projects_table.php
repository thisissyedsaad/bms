<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('title')->nullable();
            $table->decimal('conversion_rate', 12, 6)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->decimal('received_amount', 12, 2)->nullable();
            $table->string('currency')->nullable();
            $table->enum('project_type', ['fixed', 'hourly'])->default('fixed');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable();         
            $table->text('details')->nullable();
            $table->string('assigned_to')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('platform', ['direct', 'upwork', 'reference'])->default('direct');
            $table->enum('status', ['ongoing', 'completed', 'hold', 'cancelled'])->default('ongoing');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
