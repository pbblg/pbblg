<?php

namespace App\WebSocket\Action\GetGames;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\Domain\Game\Game;
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
            $result[$game->getId()] = [
                'gameId' => $game->getId(),
                'status' => $game->getStatus(),
                'created' => $game->getCreatedDt(),
            ];
        }

        return $result;
    }
}
