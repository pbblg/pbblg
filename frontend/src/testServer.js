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





var state = {
    accessTokens: {
        //accessToken: {
        //  playerId: 1,
        //  sockets: {
        //     socketId: Socket
        //  }
        //}
    },
    accessTokensBySocketId: {
        //socketId: accessToken
    },
    players: {
        //playerId: Player
    },
    games: {
        //gameId: Game
    }
};

var AuthService = require('./server/services/AuthService');
var authService = new AuthService(state);


io.sockets.on('connection', function (socket) {

    authService.authenticate(socket);

    socket.on('disconnect', function () {
        authService.disconnect(socket);
    });

    socket.on('login', function (message) {
        authService.login(socket, message.login, message.password);
    });

    socket.on('logout', function (message) {
        authService.logout(socket);
    });


    socket.on('getPlayersOnline', function (message) {

        var playersOnline = authService.getOnlinePlayers();
        var playersOnlineDTO = {};

        for (var playerId in playersOnline) {
            playersOnlineDTO[playerId] = playerDTO(playersOnline[playerId]);
        }

        socket.emit('playersOnlineList', {'playersOnline': playersOnlineDTO});
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

        var gamePlay = null;

        var player = playersBySocketId[socket.id];
        if (player && player.game) {
            var playersDTO = {};
            player.game.forAllPlayers(function(playerInGame) {
                playersDTO[playerInGame.getId()] = playerDTO(playerInGame);
            });
            gamePlay = {
                gameId: player.game.getId(),
                players: playersDTO
            }
        }

        socket.emit('gameState', {'gamePlay': gamePlay});

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
    this.getPlayers = function() {
        return players;
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

        delete players[player.getId()];
        player.game = null;

        player.socket.broadcast.emit('otherPlayerExitGame', {
            player: playerDTO(player),
            game: gameDTO(self)
        });
        player.socket.emit('exitGame');
    }

    this.forAllPlayers = function(callback) {
        for (var playerId in players) {
            callback(players[playerId]);
        }
    }
}

function Player(id, name) {

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
        id: player.id,
        name: player.name,
        game: player.game,
    }
}

function debug(socket, event, message) {
return;
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
