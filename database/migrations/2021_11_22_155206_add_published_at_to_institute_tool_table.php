<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishedAtToInstituteToolTable extends Migration
{
    public function up(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->dateTime('published_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('institute_tool', function (Blueprint $table): void {
            $table->dropColumn('published_at');
        });
    }
}
