<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->renameColumn('description_1', 'description_1_en');
            $table->renameColumn('description_2', 'description_2_en');
            $table->renameColumn('extra_information', 'extra_information_en');
            $table->renameColumn('extra_information_title', 'extra_information_title_en');
            $table->renameColumn('support_title_1', 'support_title_1_en');
            $table->renameColumn('support_title_2', 'support_title_2_en');
            $table->renameColumn('manual_title_1', 'manual_title_1_en');
            $table->renameColumn('manual_title_2', 'manual_title_2_en');
            $table->renameColumn('video_title_1', 'video_title_1_en');
            $table->renameColumn('video_title_2', 'video_title_2_en');
            $table->renameColumn('why_unfit', 'why_unfit_en');
        });

        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->mediumText('description_1_nl')->after('description_1_en')->nullable();
            $table->mediumText('description_2_nl')->after('description_2_en')->nullable();
            $table->mediumText('extra_information_nl')->after('extra_information_en')->nullable();
            $table->string('extra_information_title_nl')->after('extra_information_title_en')->nullable();
            $table->string('support_title_1_nl')->after('support_title_1_en')->nullable();
            $table->string('support_title_2_nl')->after('support_title_2_en')->nullable();
            $table->string('manual_title_1_nl')->after('manual_title_1_en')->nullable();
            $table->string('manual_title_2_nl')->after('manual_title_2_en')->nullable();
            $table->string('video_title_1_nl')->after('video_title_1_en')->nullable();
            $table->string('video_title_2_nl')->after('video_title_2_en')->nullable();
            $table->text('why_unfit_nl')->after('why_unfit_en')->nullable();
        });
    }
};
