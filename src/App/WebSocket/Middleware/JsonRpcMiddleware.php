<?php

namespace App\WebSocket\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use App\WebSocket\Exception;

use const Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD;

class JsonRpcMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $header = $request->getHeaderLine('Content-Type');
        if (! $this->match($header)) {
            throw new Exception\ParseErrorException(
                'Error when parsing request header: Content-Type must be application/json'
            );
        }

        // Matched! Parse and pass on to the next
        return $delegate->{HANDLER_METHOD}($this->parse($request));
    }

    /**
     * Match the content type to the strategy criteria.
     *
     * @param string $contentType
     * @return bool Whether or not the strategy matches.
     */
    private function match($contentType)
    {
        $parts = explode(';', $contentType);
        $mime = array_shift($parts);
        return (bool) preg_match('#[/+]json$#', trim($mime));
    }

    /**
     * Parse the body content and return a new request.
     *
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function parse(ServerRequestInterface $request)
    {
        $rawBody = (string) $request->getBody();
        $parsedBody = json_decode($rawBody, true);

        if (! empty($rawBody) && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception\ParseErrorException(sprintf(
                'Error when parsing JSON request body: %s',
                json_last_error_msg()
            ));
        }

        if (!array_key_exists('id', $parsedBody)) {
            throw new Exception\InvalidRequestException(
                'The JSON sent is not a valid Request object: id is required'
            );
        }

        if (!array_key_exists('method', $parsedBody)) {
            throw new Exception\InvalidRequestException(
                'The JSON sent is not a valid Request object: method is required'
            );
        }

        $params = [];
        if (array_key_exists('params', $parsedBody)) {
            $params = $parsedBody['params'];
        }

        return $request
            ->withAttribute('rawBody', $rawBody)
            ->withAttribute('id', $parsedBody['id'])
            ->withAttribute('method', $parsedBody['method'])
            ->withAttribute('params', $params)
            ->withParsedBody($parsedBody);
    }
}
