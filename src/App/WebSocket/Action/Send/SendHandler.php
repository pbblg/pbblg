<?php

namespace App\WebSocket\Action\Send;

use App\WebSocket\Action\SpecialHandlerInterface;
use App\WebSocket\SenderResponse;

class SendHandler implements SpecialHandlerInterface
{
    /**
     * @param array $params
     * @return mixed result
     */
    public function handle(array $params)
    {
        $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES;
        return new SenderResponse(
            array_unique($params['receivers']),
            json_encode($params['message'], $jsonOptions)
        );
    }
}
