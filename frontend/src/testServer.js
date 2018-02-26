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

var games = [];
var lastGameId = 0;
var players = {};
var playersSockets = {};
var lastPlayerId = 0;


io.sockets.on('connection', function (socket) {

    socket.on('disconnect', function () {
        //delete players[socket.id];

        debug('disconnect');
    });

    socket.on('authenticate', function (message) {

        var player = new Player(message.playerName);
        players[message.playerName] = player;

        playersSockets[message.playerName] = new PlayerSocket(socket);

        debug('authenticate');
    });

    socket.on('getGameWelcomeState', function (message) {

        var gamesForJoin = [];
        for (var gameId in games) {
            if (games[gameId].canJoin()) {
                gamesForJoin.push(new GameDTO(games[gameId]));
            }
        }

        socket.emit('gameWelcomeState', {'gamesForJoin': gamesForJoin});

        debug('getGameWelcomeState');
    });


    socket.on('newGame', function (message) {

        //var player = players[socket.id];
        //console.log('player ' + player.getId());

        var game = new Game();
        //game.joinPlayer(player);
        games[game.getId()] = game;

        socket.broadcast.emit('newGame', new GameDTO(game));
        socket.emit('newGame', new GameDTO(game));

        debug('newGame');
    });



    socket.on('joinGame', function (message) {

        var player = players[socket.id];
        if (player) {
            var game = games[message.gameId];
            game.joinPlayer(player);
            game.forAllPlayers(function(player) {
                player.send('playerJoinGame');
            });
        }

        debug('joinGame');
    });

    socket.on('exitGame', function (message) {

        var player = players[socket.id];

        var game = new Game();
        game.exitPlayer(player);
        game.forAllPlayers(function(player) {
            player.send('playerExitGame');
        });

        debug('exitGame')
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
}
function PlayerSocket(socket) {
    this.send = function(event) {
        socket.emit(event, new PlayerDTO(this));
    }
}

function GameDTO(game) {
    return {
        id: game.getId(),
        countFreePlaces: game.getCountFreePlaces()
    }
}

function PlayerDTO(player) {
    return {
        id: player.getId(),
        name: player.getName(),
    }
}

function debug(event) {
    var countGames = games.length;
    var countPlayers = 0;
    for (var socketId in players) {
        countPlayers++;
    }


    console.log('------------------------------------------');
    console.log('event - ' + event);
    console.log('count games - ' + countPlayers);
    console.log('count players - ' + countGames);
    for (var gi in games) {
        console.log('game #' + games[gi].getId() + ' ' + games[gi].getCountPlayers() + ' players');
    }
}


