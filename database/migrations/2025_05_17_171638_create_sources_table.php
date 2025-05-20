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
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('commission_type', ['on_cost', 'on_profit', 'fixed'])->default('on_cost');
            $table->decimal('commission_value', 5, 2)->nullable();   // e.g. 10.00 (10%)
            $table->boolean('is_platform')->default(false);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('sources');
    }
};
