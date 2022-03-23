<?php

declare(strict_types=1);

namespace App\Notes\Console;

use App\Notes\Models\NoteRepository;

final class DeleteOverdueNotesTask extends \Phalcon\Cli\Task
{
    public function mainAction(): void
    {
        $notes = NoteRepository::findWhereStorageTimeExpired();

        echo sprintf('Found %d overdue notes', $notes->count()) . PHP_EOL;

        if ($notes->count() === 0) {
            exit(0);
        }

        foreach ($notes as $note) {
            $note->delete();
        }

        echo 'Cleared' . PHP_EOL;
    }
}
