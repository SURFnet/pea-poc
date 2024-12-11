<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('pending_tool_edits', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('tool_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('institute_id')->nullable();

            $table->foreign('tool_id')
                ->references('id')->on('tools')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->foreign('institute_id')
                ->references('id')->on('institutes')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['tool_id', 'user_id', 'institute_id']);
        });
    }
};
