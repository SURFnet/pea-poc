<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerFilenameToInstitutesTable extends Migration
{
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table): void {
            $table->string('banner_filename')->after('logo_full_filename')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table): void {
            $table->dropColumn('banner_filename');
        });
    }
}
