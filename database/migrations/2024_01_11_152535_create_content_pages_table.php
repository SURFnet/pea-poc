<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('content_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title_en');
            $table->string('title_nl')->nullable();
            $table->string('slug')->unique();
            $table->mediumText('body_en');
            $table->mediumText('body_nl')->nullable();
            $table->timestamps();
        });
    }
};
