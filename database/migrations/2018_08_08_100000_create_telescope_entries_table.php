<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

class CreateTelescopeEntriesTable extends Migration
{
    protected Builder $schema;

    public function __construct()
    {
        $this->schema = Schema::connection($this->getConnection());
    }

    public function getConnection(): ?string
    {
        return config('telescope.storage.database.connection');
    }

    public function up(): void
    {
        $this->schema->create('telescope_entries', function (Blueprint $table): void {
            $table->bigIncrements('sequence');
            $table->uuid('uuid');
            $table->uuid('batch_id');
            $table->string('family_hash')->nullable()->index();
            $table->boolean('should_display_on_index')->default(true);
            $table->string('type', 20);
            $table->longText('content');
            $table->dateTime('created_at')->nullable();

            $table->unique('uuid');
            $table->index('batch_id');
            $table->index(['type', 'should_display_on_index']);
        });

        $this->schema->create('telescope_entries_tags', function (Blueprint $table): void {
            $table->uuid('entry_uuid');
            $table->string('tag');

            $table->index(['entry_uuid', 'tag']);
            $table->index('tag');

            $table->foreign('entry_uuid')
                ->references('uuid')
                ->on('telescope_entries')
                ->onDelete('cascade');
        });

        $this->schema->create('telescope_monitoring', function (Blueprint $table): void {
            $table->string('tag');
        });
    }

    public function down(): void
    {
        $this->schema->dropIfExists('telescope_entries_tags');
        $this->schema->dropIfExists('telescope_entries');
        $this->schema->dropIfExists('telescope_monitoring');
    }
}
