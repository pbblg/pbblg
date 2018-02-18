<?php

namespace App\WebSocket\Action\Send;

use App\WebSocket\Action\ParamsValidatorInterface;

class SendParamsValidator implements ParamsValidatorInterface
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $secret;

    /**
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {

    }

    /**
     * Must be:
     * {
     *     "secret": "XXX",
     *     "receivers": [1,2,3], or [] (empty array)
     *     "message": {
     *         "event": "newGameCreated",
     *         "params": {
     *             "gameId": "123"
     *         }
     *     }
     * }
     *
     *
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data)
    {
        $this->data = $data;
        if (!array_key_exists('secret', $data)) {
            $this->errors['secret'][] = 'Value is required';
            return false;
        }

        if ($data['secret'] !== $this->secret) {
            $this->errors['secret'][] = "Forbidden";
            return false;
        }

        if (!array_key_exists('receivers', $data)) {
            $this->errors['receivers'][] = 'Value is required';
            return false;
        }

        if (!array_key_exists('message', $data)) {
            $this->errors['message'][] = 'Value is required';
            return false;
        }

        if (empty($data['message'])) {
            $this->errors['message'][] = "Value can't be empty";
            return false;
        }

        if (!array_key_exists('event', $data['message'])) {
            $this->errors['message.event'][] = 'Value is required';
            return false;
        }

        if (empty($data['message']['event'])) {
            $this->errors['message.event'][] = "Value can't be empty";
            return false;
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getValid()
    {
        return empty($this->errors) ? $this->data : [];
    }
}
