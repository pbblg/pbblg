<?php

namespace App\WebSocket\Action\NewGame;

use App\Command\Game\NewGameCommandContext;
use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\WebSocket\Event\NewGameCreated;
use App\Command\Game\NewGameCommand;

class NewGameHandler implements ActionHandlerInterface
{
    /**
     * @var NewGameCommand
     */
    private $command;

    /**
     * @var Client
     */
    private $webSocketClient;

    public function __construct(
        NewGameCommand $command,
        Client $webSocketClient
    ) {
        $this->command = $command;
        $this->webSocketClient = $webSocketClient;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        $context = new NewGameCommandContext(
            $request->getAttribute('currentUser')
        );

        $result = $this->command->handle($context);

        $this->webSocketClient->send([], new NewGameCreated($result['gameId']));

        return $result;
    }
}
