<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('concept_tools', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tool_id');

            $table->string('name_en')->unique();
            $table->string('name_nl')->nullable()->unique();
            $table->text('description_short_en');
            $table->text('description_short_nl')->nullable();
            $table->string('image_filename')->nullable()->default(null);

            $table->mediumText('description_long_1_en')->nullable()->default(null);
            $table->mediumText('description_long_1_nl')->nullable()->default(null);
            $table->string('description_1_image_filename')->nullable()->default(null);

            $table->mediumText('description_long_2_en')->nullable()->default(null);
            $table->mediumText('description_long_2_nl')->nullable()->default(null);
            $table->string('description_2_image_filename')->nullable()->default(null);

            $table->string('info_url')->nullable()->default(null);

            $table->json('supported_standards')->nullable()->default(null);
            $table->string('additional_standards')->nullable()->default(null);

            $table->json('authentication_methods')->nullable()->default(null);

            $table->json('stored_data')->nullable()->default(null);
            $table->string('other_stored_data')->nullable()->default(null);

            $table->boolean('european_data_storage')->default(false);
            $table->boolean('surf_standards_framework_agreed')->default(false);
            $table->boolean('has_processing_agreement')->default(false);

            $table->timestamps();

            $table->foreign('tool_id')
                ->references('id')->on('tools')
                ->restrictOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
