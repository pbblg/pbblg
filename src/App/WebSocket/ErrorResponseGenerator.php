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
     * @var string
     */
    private $stackTraceTemplate = <<<'EOT'
%s raised in file %s line %d:
Message: %s
Stack Trace:
%s

EOT;

    private $errorCodesMap = [
        ZendException\MalformedRequestBodyException::class => -32700,
        /*
        -32700 	Parse error
        -32600 	Invalid Request 	The JSON sent is not a valid Request object.
-32601 	Method not found 	The method does not exist / is not available.
-32602 	Invalid params 	Invalid method parameter(s).
-32603 	Internal error 	Internal JSON-RPC error.
-32000 to -32099 	Server error 	Reserved for implementation-defined server-errors.*/
    ];

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
        $message = $e->getMessage();

        //if ($this->debug) {
        //    $message .= "; strack trace:\n\n" . $this->prepareStackTrace($e);
        //}

        $responseArray = [
            'id' => null,
            'error' => [
                'code' => -32700,
                'message' => $message
            ],
        ];

        return new JsonResponse($responseArray, Utils::getStatusCode($e, $response));
    }

    /**
     * Prepares a stack trace to display.
     *
     * @param \Throwable|\Exception $e
     * @return string
     */
    private function prepareStackTrace($e)
    {
        $message = '';
        do {
            $message .= sprintf(
                $this->stackTraceTemplate,
                get_class($e),
                $e->getFile(),
                $e->getLine(),
                $e->getMessage(),
                $e->getTraceAsString()
            );
        } while ($e = $e->getPrevious());

        return $message;
    }
}
