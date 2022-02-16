<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolsTable extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table): void {
            $table->id();

            $table->string('name')->unique();
            $table->text('description_short');
            $table->string('image_filename')->nullable()->default(null);

            $table->mediumText('description_long_1')->nullable()->default(null);
            $table->string('description_1_image_filename')->nullable()->default(null);

            $table->mediumText('description_long_2')->nullable()->default(null);
            $table->string('description_2_image_filename')->nullable()->default(null);

            $table->json('supported_standards')->nullable()->default(null);
            $table->string('additional_standards')->nullable()->default(null);

            $table->json('authentication_methods')->nullable()->default(null);

            $table->json('stored_data')->nullable()->default(null);
            $table->string('other_stored_data')->nullable()->default(null);

            $table->boolean('european_data_storage')->default(false);
            $table->boolean('surf_standards_framework_agreed')->default(false);
            $table->boolean('has_processing_agreement')->default(false);

            $table->dateTime('published_at')->nullable()->default(null);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
}
