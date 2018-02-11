<?php

namespace App\Action\Register;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

class RegisterAction implements ServerMiddlewareInterface
{
    /**
     * @var Template\TemplateRendererInterface
     */
    private $template;

    /**
     * @var RegisterInputFilter
     */
    private $inputFilter;

    public function __construct(
        Template\TemplateRendererInterface $template = null,
        RegisterInputFilter $inputFilter
    )
    {
        $this->template = $template;
        $this->inputFilter = $inputFilter;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $data = [];
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $this->inputFilter->setData($data);
            if ($this->inputFilter->isValid()) {
                return $delegate->handle($request);
            } else {
                $errors = $this->inputFilter->getMessages();
            }
        }

        return new HtmlResponse($this->template->render(
            'app::register',
            [
                'errors' => $errors,
                'data' => $data
            ]
        ));
    }
}
