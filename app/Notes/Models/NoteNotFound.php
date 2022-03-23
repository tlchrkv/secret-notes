<?php

declare(strict_types=1);

namespace App\Notes\Models;

use App\SharedKernel\Exceptions\NotFoundException;

final class NoteNotFound extends NotFoundException
{
    public function __construct()
    {
        parent::__construct('Note not found');
    }
}
