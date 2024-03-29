<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('tool_categories', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('tool_id');

            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->foreign('tool_id')
                ->references('id')->on('tools')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_categories');
    }
}
