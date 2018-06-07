<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template;
use Dflydev\FigCookies\FigResponseCookies;
use App\WebSocket\Command\LoginCommand;
use App\WebSocket\Command\LoginCommandContext;

class LoginAction implements ServerMiddlewareInterface
{
    /**
     * @var Template\TemplateRendererInterface
     */
    private $template;

    /**
     * @var LoginInputFilter
     */
    private $inputFilter;

    /**
     * @var LoginCommand
     */
    private $loginCommand;

    /**
     * LoginAction constructor.
     * @param Template\TemplateRendererInterface $template
     * @param LoginInputFilter $inputFilter
     * @param LoginCommand $loginCommand
     */
    public function __construct(
        Template\TemplateRendererInterface $template,
        LoginInputFilter $inputFilter,
        LoginCommand $loginCommand
    ) {
        $this->template = $template;
        $this->inputFilter = $inputFilter;
        $this->loginCommand = $loginCommand;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $this->inputFilter->setData($data);
            if ($this->inputFilter->isValid()) {
                $response = $delegate->handle($request);
                if ($response->getStatusCode() !== 301) {
                    $sessionCookie = $this->loginCommand->handle(new LoginCommandContext($data['username']));

                    return FigResponseCookies::set(new RedirectResponse('/'), $sessionCookie);
                }

                $errors['username'] = ['Failure' => 'Login Failure, please try again'];
            } else {
                $errors = $this->inputFilter->getMessages();
            }
        }

        return new HtmlResponse($this->template->render('app::login', ['errors' => $errors]));
    }
}
