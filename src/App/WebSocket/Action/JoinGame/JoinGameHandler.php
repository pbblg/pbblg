<?php

namespace App\WebSocket\Action\JoinGame;

use App\WebSocket\Command\JoinGameCommand;
use App\WebSocket\Command\JoinGameCommandContext;
use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\Domain\User\User;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class JoinGameHandler implements ActionHandlerInterface
{
    /**
     * @var JoinGameCommand
     */
    private $joinGameCommand;

    /**
     * JoinGameHandler constructor.
     * @param JoinGameCommand $joinGameCommand
     */
    public function __construct(JoinGameCommand $joinGameCommand)
    {
        $this->joinGameCommand = $joinGameCommand;
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

        /** @var User $user */
        $user = $request->getAttribute('currentUser');
        $params = $request->getQueryParams();

        $this->joinGameCommand->handle(new JoinGameCommandContext($user, $params['gameId']));
    }
}
