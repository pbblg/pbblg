<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template;

class LoginAction implements ServerMiddlewareInterface
{
    private $template;

    public function __construct(Template\TemplateRendererInterface $template = null)
    {
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $error  = '';
        if ($request->getMethod() === 'POST') {
            $this->loginForm->setData($request->getParsedBody());
            if ($this->loginForm->isValid()) {
                $response = $delegate->handle($request);
                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/');
                }

                $error = 'Login Failure, please try again';
            }
        }

        return new HtmlResponse($this->template->render('app::login', ['error' => $error]));
    }
}
