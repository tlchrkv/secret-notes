<?php

declare(strict_types=1);

namespace App\Notes\Models;

use App\Notes\Models\ViewsLimitReached\DeletionBehaviour;
use Ramsey\Uuid\Uuid;

final class Note extends \Phalcon\Mvc\Model
{
    public $id;
    public $content;
    public $code;
    public $cipher;
    public $initVector;
    public $encoding;
    public $viewsLimit;
    public $storageTimeExpiresAt;

    public static function add(string $code, string $content): void
    {
        $id = Uuid::uuid4();

        $note = new self([
            'id' => $id,
            'content' => $content,
            'code' => $code,
        ]);

        $note->getDI()->getShared('db')->begin();

        $note->enableAutoDeleteOnStorageTimeExpired(1);

        ViewsCounter::attachTo($id);

        $note->getDI()->getShared('db')->commit();
    }

    public static function addEncrypted(string $code, string $cipher, string $initVector, string $encoding): void
    {
        $id = Uuid::uuid4();

        $note = new self([
            'id' => $id,
            'code' => $code,
            'cipher' => $cipher,
            'initVector' => $initVector,
            'encoding' => $encoding,
        ]);

        $note->getDI()->getShared('db')->begin();

        $note->enableAutoDeleteOnStorageTimeExpired(1);

        ViewsCounter::attachTo($id);

        $note->getDI()->getShared('db')->commit();
    }

    public function enableAutoDeleteOnViewsLimitReached(int $viewsLimit): void
    {
        $this->viewsLimit = $viewsLimit;
        $this->storageTimeExpiresAt = null;
        $this->save();

        $this->viewsCounter->value = 0;
        $this->viewsCounter->save();
    }

    public function enableAutoDeleteOnStorageTimeExpired(int $storageTimeInDays): void
    {
        $nowDateTime = new \DateTime('now');
        $storageTimeInDaysDateInterval = new \DateInterval(sprintf('P%dD', $storageTimeInDays));

        $this->storageTimeExpiresAt = $nowDateTime->add($storageTimeInDaysDateInterval)->format('Y-m-d H:i:s');
        $this->viewsLimit = null;
        $this->save();
    }

    public function delete()
    {
        $this->getDI()->getShared('db')->begin();

        $this->viewsCounter->delete();
        parent::delete();

        $this->getDI()->getShared('db')->commit();
    }

    public function initialize(): void
    {
        $this->setSource('notes');

        $this->hasOne(
            ['id'],
            ViewsCounter::class,
            ['noteId'],
            ['reusable' => true, 'alias' => 'viewsCounter']
        );

        $this->addBehavior(new DeletionBehaviour());
    }

    public function columnMap(): array
    {
        return [
            'id' => 'id',
            'content' => 'content',
            'code' => 'code',
            'cipher' => 'cipher',
            'init_vector' => 'initVector',
            'encoding' => 'encoding',
            'views_limit' => 'viewsLimit',
            'storage_time_expires_at' => 'storageTimeExpiresAt',
        ];
    }
}
