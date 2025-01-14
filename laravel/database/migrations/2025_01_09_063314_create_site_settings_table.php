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
    private string $tableName = 'site_settings';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->enum('type', [ 'text', 'image', 'setting' ])->default('setting');
            $table->enum('category', ['layout', 'system', 'user'])->default('system');
            $table->string('shortcode', 30)->unique();
            $table->text('content');
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
