<?php

namespace App\WebSocket;

use Zend\Diactoros\Response;

class SenderResponse extends Response
{
    /**
     * @var array
     */
    private $receivers;

    /**
     * @var string
     */
    private $message;

    public function __construct(array $receivers, $message)
    {
        $this->receivers = $receivers;
        $this->message = $message;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getReceivers(): array
    {
        return $this->receivers;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
