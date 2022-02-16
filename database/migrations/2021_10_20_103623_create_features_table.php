<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    private array $features = [
        'Chat',
        'Create learning materials',
        'Edit video, sound or image',
        'Exams and assignments',
        'Feedback',
        'File share',
        'Mindmaps',
        'Notebook',
        'Presenting',
        'Project Planning',
        'Quiz, Games or Polls',
        'Revision',
        'Schedule or Roster',
        'Surveys',
        'Whiteboard',
    ];

    public function up(): void
    {
        Schema::create('features', function (Blueprint $table): void {
            $table->id();

            $table->string('name')->unique();

            $table->timestamps();
        });

        foreach ($this->features as $feature) {
            DB::table('features')->insert([
                'name'       => $feature,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
}
