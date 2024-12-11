<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('concept_tool_features', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('concept_tool_id');

            $table->foreign('feature_id')
                ->references('id')->on('features')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('concept_tool_id')
                ->references('id')->on('concept_tools')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }
};
