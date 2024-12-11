<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->after('tool_id', function (Blueprint $table): void {
                $table->string('conditions_en')->nullable();
                $table->string('conditions_nl')->nullable();
                $table->text('links_with_other_tools_en')->nullable();
                $table->text('links_with_other_tools_nl')->nullable();
                $table->text('sla_url')->nullable();
                $table->string('privacy_contact')->nullable();
                $table->string('privacy_evaluation_url')->nullable();
                $table->string('security_evaluation_url')->nullable();
                $table->string('data_classification')->nullable();
                $table->string('how_to_login_en')->nullable();
                $table->string('how_to_login_nl')->nullable();
                $table->string('availability_en')->nullable();
                $table->string('availability_nl')->nullable();
                $table->string('licensing_en')->nullable();
                $table->string('licensing_nl')->nullable();
                $table->string('request_access_url')->nullable();
                $table->text('instructions_en')->nullable();
                $table->text('instructions_nl')->nullable();
                $table->string('instructions_manual_1_url')->nullable();
                $table->string('instructions_manual_2_url')->nullable();
                $table->string('instructions_manual_3_url')->nullable();
                $table->text('faq_en')->nullable();
                $table->text('faq_nl')->nullable();
                $table->text('examples_of_usage_en')->nullable();
                $table->text('examples_of_usage_nl')->nullable();
                $table->string('additional_info_heading_en')->nullable();
                $table->string('additional_info_heading_nl')->nullable();
                $table->text('additional_info_text_en')->nullable();
                $table->text('additional_info_text_nl')->nullable();
            });

            $table->dropColumn([
                'description_1_image_filename',
                'description_2_image_filename',
                'description_1_en',
                'description_1_nl',
                'description_2_en',
                'description_2_nl',
                'extra_information_title_en',
                'extra_information_title_nl',
                'extra_information_en',
                'extra_information_nl',
                'support_title_1_en',
                'support_title_1_nl',
                'support_email_1',
                'support_title_2_en',
                'support_title_2_nl',
                'support_email_2',
                'manual_title_1_en',
                'manual_title_1_nl',
                'manual_url_1',
                'manual_title_2_en',
                'manual_title_2_nl',
                'manual_url_2',
                'video_title_1_en',
                'video_title_1_nl',
                'video_url_1',
                'video_title_2_en',
                'video_title_2_nl',
                'video_url_2',
            ]);
        });
    }
};
