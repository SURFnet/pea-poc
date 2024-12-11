<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('concept_tools', function (Blueprint $table): void {
            $table->renameColumn('name_en', 'name');
            $table->renameIndex('concept_tools_name_en_unique', 'concept_tools_name_unique');
            $table->dropColumn('name_nl');

            $table->after('name_en', function (Blueprint $table): void {
                $table->string('supplier')->nullable();
                $table->string('supplier_url')->nullable();
                $table->text('addons_en')->nullable();
                $table->text('addons_nl')->nullable();
                $table->string('system_requirements_en')->nullable();
                $table->string('system_requirements_nl')->nullable();
                $table->string('supplier_country')->nullable();
                $table->text('personal_data')->nullable();
                $table->string('privacy_policy_url')->nullable();
                $table->string('model_processor_agreement_url')->nullable();
                $table->text('privacy_analysis')->nullable();
                $table->boolean('supplier_agrees_with_surf_standards')->nullable();
                $table->string('dtia_by_external_url')->nullable();
                $table->string('dpia_by_external_url')->nullable();
                $table->string('jurisdiction')->nullable();
                $table->string('instructions_manual_1_url_en')->nullable();
                $table->string('instructions_manual_1_url_nl')->nullable();
                $table->string('instructions_manual_2_url_en')->nullable();
                $table->string('instructions_manual_2_url_nl')->nullable();
                $table->string('instructions_manual_3_url_en')->nullable();
                $table->string('instructions_manual_3_url_nl')->nullable();
                $table->text('support_for_teachers_en')->nullable();
                $table->text('support_for_teachers_nl')->nullable();
                $table->text('availability_surf')->nullable();
                $table->text('accessibility_facilities_en')->nullable();
                $table->text('accessibility_facilities_nl')->nullable();
                $table->text('use_for_education_en')->nullable();
                $table->text('use_for_education_nl')->nullable();
                $table->string('how_does_it_work_en')->nullable();
                $table->string('how_does_it_work_nl')->nullable();
            });

            $table->renameColumn('description_long_1_en', 'description_long_en');
            $table->renameColumn('description_long_1_nl', 'description_long_nl');
            $table->dropColumn(['description_long_2_en', 'description_long_2_nl']);

            $table->renameColumn('image_filename', 'logo_filename');
            $table->renameColumn('description_1_image_filename', 'image_1_filename');
            $table->renameColumn('description_2_image_filename', 'image_2_filename');

            $table->dropColumn([
                'info_url',
                'supported_standards',
                'additional_standards',
                'authentication_methods',
                'stored_data',
                'other_stored_data',
                'european_data_storage',
                'surf_standards_framework_agreed',
                'has_processing_agreement',
            ]);
        });
    }
};
