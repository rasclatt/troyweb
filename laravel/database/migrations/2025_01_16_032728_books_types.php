<?php

use Illuminate\Database\ {
    Migrations\Migration,
    Schema\Blueprint
};
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $tableName = 'books_types';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('shortcode', 50);
            $table->string('title', 50);
            $table->string('description', 255);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
