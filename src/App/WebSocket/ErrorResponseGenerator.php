<?php

namespace App\WebSocket;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Utils;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Helper\Exception as ZendException;

class ErrorResponseGenerator
{
    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @param bool $isDevelopmentMode
     */
    public function __construct($isDevelopmentMode = false)
    {
        $this->debug = (bool)$isDevelopmentMode;
    }

    /**
     * @param \Throwable|\Exception $e
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke($e, ServerRequestInterface $request, ResponseInterface $response)
    {
        $responseArray = [
            'id' => null,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ],
        ];

        return new JsonResponse($responseArray, Utils::getStatusCode($e, $response));
    }
}
