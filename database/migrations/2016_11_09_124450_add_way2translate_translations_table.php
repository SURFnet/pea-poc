<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWay2translateTranslationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('way2translate_translations', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('locale', 10);
            $table->string('namespace', 150)->default('*');
            $table->string('group', 150);
            $table->string('name', 150);
            $table->text('value');
            $table->boolean('in_latest_import');
            $table->timestamps();
            $table->unique(['locale', 'namespace', 'group', 'name']);
        });
    }

    public function down(): void
    {
        Schema::drop('way2translate_translations');
    }
}
