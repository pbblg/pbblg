<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
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
                    $accessToken = $this->accessTokenGenerator->generateForUserName($data['username']);
                    $sessionCookie = SetCookie::create('access_token')
                        ->withValue($accessToken->getToken())
                        ->withPath(ini_get('session.cookie_path'));

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
