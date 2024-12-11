<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('concept_institute_tools', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('institute_tool_id');

            $table->unsignedBigInteger('alternative_tool_id')->nullable();

            $table->mediumText('description_1_en')->nullable();
            $table->mediumText('description_1_nl')->nullable();
            $table->string('description_1_image_filename')->nullable()->default(null);

            $table->mediumText('description_2_en')->nullable();
            $table->mediumText('description_2_nl')->nullable();
            $table->string('description_2_image_filename')->nullable()->default(null);

            $table->mediumText('extra_information_en')->nullable();
            $table->mediumText('extra_information_nl')->nullable();
            $table->string('extra_information_title_en')->nullable();
            $table->string('extra_information_title_nl')->nullable();

            $table->string('support_email_1')->nullable();
            $table->string('support_title_1_en')->nullable();
            $table->string('support_title_1_nl')->nullable();

            $table->string('support_email_2')->nullable();
            $table->string('support_title_2_en')->nullable();
            $table->string('support_title_2_nl')->nullable();

            $table->string('manual_url_1')->nullable();
            $table->string('manual_title_1_en')->nullable();
            $table->string('manual_title_1_nl')->nullable();

            $table->string('manual_url_2')->nullable();
            $table->string('manual_title_2_en')->nullable();
            $table->string('manual_title_2_nl')->nullable();

            $table->string('video_url_1')->nullable();
            $table->string('video_title_1_en')->nullable();
            $table->string('video_title_1_nl')->nullable();

            $table->string('video_url_2')->nullable();
            $table->string('video_title_2_en')->nullable();
            $table->string('video_title_2_nl')->nullable();

            $table->string('status', 25)->nullable();

            $table->text('why_unfit_en')->nullable();
            $table->text('why_unfit_nl')->nullable();

            $table->timestamps();

            $table->foreign('institute_tool_id')
                ->references('id')->on('institute_tool')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('alternative_tool_id')
                ->references('id')->on('tools')
                ->restrictOnUpdate()
                ->nullOnDelete();
        });
    }
};
