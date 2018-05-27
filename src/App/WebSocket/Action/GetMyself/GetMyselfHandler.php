<?php

namespace App\WebSocket\Action\GetMyself;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\Domain\User\User;
use App\Domain\Game\Game;
use App\WebSocket\Event\NewGameCreated;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\Domain\Game\GameStatus;

class GetMyselfHandler implements ActionHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        if (!$request->getAttribute('currentUser')) {
            throw new NotAuthorizedException();
        }

        /** @var User $user */
        $user = $request->getAttribute('currentUser');

        return $user->extract();
    }
}
