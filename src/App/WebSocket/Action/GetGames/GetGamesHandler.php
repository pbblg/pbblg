<?php

namespace App\WebSocket\Action\GetGames;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\Domain\Game\Game;
use App\Domain\Game\ViewModel\Game as GameViewModel;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class GetGamesHandler implements ActionHandlerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $gameRepository;

    public function __construct(RepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        if (!$request->getAttribute('currentUser')) {
            throw new NotAuthorizedException();
        }

        /** @var Game[] $games */
        $games = $this->gameRepository->findMany([
            'order' => 'id DESC',
        ]);

        $result = [];

        foreach ($games as $game) {
            $gameVieModel = new GameViewModel($game);
            $result[$game->getId()] = $gameVieModel->extract();
        }

        return $result;
    }
}
