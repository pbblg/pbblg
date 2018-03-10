<?php

namespace App\WebSocket\Event;

abstract class AbstractEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct(string $name, array $params)
    {
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function toArray()
    {
        return [
            "event" => $this->getName(),
            "params" => $this->getParams(),
        ];
    }
}
