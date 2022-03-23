<?php

declare(strict_types=1);

namespace App\Notes\Models\ViewsLimitReached;

use Phalcon\Mvc\ModelInterface;

final class DeletionBehaviour extends \Phalcon\Mvc\Model\Behavior
{
    public function notify($type, ModelInterface $model)
    {
        if ($type === Event::ID) {
            $model->delete();
        }
    }
}
