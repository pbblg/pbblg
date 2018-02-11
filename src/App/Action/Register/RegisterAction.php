<?php

namespace App\Action\Register;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template;
use App\Command\Register\RegisterCommand;
use App\Command\Register\RegisterCommandContext;

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

    /**
     * @var RegisterCommand
     */
    private $registerCommand;

    public function __construct(
        Template\TemplateRendererInterface $template = null,
        RegisterInputFilter $inputFilter,
        RegisterCommand $registerCommand
    )
    {
        $this->template = $template;
        $this->inputFilter = $inputFilter;
        $this->registerCommand = $registerCommand;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $data = [];
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            $this->inputFilter->setData($data);
            if ($this->inputFilter->isValid()) {

                $context = RegisterCommandContext::fromData($this->inputFilter->getValues());

                $this->registerCommand->handle($context);

                $response = $delegate->handle($request);

                if ($response->getStatusCode() !== 301) {
                    return new RedirectResponse('/');
                }
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
