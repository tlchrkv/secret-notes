<?php

declare(strict_types=1);

namespace App\Notes\Models;

use App\Notes\Models\ViewsLimitReached\Event as ViewsLimitReachedEvent;
use Ramsey\Uuid\UuidInterface;

final class ViewsCounter extends \Phalcon\Mvc\Model
{
    public $noteId;
    public $value;

    public static function attachTo(UuidInterface $id): void
    {
        $viewsCounter = new self([
            'noteId' => $id,
            'value' => 0,
        ]);

        $viewsCounter->save();
    }

    public function increment(): void
    {
        $this->value++;
        $this->save();

        if ($this->note->viewsLimit !== null && $this->value >= $this->note->viewsLimit) {
            $this->note->fireEvent(ViewsLimitReachedEvent::ID);
        }
    }

    public function initialize()
    {
        $this->setSource('note_views_counters');

        $this->hasOne(
            ['noteId'],
            Note::class,
            ['id'],
            ['reusable' => true, 'alias' => 'note']
        );
    }

    public function columnMap(): array
    {
        return [
            'note_id' => 'noteId',
            'value' => 'value',
        ];
    }
}
