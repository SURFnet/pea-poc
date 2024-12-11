<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('custom_field_values', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('institute_tool_id');
            $table->unsignedBigInteger('custom_field_id');

            $table->text('value_en')->nullable();
            $table->text('value_nl')->nullable();

            $table->timestamps();

            $table->unique(['institute_tool_id', 'custom_field_id']);

            $table->foreign('institute_tool_id')
                ->references('id')->on('institute_tool')
                ->restrictOnDelete()
                ->cascadeOnDelete();

            $table->foreign('custom_field_id')
                ->references('id')->on('custom_fields')
                ->restrictOnDelete()
                ->cascadeOnDelete();
        });
    }
};
