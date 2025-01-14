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
    private string $tableName = 'books';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('author', 100);
            $table->string('description', 255);
            $table->string('cover_image');
            $table->string('publisher', 150);
            $table->string('category');
            $table->string('isbn');
            $table->integer('page_count');
            $table->date('publication_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
            $table->unique('isbn');
            $table->index(['title', 'deleted_at']);
            $table->index(['description', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};