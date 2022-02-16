<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToInstituteTool extends Migration
{
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->string('status', 25)->nullable()->after('video_title_2');
        });
    }

    public function down(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }
}
