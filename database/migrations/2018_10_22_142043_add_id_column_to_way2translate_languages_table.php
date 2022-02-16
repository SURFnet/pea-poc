<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdColumnToWay2translateLanguagesTable extends Migration
{
    public function up(): void
    {
        Schema::table('way2translate_languages', function (Blueprint $table): void {
            $table->dropPrimary(['locale']);
        });

        Schema::table('way2translate_languages', function (Blueprint $table): void {
            $table->increments('id')->first();
        });
    }

    public function down(): void
    {
        Schema::table('way2translate_languages', function (Blueprint $table): void {
            $table->dropColumn(['id']);
            $table->primary('locale');
        });
    }
}
