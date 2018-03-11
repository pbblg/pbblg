<?php

namespace App\Action\Admin\Games;

use App\Domain\Collection;
use App\Domain\Game\Game;
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
    private $gamesRepository;

    /**
     * @var RepositoryInterface
     */
    private $usersRepository;

    /**
     * @var RepositoryInterface
     */
    private $usersInGamesRepository;

    /**
     * @var ZendViewRenderer
     */
    private $template;

    public function __construct(
        RepositoryInterface $gamesRepository,
        RepositoryInterface $usersRepository,
        RepositoryInterface $usersInGamesRepository,
        Template\TemplateRendererInterface $template = null
    ) {
        $this->gamesRepository = $gamesRepository;
        $this->usersRepository = $usersRepository;
        $this->usersInGamesRepository = $usersInGamesRepository;

        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {

        /** @var Collection $games */
        $games = $this->gamesRepository->findMany([]);

        $usersInGameIds = [];
        $userIds = [];

        /** @var Game $game */
        foreach ($games as $game) {
            /** @var Collection $usersInGame */
            $usersInGame = $this->usersInGamesRepository->findMany([
                'gameId_equalTo' => $game->getId()
            ]);

            $usersInGameIds[$game->getId()] = $usersInGame->getValueByAttribute('userId');
            $userIds = array_merge($userIds, $usersInGame->getValueByAttribute('userId'));
            $userIds[] = $game->getOwnerId();
        }

        $users = $this->getUsers(array_unique($userIds));

        $data = [
            'games' => $games,
            'usersInGameIds' => $usersInGameIds,
            'users' => $users,
        ];

        $errors = [];

        return $this->returnResponse($errors, $data);
    }

    private function returnResponse(array $errors, array $data)
    {
        return new HtmlResponse($this->template->render(
            'app-admin-games::list',
            [
                'errors' => $errors,
                'data' => $data,
                'layout' => 'layout::admin'
            ]
        ));
    }

    /**
     * @param array $ids
     * @return Collection
     */
    private function getUsers(array $ids): Collection
    {
        if (empty($ids)) {
            return new Collection();
        }

        /** @var Collection $users */
        $users = $this->usersRepository->findMany([
            'id_in' => $ids
        ]);

        $users = $users->rebuildByKey('id');

        return $users;
    }
}
