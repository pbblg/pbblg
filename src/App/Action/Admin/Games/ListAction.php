<?php

namespace App\Action\Admin\Games;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use Zend\Expressive\ZendView\ZendViewRenderer;

class ListAction implements ServerMiddlewareInterface
{
    /**
     * @var ZendViewRenderer
     */
    private $template;

    public function __construct(
        Template\TemplateRendererInterface $template = null
    ) {
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $data = [];
        $errors = [];

        return $this->returnResponse($errors, $data);
    }

    private function returnResponse(array $errors, array $data)
    {
        return new HtmlResponse($this->template->render(
            'app-admin-games::list',
            [
                'errors' => $errors,
                'data' => $data,
                'layout' => 'layout::admin'
            ]
        ));
    }
}
