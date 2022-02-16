<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureToolTable extends Migration
{
    public function up(): void
    {
        Schema::create('feature_tool', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('tool_id');

            $table->timestamps();

            $table->foreign('feature_id')
                ->references('id')->on('features')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');

            $table->foreign('tool_id')
                ->references('id')->on('tools')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_tool');
    }
}
