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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('position');
            $table->text('description');
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('location');
            $table->json('tech');
            $table->json('achievements');
            $table->boolean('is_current')->default(false);
            $table->string('company_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
