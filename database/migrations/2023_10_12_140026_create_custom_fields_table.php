<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('institute_id');

            $table->string('title_en');
            $table->string('title_nl')->nullable();

            $table->unsignedInteger('sortkey');

            $table->timestamps();

            $table->foreign('institute_id')
                ->references('id')->on('institutes')
                ->restrictOnDelete()
                ->cascadeOnDelete();
        });
    }
};
