<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfoUrlColumnToToolsTable extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table): void {
            $table->string('info_url')->after('description_2_image_filename')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table): void {
            $table->dropColumn('info_url');
        });
    }
}
