<?php

declare(strict_types=1);

namespace App\Notes\Models;

use Phalcon\Mvc\Model\Resultset;

final class NoteRepository
{
    /**
     * @throws NoteNotFound
     */
    public static function findFirstById(string $id): Note
    {
        $note = Note::findFirst(['conditions' => 'id = ?0', 'bind' => [$id]]);

        if ($note === false) {
            throw new NoteNotFound();
        }

        return $note;
    }

    /**
     * @throws NoteNotFound
     */
    public static function findFirstByCode(string $code): Note
    {
        $note = Note::findFirst(['conditions' => 'code = ?0', 'bind' => [$code]]);

        if ($note === false) {
            throw new NoteNotFound();
        }

        return $note;
    }

    public static function findWhereStorageTimeExpired(): Resultset
    {
        $now = new \DateTime('now');

        return Note::find("storageTimeExpiresAt <= '{$now->format('Y-m-d H:i:s')}'");
    }
}
