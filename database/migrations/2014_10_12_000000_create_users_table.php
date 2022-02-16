<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('institute_id')->nullable();

            $table->string('external_id')->nullable()->unique();
            $table->string('name');
            $table->json('roles')->nullable();

            $table->timestamps();

            $table->foreign('institute_id')
                ->references('id')->on('institutes')
                ->onUpdate('RESTRICT')
                ->onDelete('SET NULL');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
