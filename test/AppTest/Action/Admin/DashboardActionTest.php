<?php

namespace AppTest\Action\Admin;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use TestUtils\TemplateRendererStub;
use TestUtils\TestCase;
use App\Action\Admin\DashboardAction;

class DashboardActionTest extends TestCase
{
    public function testReturnsHtmlResponseWhenTemplateRendererProvided()
    {
        $renderer = new TemplateRendererStub();

        $action = new DashboardAction($renderer);

        $response = $action->process(
            $this->prophesize(ServerRequestInterface::class)->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response, "Response must be HtmlResponse instance");
        $this->assertEquals('layout::admin', $renderer->layout, "Layout must be layout::admin");
        $this->assertEquals('app-admin::dashboard', $renderer->templateName, "Template must be app-admin-games::list");
    }
}
