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
var lastPlayerId = 0;


io.sockets.on('connection', function (socket) {

    socket.on('getGameWelcomeState', function (message) {
        console.log('getGameWelcomeState');

        var gamesForJoin = [];
        for (var gameId in games) {
            if (games[gameId].canJoin()) {
                gamesForJoin.push(new GameDTO(games[gameId]));
            }
        }

        socket.emit('gameWelcomeState', {'gamesForJoin': gamesForJoin});
    });


    socket.on('newGame', function (message) {
        console.log('newGame');

        var player = new Player(socket);
        players[socket.id] = player;

        var game = new Game();
        game.joinPlayer(player);
        games[game.getId()] = game;

        socket.broadcast.emit('newGame', new GameDTO(game));
        socket.emit('newGame', new GameDTO(game));
    });



    socket.on('joinGame', function (message) {
        console.log('joinGame');

        var player = players[socket.id];
        console.log(player);
        if (player) {
            var game = games[message.gameId];
            game.joinPlayer(player);
            game.forAllPlayers(function(player) {
                player.send('playerJoinGame');
            });
        }
    });

    socket.on('exitGame', function (message) {

        console.log('exitGame');

        var player = players[socket.id];

        var game = new Game();
        game.exitPlayer(player);
        game.forAllPlayers(function(player) {
            player.send('playerExitGame');
        });
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

function Player(socket) {
    var id = ++lastPlayerId;

    this.getId = function() {
        return id;
    }

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
    }
}



