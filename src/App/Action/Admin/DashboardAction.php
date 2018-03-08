<?php

namespace App\Action\Admin;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use Zend\Expressive\ZendView\ZendViewRenderer;
use App\Command\Register\RegisterCommand;

class DashboardAction implements ServerMiddlewareInterface
{
    /**
     * @var ZendViewRenderer
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
        Template\TemplateRendererInterface $template = null
    ) {
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $data = [];
        $errors = [];
//        if ($request->getMethod() === 'POST') {
//            $data = $request->getParsedBody();
//            $this->inputFilter->setData($data);
//            if ($this->inputFilter->isValid()) {
//
//                $context = RegisterCommandContext::fromData($this->inputFilter->getValues());
//
//                try {
//                    $this->registerCommand->handle($context);
//                } catch (UserAlreadyExistsException $e) {
//                    return $this->returnResponse(
//                        ['username' => [$e->getMessage()]],
//                        $data
//                    );
//                }
//
//                $response = $delegate->handle($request);
//
//                if ($response->getStatusCode() !== 301) {
//                    return new RedirectResponse('/');
//                }
//            } else {
//                $errors = $this->inputFilter->getMessages();
//            }
//        }

        return $this->returnResponse($errors, $data);
    }

    private function returnResponse(array $errors, array $data)
    {
        return new HtmlResponse($this->template->render(
            'app-admin::dashboard',
            [
                'errors' => $errors,
                'data' => $data,
                'layout' => 'layout::admin'
            ]
        ));
    }
}
