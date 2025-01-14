<?php
use Illuminate\Database\ {
    Migrations\Migration,
    Schema\Blueprint
};
use Illuminate\Support\Facades\ {
    DB,
    Schema
};

return new class extends Migration
{
    private string $tableName = 'roles';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['admin', 'librarian', 'member']);
            $table->string('description', 255);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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