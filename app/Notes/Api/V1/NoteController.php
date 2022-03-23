<?php

declare(strict_types=1);

namespace App\Notes\Api\V1;

use App\Notes\Models\Note;
use App\Notes\Models\NoteRepository;
use App\SharedKernel\Http\Validation;
use App\SharedKernel\RandomStringGenerator;

final class NoteController extends \Phalcon\Mvc\Controller
{
    public function addAction(): void
    {
        $validation = new Validation([
            'content' => 'required|length_between:1,' . $this->config['notes']['symbols_limit']
        ]);
        $validation->validate($_POST);

        $content = trim($_POST['content']);

        $code = RandomStringGenerator::generate($this->config['notes']['code_length']);

        Note::add($code, $content);

        $this->response
            ->setStatusCode(201)
            ->setJsonContent([
                'code' => $code,
            ])
            ->send();
    }

    public function addEncryptedAction(): void
    {
        $validation = new Validation([
            'cipher' => 'required',
            'init_vector' => 'required',
            'encoding' => 'required'
        ]);
        $validation->validate($_POST);

        $cipher = $_POST['cipher'];
        $initVector = $_POST['init_vector'];
        $encoding = $_POST['encoding'];

        $code = RandomStringGenerator::generate($this->config['notes']['code_length']);

        Note::addEncrypted($code, $cipher, $initVector, $encoding);

        $this->response
            ->setStatusCode(201)
            ->setJsonContent([
                'code' => $code,
            ])
            ->send();
    }

    public function enableAutoDeleteOnViewsLimitReachedAction(string $code): void
    {
        $validation = new Validation([
            'views_limit' => 'required|int|between:1,' . $this->config['notes']['views_limit']
        ]);
        $validation->validate($_POST);

        $viewsLimit = (int) $_POST['views_limit'];

        NoteRepository::findFirstByCode($code)->enableAutoDeleteOnViewsLimitReached($viewsLimit);

        $this->response->setStatusCode(204)->send();
    }

    public function enableAutoDeleteOnStorageTimeExpiredAction(string $code): void
    {
        $validation = new Validation([
            'storage_time_in_days' => 'required|int|between:1,' . $this->config['notes']['storage_time_limit_in_days']
        ]);
        $validation->validate($_POST);

        $storageTimeInDays = (int) $_POST['storage_time_in_days'];

        NoteRepository::findFirstByCode($code)->enableAutoDeleteOnStorageTimeExpired($storageTimeInDays);

        $this->response->setStatusCode(204)->send();
    }

    public function getByCodeAction(string $code): void
    {
        $note = NoteRepository::findFirstByCode($code);

        $note->viewsCounter->increment();

        $this->response
            ->setStatusCode(200)
            ->setJsonContent([
                'content' => $note->content,
                'cipher' => $note->cipher,
                'init_vector' => $note->initVector,
                'encoding' => $note->encoding,
            ])
            ->send();
    }
}
