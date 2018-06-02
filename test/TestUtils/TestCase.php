<?php

namespace TestUtils;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequest;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\Domain\User\User;

class TestCase extends PHPUnitTestCase
{
    /**
     * @var null|ContainerInterface
     */
    private $container;

    /**
     * @var User
     */
    private $authorizedUser;

    /**
     * @param $entityName
     * @return RepositoryInterface
     */
    protected function getRepository($entityName)
    {
        $this->getContainer()->setShared("$entityName\Infrastructure\InMemoryRepository", false);
        return $this->getContainer()->get("$entityName\Infrastructure\InMemoryRepository");
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    protected function authorizeUser(ServerRequestInterface $request = null)
    {
        if (!$request) {
            $request = $this->getRequest();
        }

        $this->authorizedUser = new User(['id' => 1, 'name' => 'John']);
        return $request->withAttribute('currentUser', $this->authorizedUser);
    }

    /**
     * @return User
     */
    public function getAuthorizedUser(): User
    {
        return $this->authorizedUser;
    }

    /**
     * @return ServerRequest
     */
    protected function getRequest()
    {
        return new ServerRequest();
    }

    /**
     * @return WebSocketClientStub
     */
    protected function getWebSocketClient()
    {
        return new WebSocketClientStub('secret', 'url');
    }

    /**
     * @return null|ContainerInterface
     */
    private function getContainer()
    {
        if (!$this->container) {
            $this->container = require dirname(dirname(__DIR__)) . '/config/container.php';
        }

        return $this->container;
    }
}
