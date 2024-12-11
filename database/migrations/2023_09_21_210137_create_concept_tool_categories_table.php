<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('concept_tool_categories', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('concept_institute_tool_id');

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('concept_institute_tool_id')
                ->references('id')->on('concept_institute_tools')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }
};
