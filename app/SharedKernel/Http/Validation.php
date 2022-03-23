<?php

declare(strict_types=1);

namespace App\SharedKernel\Http;

use App\SharedKernel\StringConverter;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Message;

/**
 * @method validate($data)
 */
class Validation extends \Phalcon\Validation
{
    private $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
        parent::__construct();
    }

    public function initialize()
    {
        foreach ($this->rules as $argument => $rules) {
            $rules = explode('|', $rules);

            foreach ($rules as $rule) {
                list($rule, $value) = explode(':', $rule);

                switch ($rule) {
                    case 'required':
                        $this->add(
                            $argument,
                            new PresenceOf(
                                [
                                    'message' => new Message(
                                        sprintf(
                                            '%s is required',
                                            StringConverter::snakeCaseToReadable($argument, true)
                                        )
                                    ),
                                ]
                            )
                        );
                        break;
                    case 'between':
                        $values = explode(',', $value);
                        $this->add(
                            $argument,
                            new Callback([
                                'callback' => function (array $data) use ($argument, $values): bool {
                                    if (!isset($data[$argument])) {
                                        return false;
                                    }
                                    return in_array((int) $data[$argument], range($values[0], $values[1]));
                                },
                                'message' => new Message(
                                    sprintf(
                                        '%s must be between %d and %d',
                                        StringConverter::snakeCaseToReadable($argument, true),
                                        $values[0],
                                        $values[1]
                                    )
                                ),
                            ])
                        );
                        break;
                    case 'equal':
                        $this->add(
                            $argument,
                            new Callback([
                                'callback' => function (array $data) use ($argument, $value): bool {
                                    if (!isset($data[$argument])) {
                                        return false;
                                    }
                                    return $data[$argument] === $value;
                                },
                                'message' => new Message(
                                    sprintf(
                                        '%s must be only %s',
                                        StringConverter::snakeCaseToReadable($argument, true),
                                        $value
                                    )
                                ),
                            ])
                        );
                        break;
                    case 'length_between':
                        $values = explode(',', $value);
                        $this->add(
                            $argument,
                            new Callback([
                                'callback' => function (array $data) use ($argument, $values): bool {
                                    if (!isset($data[$argument])) {
                                        return false;
                                    }
                                    $length = strlen(trim($data[$argument]));
                                    return $length >= $values[0] && $length <= $values[1];
                                },
                                'message' => new Message(
                                    sprintf(
                                        'Length of %s must be between %d and %d symbols',
                                        StringConverter::snakeCaseToReadable($argument),
                                        $values[0],
                                        $values[1]
                                    )
                                ),
                            ])
                        );
                        break;
                }
            }
        }
    }

    public function afterValidation($data, $entity, $messages)
    {
        if (count($messages)) {
            foreach ($messages as $message) {
                throw new \InvalidArgumentException((string) $message);
            }
        }
    }
}
