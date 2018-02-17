<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template;
use App\Domain\AccessToken\Generator;

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
     * @var Generator
     */
    private $accessTokenGenerator;

    public function __construct(
        Template\TemplateRendererInterface $template,
        LoginInputFilter $inputFilter,
        Generator $accessTokenGenerator
    )
    {
        $this->template = $template;
        $this->inputFilter = $inputFilter;
        $this->accessTokenGenerator = $accessTokenGenerator;
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
                    $this->accessTokenGenerator->generateForUserName($data['username']);
                    return new RedirectResponse('/');
                }

                $errors['username'] = ['Failure' => 'Login Failure, please try again'];
            } else {
                $errors = $this->inputFilter->getMessages();
            }

        }

        return new HtmlResponse($this->template->render('app::login', ['errors' => $errors]));
    }
}
