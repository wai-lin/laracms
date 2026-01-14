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
        Schema::create('page_template_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_template_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('label');
            $table->enum('type', ['text', 'textarea', 'richtext', 'image', 'boolean']);
            $table->json('options')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('required')->default(false);
            $table->timestamps();

            $table->unique(['page_template_id', 'name']);
            $table->index(['page_template_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_template_fields');
    }
};
