<?php

namespace AppTest\Action\Register;

use App\Action\LoginAction;
use App\Action\LoginInputFilter;
use TestUtils\TestCase;
use TestUtils\TemplateRendererStub;
use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Command\LoginCommand;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Stratigility\Next;
use Prophecy\Argument;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\RedirectResponse;
use App\WebSocket\Command\LoginCommandContext;
use Dflydev\FigCookies\SetCookie;

class LoginActionTest extends TestCase
{
    public function testGetRequest()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new LoginInputFilter();
        $request = $this->prophesize(ServerRequestInterface::class);
        $loginCommand = $this->prophesize(LoginCommand::class);

        $homePage = new LoginAction(
            $renderer,
            $inputFilter,
            $loginCommand->reveal()
        );

        $request->getMethod()
            ->willReturn('GET');

        $response = $homePage->process(
            $request->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEmpty($renderer->errors);
    }

    public function testPostRequestWithBadParams()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new LoginInputFilter();
        $request = $this->prophesize(ServerRequestInterface::class);
        $loginCommand = $this->prophesize(LoginCommand::class);

        $homePage = new LoginAction(
            $renderer,
            $inputFilter,
            $loginCommand->reveal()
        );

        $request->getMethod()
            ->willReturn('POST');
        $request->getParsedBody()
            ->willReturn([]);

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
    }

    public function testNotExistingUser()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new LoginInputFilter();
        $request = $this->prophesize(ServerRequestInterface::class);
        $loginCommand = $this->prophesize(LoginCommand::class);
        $delegate = $this->prophesize(Next::class);

        $homePage = new LoginAction(
            $renderer,
            $inputFilter,
            $loginCommand->reveal()
        );

        $postData = [
            'username' => 'John',
            'password' => 'xxx'
        ];
        $request->getMethod()
            ->willReturn('POST');
        $request->getParsedBody()
            ->willReturn($postData);

        $delegate->handle(Argument::exact($request->reveal()))
            ->willReturn((new Response())->withStatus(301));

        $response = $homePage->process(
            $request->reveal(),
            $delegate->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEmpty($renderer->data);
        $this->assertArrayHasKey('username', $renderer->errors);
        $this->assertArrayHasKey('Failure', $renderer->errors['username']);
        $this->assertEquals(
            'Login Failure, please try again',
            $renderer->errors['username']['Failure']
        );
    }

    public function testSuccessPostRequest()
    {
        $renderer = new TemplateRendererStub();
        $inputFilter = new LoginInputFilter();
        $request = $this->prophesize(ServerRequestInterface::class);
        $loginCommand = $this->prophesize(LoginCommand::class);
        $delegate = $this->prophesize(Next::class);

        $homePage = new LoginAction(
            $renderer,
            $inputFilter,
            $loginCommand->reveal()
        );

        $postData = [
            'username' => 'John',
            'password' => 'xxx'
        ];
        $request->getMethod()
            ->willReturn('POST');
        $request->getParsedBody()
            ->willReturn($postData);

        $delegate->handle(Argument::exact($request->reveal()))
            ->willReturn(new Response());

        $loginCommand->handle(Argument::type(LoginCommandContext::class))
            ->shouldBeCalledTimes(1)->willReturn(SetCookie::create('access_token'));

        /** @var RedirectResponse $response */
        $response = $homePage->process(
            $request->reveal(),
            $delegate->reveal()
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
