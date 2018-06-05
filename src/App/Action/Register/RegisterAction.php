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
use App\Command\Register\UserAlreadyExistsException;
use App\Domain\AccessToken\Generator;
use App\WebSocket\Client;
use Dflydev\FigCookies\FigResponseCookies;
use App\WebSocket\Event\UserLoggedIn;
use App\Domain\User\User;
use Dflydev\FigCookies\SetCookie;
use T4webDomainInterface\Infrastructure\RepositoryInterface;

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

    /**
     * @var Generator
     */
    private $accessTokenGenerator;

    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * RegisterAction constructor.
     * @param Template\TemplateRendererInterface|null $template
     * @param RegisterInputFilter $inputFilter
     * @param RegisterCommand $registerCommand
     * @param Generator $accessTokenGenerator
     * @param Client $webSocketClient
     * @param RepositoryInterface $userRepository
     */
    public function __construct(
        Template\TemplateRendererInterface $template = null,
        RegisterInputFilter $inputFilter,
        RegisterCommand $registerCommand,
        Generator $accessTokenGenerator,
        Client $webSocketClient,
        RepositoryInterface $userRepository
    ) {
        $this->template = $template;
        $this->inputFilter = $inputFilter;
        $this->registerCommand = $registerCommand;
        $this->accessTokenGenerator = $accessTokenGenerator;
        $this->webSocketClient = $webSocketClient;
        $this->userRepository = $userRepository;
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

                try {
                    $this->registerCommand->handle($context);
                } catch (UserAlreadyExistsException $e) {
                    return $this->returnResponse(
                        ['username' => [$e->getMessage()]],
                        $data
                    );
                }

                $response = $delegate->handle($request);

                if ($response->getStatusCode() !== 301) {
                    $accessToken = $this->accessTokenGenerator->generateForUserName($data['username']);
                    $sessionCookie = SetCookie::create('access_token')
                        ->withValue($accessToken->getToken())
                        ->withPath(ini_get('session.cookie_path'));

                    /** @var User $user */
                    $user = $this->userRepository->find(['name_equalTo' => $data['username']]);

                    $this->webSocketClient->send([], new UserLoggedIn([
                        'id' => $user->getId(),
                        'name' => $user->getName()
                    ]));
                    return FigResponseCookies::set(new RedirectResponse('/'), $sessionCookie);
                }
            } else {
                $errors = $this->inputFilter->getMessages();
            }
        }

        return $this->returnResponse($errors, $data);
    }

    private function returnResponse(array $errors, array $data)
    {
        return new HtmlResponse($this->template->render(
            'app::register',
            [
                'errors' => $errors,
                'data' => $data
            ]
        ));
    }
}
