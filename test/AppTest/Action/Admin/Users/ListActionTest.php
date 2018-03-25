<?php

namespace AppTest\Action\Admin\Users;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use TestUtils\TemplateRendererStub;
use TestUtils\TestCase;
use App\Action\Admin\Users\ListAction;
use App\Domain\User\User;

class ListActionTest extends TestCase
{
    public function testReturnsHtmlResponseWhenTemplateRendererProvided()
    {
        $renderer = new TemplateRendererStub();
        $usersRepository = $this->getRepository('User');

        $action = new ListAction(
            $usersRepository,
            $renderer
        );

        $this->addUsers($usersRepository);

        $response = $action->process(
            $this->prophesize(ServerRequestInterface::class)->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response, "Response must be HtmlResponse instance");
        $this->assertEquals('layout::admin', $renderer->layout, "Layout must be layout::admin");
        $this->assertEquals('app-admin-users::list', $renderer->templateName, "Template must be app-admin-games::list");
        $this->assertArrayHasKey('users', $renderer->data, "Data must contain 'users'");
        $this->assertEquals($usersRepository->findMany([]), $renderer->data['users']);
    }

    private function addUsers(RepositoryInterface $usersRepository)
    {
        $usersRepository->add(new User([
            'name' => 'John',
        ]));
    }
}
