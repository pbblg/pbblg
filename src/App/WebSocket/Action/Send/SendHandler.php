<?php

namespace App\WebSocket\Action\Send;

use App\WebSocket\Action\SpecialHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class SendHandler implements SpecialHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES;
        $params = $request->getQueryParams();
        return new SenderResponse(
            array_unique($params['receivers']),
            json_encode($params['message'], $jsonOptions)
        );
    }
}
