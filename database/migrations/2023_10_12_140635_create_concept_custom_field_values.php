<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('concept_custom_field_values', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('concept_institute_tool_id');
            $table->unsignedBigInteger('custom_field_id');

            $table->mediumText('value_en')->nullable();
            $table->mediumText('value_nl')->nullable();

            $table->timestamps();

            $table->unique(['concept_institute_tool_id', 'custom_field_id'], 'institute_tool_custom_field_unique');

            $table->foreign('concept_institute_tool_id')
                ->references('id')->on('concept_institute_tools')
                ->restrictOnDelete()
                ->cascadeOnDelete();

            $table->foreign('custom_field_id')
                ->references('id')->on('custom_fields')
                ->restrictOnDelete()
                ->cascadeOnDelete();
        });
    }
};
