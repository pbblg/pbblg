var PORT = 8008;

var options = {
   // 'log level': 1
};

var express = require('express');
var app = express();
var http = require('http');
var server = http.createServer(app);
var io = require('socket.io').listen(server, options);
server.listen(PORT);

var games = {};
var lastGameId = 0;
var playersByName = {};
var playersBySocketId = {};
var lastPlayerId = 0;


io.sockets.on('connection', function (socket) {

    socket.on('disconnect', function () {
        debug(socket, 'disconnect');

        delete playersBySocketId[socket.id];
    });

    socket.on('authenticate', function (message) {

        debug(socket, 'authenticate-pre', message);

        if (!playersByName[message.playerName]) {
            playersByName[message.playerName] = new Player(message.playerName);
        }

        playersByName[message.playerName].socket = socket;
        playersBySocketId[socket.id] = playersByName[message.playerName];

        debug(socket, 'authenticate');
    });

    socket.on('getGameState', function (message) {

        var gamePlayId = null;

        var player = playersBySocketId[socket.id];
        if (player && player.game) {
            gamePlayId = player.game.getId();
        }

        socket.emit('gameState', {'gamePlayId': gamePlayId});

        debug(socket, 'getGameState');
    });

    socket.on('getGameWelcomeState', function (message) {

        var gamesForJoin = {};
        for (var gameId in games) {
            if (games[gameId].canJoin()) {
                gamesForJoin[gameId] = gameDTO(games[gameId]);
            }
        }

        socket.emit('gameWelcomeState', {'gamesForJoin': gamesForJoin});

        debug(socket, 'getGameWelcomeState');
    });


    socket.on('newGame', function (message) {

        var player = playersBySocketId[socket.id];

        if (player) {
            var game = new Game();
            games[game.getId()] = game;

            game.joinPlayer(player);

            socket.broadcast.emit('newGame', gameDTO(game));
            socket.emit('newGame', gameDTO(game));
        }

        debug(socket, 'newGame');
    });



    socket.on('joinGame', function (message) {

        debug(socket, 'joinGame-pre', message);

        var player = playersBySocketId[socket.id];

        if (player) {
            var game = games[message.gameId];
            game.joinPlayer(player);
        }

        debug(socket, 'joinGame');
    });

    socket.on('exitGame', function (message) {

        var player = playersBySocketId[socket.id];

        if (player && player.game) {
            player.game.exitPlayer(player);
            player.socket.emit('exitGame');
        }

        debug(socket, 'exitGame')
    });

    socket.on('startGame', function (message) {
        console.log('startGame');
    });
});


function Game() {

    var id = ++lastGameId;
    var players = {};
    var countMaxPlayers = 5;
    var self = this;
    // var deck = createDeck();

    // function createDeck() {
    //     var deck = [
    //         1,1,1,1,1,
    //         2,2,
    //         3,3,
    //         4,4,
    //         5,5,
    //         6,
    //         7,
    //         8
    //     ];
    //
    //     function shuffle(a) {
    //         var j, x, i;
    //         for (i = a.length - 1; i > 0; i--) {
    //             j = Math.floor(Math.random() * (i + 1));
    //             x = a[i];
    //             a[i] = a[j];
    //             a[j] = x;
    //         }
    //         return a;
    //     }
    //
    //     return shuffle(deck);
    // }

    this.getId = function() {
        return id;
    }
    this.getCountFreePlaces = function() {
        return countMaxPlayers - Object.keys(players).length;
    }
    this.getCountPlayers = function() {
        return Object.keys(players).length;
    }

    this.canJoin = function() {
        return this.getCountFreePlaces() > 0;
    }

    this.joinPlayer = function(player) {
        if (this.canJoin() && !players[player.getId()]) {

            player.game = self;
            players[player.getId()] = player

            self.forAllPlayers(function(playerInGame) {
                if (playerInGame.getId() != player.getId()) {
                    playerInGame.socket.emit('otherPlayerJoinedGame', {
                        player: playerDTO(playerInGame),
                        game: gameDTO(playerInGame.game)
                    });
                } else {
                    playerInGame.socket.emit('currentPlayerJoinedGame', {
                        gameId: playerInGame.game.getId()
                    });
                }
            });

            return player;
        }
        return false;
    }

    this.exitPlayer = function(player) {
        player.game = null;
        delete players[player.getId()];
    }

    this.forAllPlayers = function(callback) {
        for (var playerId in players) {
            callback(players[playerId]);
        }
    }
}

function Player(name) {

    var id = ++lastPlayerId;

    this.getId = function() {
        return id;
    }

    this.getName = function() {
        return name;
    }

    this.socket = null;
    this.game = null;
}

function gameDTO(game) {
    return {
        id: game.getId(),
        countFreePlaces: game.getCountFreePlaces()
    }
}

function playerDTO(player) {
    return {
        id: player.getId(),
        name: player.getName(),
        game: player.game,
    }
}

function debug(socket, event, message) {

    var playersDTOs = [];
    for (var player in playersByName) {
        playersDTOs.push(playerDTO(playersByName[player]));
    }

    var gamesDTOs = [];
    for (var game in games) {
        gamesDTOs.push(gameDTO(games[game]));
    }

    var serverState = {
        event: event,
        message: message,
        players: playersDTOs,
        games: gamesDTOs,
    };

    console.log(serverState)
    socket.emit('serverState', serverState)
    socket.broadcast.emit('serverState', serverState);
}


