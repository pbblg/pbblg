<?php

namespace App\Action\Admin\Users;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use Zend\Expressive\ZendView\ZendViewRenderer;
use T4webDomainInterface\Infrastructure\RepositoryInterface;

class ListAction implements ServerMiddlewareInterface
{
    /**
     * @var RepositoryInterface
     */
    private $usersRepository;

    /**
     * @var ZendViewRenderer
     */
    private $template;

    public function __construct(
        RepositoryInterface $usersRepository,
        Template\TemplateRendererInterface $template = null
    ) {
        $this->usersRepository = $usersRepository;

        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $users = $this->usersRepository->findMany([]);

        $data = [
            'users' => $users,
        ];

        $errors = [];

        return $this->returnResponse($errors, $data);
    }

    private function returnResponse(array $errors, array $data)
    {
        return new HtmlResponse($this->template->render(
            'app-admin-users::list',
            [
                'errors' => $errors,
                'data' => $data,
                'layout' => 'layout::admin'
            ]
        ));
    }
}
