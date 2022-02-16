<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutesTable extends Migration
{
    public function up(): void
    {
        Schema::create('institutes', function (Blueprint $table): void {
            $table->id();

            $table->string('full_name');
            $table->string('short_name');
            $table->string('domain')->unique();

            $table->string('logo_square_filename');
            $table->string('logo_full_filename');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
}
