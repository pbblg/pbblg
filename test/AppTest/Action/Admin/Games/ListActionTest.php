<?php

namespace AppTest\Action\Admin\Games;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use TestUtils\TemplateRendererStub;
use TestUtils\TestCase;
use App\Action\Admin\Games\ListAction;
use App\Domain\Game\Game;
use App\Domain\User\User;
use App\Domain\Game\GameStatus;
use App\Domain\Game\UsersInGames;

class ListActionTest extends TestCase
{
    public function testReturnsHtmlResponseWhenTemplateRendererProvided()
    {
        $renderer = new TemplateRendererStub();
        $gamesRepository = $this->getRepository('Game');
        $usersRepository = $this->getRepository('User');
        $usersInGamesRepository = $this->getRepository('UsersInGames');

        $action = new ListAction(
            $gamesRepository,
            $usersRepository,
            $usersInGamesRepository,
            $renderer
        );

        $this->addUsers($usersRepository);
        $this->addGames($gamesRepository);
        $this->addUsersInGames($usersInGamesRepository);

        $response = $action->process(
            $this->prophesize(ServerRequestInterface::class)->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $this->assertInstanceOf(HtmlResponse::class, $response, "Response must be HtmlResponse instance");
        $this->assertEquals('layout::admin', $renderer->layout, "Layout must be layout::admin");
        $this->assertEquals('app-admin-games::list', $renderer->templateName, "Template must be app-admin-games::list");
        $this->assertArrayHasKey('games', $renderer->data, "Data must contain 'games'");
        $this->assertArrayHasKey('usersInGameIds', $renderer->data, "Data must contain 'usersInGameIds'");
        $this->assertArrayHasKey('users', $renderer->data, "Data must contain 'users'");
        $this->assertEquals($gamesRepository->findMany([]), $renderer->data['games']);
        $this->assertEquals([1 => [1]], $renderer->data['usersInGameIds']);
        $this->assertEquals($usersRepository->findMany([]), $renderer->data['users']);
    }

    private function addUsers(RepositoryInterface $usersRepository)
    {
        $usersRepository->add(new User([
            'name' => 'John',
        ]));
    }

    private function addGames(RepositoryInterface $gamesRepository)
    {
        $gamesRepository->add(new Game([
            'status' => GameStatus::STATUS_OPEN,
            'ownerId' => 1,
            'createdDt' => date('Y-m-d H:i:s'),
        ]));
    }

    private function addUsersInGames(RepositoryInterface $usersInGamesRepository)
    {
        $usersInGamesRepository->add(new UsersInGames([
            'userId' => 1,
            'gameId' => 1,
        ]));
    }
}
