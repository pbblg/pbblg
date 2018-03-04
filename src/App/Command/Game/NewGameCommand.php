<?php

namespace App\Command\Game;

use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\Command\Exception\NotAuthorizedException;
use App\Domain\Game\Game;

class NewGameCommand
{
    /**
     * @var RepositoryInterface
     */
    private $gameRepository;

    /**
     * NewGameCommand constructor.
     * @param RepositoryInterface $gameRepository
     */
    public function __construct(RepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param NewGameCommandContext $context
     * @return array
     */
    public function handle(NewGameCommandContext $context)
    {
        if (!$context->getUser()) {
            throw new NotAuthorizedException();
        }

        $game = new Game([
            'status' => Game::STATUS_OPEN,
            'ownerId' => $context->getUser()->getId(),
        ]);

        $this->gameRepository->add($game);

        return [
            'gameId' => $game->getId(),
        ];
    }
}