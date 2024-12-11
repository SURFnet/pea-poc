<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table): void {
            $table->id();

            $table->json('name');
            $table->json('slug');
            $table->string('type')->nullable();
            $table->foreignId('institute_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('order_column')->nullable();

            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table): void {
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();

            $table->morphs('taggable');

            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }
};
