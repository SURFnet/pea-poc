<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToFailedJobsTable extends Migration
{
    public function up(): void
    {
        Schema::table('failed_jobs', function (Blueprint $table): void {
            $table->string('uuid')->after('id')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('failed_jobs', function (Blueprint $table): void {
            $table->dropColumn('uuid');
        });
    }
}
