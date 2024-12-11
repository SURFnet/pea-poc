<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('tool_followers', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('tool_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('tool_id')
                ->references('id')->on('tools')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
};
