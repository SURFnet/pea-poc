<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitlesToInstituteToolTable extends Migration
{
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->string('extra_information_title')->nullable()->after('extra_information');
            $table->string('support_title_1')->nullable()->after('support_email_1');
            $table->string('support_title_2')->nullable()->after('support_email_2');
            $table->string('manual_title_1')->nullable()->after('manual_url_1');
            $table->string('manual_title_2')->nullable()->after('manual_url_2');
            $table->string('video_title_1')->nullable()->after('video_url_1');
            $table->string('video_title_2')->nullable()->after('video_url_2');
        });
    }

    public function down(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->dropColumn([
                'extra_information_title',
                'support_title_1',
                'support_title_2',
                'manual_title_1',
                'manual_title_2',
                'video_title_1',
                'video_title_2',
            ]);
        });
    }
}
