var PORT = 8008;

var options = {
   // 'log level': 1
};

var express = require('express');
var app = express();
var http = require('http');
var cookie = require('cookie');
var server = http.createServer(app);
var io = require('socket.io').listen(server, options);
server.listen(PORT);

var persistData = require('./server/persistData');

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



var games = {};
var lastGameId = 0;
var playersByName = {};
var playersBySocketId = {};
var lastPlayerId = 0;


io.sockets.on('connection', function (socket) {

    if (socket.request.headers.cookie) {
        var cookies = cookie.parse(socket.request.headers.cookie);
        if (cookies.access_token) {
            if (state.accessTokens[cookies.access_token]) {
                var player = persistData.players[state.accessTokens[cookies.access_token].playerId];

                state.accessTokensBySocketId[socket.id] = cookies.access_token;
                state.accessTokens[cookies.access_token].sockets[socket.id] = socket;

                socket.emit('authenticated', playerDTO(player));
            }
        }
    }

    socket.on('disconnect', function () {
        debug(socket, 'disconnect');

        if (state.accessTokensBySocketId[socket.id]) {
            delete state.accessTokens[state.accessTokensBySocketId[socket.id]].sockets[socket.id];
            delete state.accessTokensBySocketId[socket.id];
        }
    });

    socket.on('login', function (message) {

        debug(socket, 'login-pre', message);

        var loggedPlayer = null;
        var newAccessToken = null;

        for (var playerId in persistData.players) {
            var player = persistData.players[playerId];

            if (player.login == message.login && player.password == message.password) {
                newAccessToken = generateAccessToken();

                state.accessTokens[newAccessToken] = {
                    playerId: player.id,
                    sockets: {}
                };
                state.accessTokens[newAccessToken].sockets[socket.id] = socket;

                loggedPlayer = player;
                break;
            }
        }

        if (loggedPlayer) {
            socket.emit('loginSuccess', {
                accessToken: newAccessToken,
                player: playerDTO(player),
            });
        } else {
            socket.emit('loginFail', {
                error: 'Wrong login or password'
            });
        }

        debug(socket, 'login');
    });

    socket.on('logout', function (message) {

        debug(socket, 'logout-pre', message);

        if (state.accessTokensBySocketId[socket.id]) {

            var accessToken = state.accessTokensBySocketId[socket.id];

            for (socketId in state.accessTokens[state.accessTokensBySocketId[socket.id]].sockets) {
                var clientSocket = state.accessTokens[state.accessTokensBySocketId[socket.id]].sockets[socketId];
                clientSocket.emit('loggedOut');

                delete state.accessTokensBySocketId[clientSocket.id];
            }

            delete state.accessTokens[accessToken];
        }

        debug(socket, 'logout');
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

    socket.on('getPlayersOnline', function (message) {

        var playersOnline = {};
        for (var playerName in playersByName) {
            playersOnline[playersByName[playerName].getId()] = playerDTO(playersByName[playerName]);
        }

        socket.emit('playersOnlineList', {'playersOnline': playersOnline});

        debug(socket, 'playersOnlineList');
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


function generateAccessToken() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 5; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}