<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tool_id');

            $table->integer('rating');
            $table->string('title')->nullable()->default(null);
            $table->mediumText('message')->nullable()->default(null);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('RESTRICT')
                ->onDelete('SET NULL');

            $table->foreign('tool_id')
                ->references('id')->on('tools')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
}
