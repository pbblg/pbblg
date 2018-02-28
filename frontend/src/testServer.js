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

        //var player = playersByName[socket.id];
        //console.log('player ' + player.getId());

        var game = new Game();
        //game.joinPlayer(player);
        games[game.getId()] = game;

        socket.broadcast.emit('newGame', gameDTO(game));
        socket.emit('newGame', gameDTO(game));

        debug(socket, 'newGame');
    });



    socket.on('joinGame', function (message) {

        debug(socket, 'joinGame-pre', message);

        var player = playersBySocketId[socket.id];
        console.log(player);
        if (player) {
            var game = games[message.gameId];
            game.joinPlayer(player);
            game.forAllPlayers(function(player) {
                player.socket.emit('playerJoinGame', {
                    player: playerDTO(player),
                    game: gameDTO(game)
                });
            });
        }

        debug(socket, 'joinGame');
    });

    socket.on('exitGame', function (message) {

        var player = playersByName[socket.id];

        var game = new Game();
        game.exitPlayer(player);
        game.forAllPlayers(function(player) {
            player.send('playerExitGame');
        });

        debug(socket, 'exitGame')
    });

    socket.on('startGame', function (message) {

        console.log('startGame');

        // var game = new Game();
        // game.createDeck();
        // var card = game.popCardFromDeck();
    });
});


function Game() {

    var id = ++lastGameId;
    var players = {};
    var countMaxPlayers = 5;
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
            return players[player.getId()] = player;
        }
        return false;
    }

    this.exitPlayer = function(player) {
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

    this.socket;
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


