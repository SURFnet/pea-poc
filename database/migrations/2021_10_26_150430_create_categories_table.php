<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('institute_id');
            $table->string('name');
            $table->string('description', 1024);

            $table->timestamps();

            $table->unique(['institute_id', 'name']);

            $table->foreign('institute_id')
                ->references('id')->on('institutes')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
}
