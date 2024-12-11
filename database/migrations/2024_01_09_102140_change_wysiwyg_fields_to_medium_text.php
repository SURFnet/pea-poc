<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        foreach (['concept_tools', 'tools'] as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->mediumText('addons_en')->change();
                $table->mediumText('addons_nl')->change();
                $table->mediumText('personal_data')->change();
                $table->mediumText('privacy_analysis')->change();
                $table->mediumText('support_for_teachers_en')->change();
                $table->mediumText('support_for_teachers_nl')->change();
                $table->mediumText('availability_surf')->change();
                $table->mediumText('accessibility_facilities_en')->change();
                $table->mediumText('accessibility_facilities_nl')->change();
                $table->mediumText('use_for_education_en')->change();
                $table->mediumText('use_for_education_nl')->change();
                $table->mediumText('description_short_en')->change();
                $table->mediumText('description_short_nl')->change();
            });
        }

        foreach (['concept_institute_tools', 'institute_tool'] as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->mediumText('links_with_other_tools_en')->change();
                $table->mediumText('links_with_other_tools_nl')->change();
                $table->mediumText('instructions_en')->change();
                $table->mediumText('instructions_nl')->change();
                $table->mediumText('faq_en')->change();
                $table->mediumText('faq_nl')->change();
                $table->mediumText('examples_of_usage_en')->change();
                $table->mediumText('examples_of_usage_nl')->change();
                $table->mediumText('additional_info_text_en')->change();
                $table->mediumText('additional_info_text_nl')->change();
                $table->mediumText('why_unfit_en')->change();
                $table->mediumText('why_unfit_nl')->change();
            });
        }

        Schema::table('institutes', function (Blueprint $table): void {
            $table->mediumText('homepage_body')->change();
        });
    }
};
