<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituteToolTable extends Migration
{
    public function up(): void
    {
        Schema::create('institute_tool', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('tool_id');

            $table->mediumText('description_1')->nullable();
            $table->string('description_1_image_filename')->nullable()->default(null);

            $table->mediumText('description_2')->nullable();
            $table->string('description_2_image_filename')->nullable()->default(null);

            $table->mediumText('extra_information')->nullable();

            $table->string('support_email_1')->nullable();
            $table->string('support_email_2')->nullable();

            $table->string('manual_url_1')->nullable();
            $table->string('manual_url_2')->nullable();

            $table->string('video_url_1')->nullable();
            $table->string('video_url_2')->nullable();

            $table->timestamps();

            $table->foreign('institute_id')
                ->references('id')->on('institutes')
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
        Schema::dropIfExists('institute_tool');
    }
}
