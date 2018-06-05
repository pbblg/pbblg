<?php

namespace AppTest\Action\Register;

use App\Domain\User\User;
use Interop\Http\ServerMiddleware\DelegateInterface;
use TestUtils\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Stratigility\Next;
use Prophecy\Argument;
use TestUtils\TemplateRendererStub;
use App\Action\Register\RegisterAction;
use App\Action\Register\RegisterInputFilter;
use App\Command\Register\RegisterCommand;
use App\Command\Register\RegisterCommandContext;
use App\Command\Register\UserAlreadyExistsException;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Client;
use App\Domain\AccessToken\Generator;

class RegisterActionTest extends TestCase
{
    /**
     * @var RepositoryInterface
     */
    private $usersRepository;

    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * @var Generator
     */
    private $accessTokenGenerator;

    protected function setUp()
    {
        $this->usersRepository = $this->prophesize(RepositoryInterface::class);
        $this->webSocketClient = $this->prophesize(Client::class);

        $this->accessTokenGenerator = new Generator(
            $this->getRepository('AccessToken'),
            $this->getRepository('User')
        );
    }


    public function testGetRequest()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new RegisterInputFilter();
        $registerCommand = $this->prophesize(RegisterCommand::class);
        $request = $this->prophesize(ServerRequestInterface::class);

        $homePage = new RegisterAction(
            $renderer,
            $inputFilter,
            $registerCommand->reveal(),
            $this->accessTokenGenerator,
            $this->webSocketClient->reveal(),
            $this->usersRepository->reveal()
        );

        $request->getMethod()
            ->willReturn('GET');

        $response = $homePage->process(
            $request->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEmpty($renderer->data);
        $this->assertEmpty($renderer->errors);
    }

    public function testPostRequestWithBadParams()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new RegisterInputFilter();
        $registerCommand = $this->prophesize(RegisterCommand::class);
        $request = $this->prophesize(ServerRequestInterface::class);

        $homePage = new RegisterAction(
            $renderer,
            $inputFilter,
            $registerCommand->reveal(),
            $this->accessTokenGenerator,
            $this->webSocketClient->reveal(),
            $this->usersRepository->reveal()
        );

        $request->getMethod()
            ->willReturn('POST');
        $request->getParsedBody()
            ->willReturn([]/*[
                'username' => 'John',
                'password' => 'xxx',
                'password-again' => 'xxx',
            ]*/);

        $response = $homePage->process(
            $request->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEmpty($renderer->data);
        $this->assertArrayHasKey('username', $renderer->errors);
        $this->assertArrayHasKey('isEmpty', $renderer->errors['username']);
        $this->assertEquals(
            'Value is required and can\'t be empty',
            $renderer->errors['username']['isEmpty']
        );

        $this->assertArrayHasKey('password', $renderer->errors);
        $this->assertArrayHasKey('isEmpty', $renderer->errors['password']);
        $this->assertEquals(
            'Value is required and can\'t be empty',
            $renderer->errors['password']['isEmpty']
        );

        $this->assertArrayHasKey('password-again', $renderer->errors);
        $this->assertArrayHasKey('isEmpty', $renderer->errors['password-again']);
        $this->assertEquals(
            'Value is required and can\'t be empty',
            $renderer->errors['password-again']['isEmpty']
        );
    }

    public function testPostRequestWithAlreadyRegisteredUser()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new RegisterInputFilter();
        $registerCommand = $this->prophesize(RegisterCommand::class);
        $request = $this->prophesize(ServerRequestInterface::class);

        $homePage = new RegisterAction(
            $renderer,
            $inputFilter,
            $registerCommand->reveal(),
            $this->accessTokenGenerator,
            $this->webSocketClient->reveal(),
            $this->usersRepository->reveal()
        );

        $postData = [
            'username' => 'John',
            'password' => 'xxx',
            'password-again' => 'xxx',
        ];
        $request->getMethod()
            ->willReturn('POST');
        $request->getParsedBody()
            ->willReturn($postData);

        $registerCommand->handle(Argument::type(RegisterCommandContext::class))
            ->will(function () {
                throw new UserAlreadyExistsException('John');
            });

        $response = $homePage->process(
            $request->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals($postData, $renderer->data);
        $this->assertArrayHasKey('username', $renderer->errors);
        $this->assertArrayHasKey(0, $renderer->errors['username']);
        $this->assertEquals('User \'John\' already registered', $renderer->errors['username'][0]);
    }

    public function testSuccessPostRequest()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new RegisterInputFilter();
        $registerCommand = $this->prophesize(RegisterCommand::class);
        $request = $this->prophesize(ServerRequestInterface::class);
        $delegate = $this->prophesize(Next::class);

        $userRepository = $this->getRepository('User');
        $userRepository->add(new User([
            'id' => 1,
            'name' => 'John',
            'password' => 'xxx',
        ]));
        $accessTokenGenerator = new Generator(
            $this->getRepository('AccessToken'),
            $userRepository
        );

        $homePage = new RegisterAction(
            $renderer,
            $inputFilter,
            $registerCommand->reveal(),
            $accessTokenGenerator,
            $this->webSocketClient->reveal(),
            $userRepository
        );

        $postData = [
            'username' => 'John',
            'password' => 'xxx',
            'password-again' => 'xxx',
        ];
        $request->getMethod()
            ->willReturn('POST');
        $request->getParsedBody()
            ->willReturn($postData);

        $registerCommand->handle(Argument::type(RegisterCommandContext::class))
            ->willReturn(true);

        $delegate->handle(Argument::exact($request->reveal()))
            ->willReturn(new Response());

        /** @var RedirectResponse $response */
        $response = $homePage->process(
            $request->reveal(),
            $delegate->reveal()
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
