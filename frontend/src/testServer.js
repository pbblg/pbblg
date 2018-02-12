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


io.sockets.on('connection', function (client) {


    client.on('newGame', function (message) {
        console.log('newGame');

        var game = new Game();
        game.addPlayer(client);
        games[game.getId()] = game;

        client.broadcast.emit('newGame', {'gameId': game.getId()});
        client.emit('newGame', {'gameId': game.getId()});
    });

    client.on('getGameWelcomeState', function (message) {
        console.log('getGameWelcomeState');

        var gamesForJoin = [];
        for (var gameId in games) {
            if (games[gameId].canJoin()) {
                gamesForJoin.push(gameId);
            }
        }

        client.emit('gameWelcomeState', {'gamesForJoin': gamesForJoin});
    });


    client.on('joinGame', function (message) {

        console.log('joinGame');

        var game = new Game();
        game.joinPlayer(client);
        var card = game.popCardFromDeck();
    });

    client.on('startGame', function (message) {

        console.log('startGame');

        var game = new Game();
        game.createDeck();
        var card = game.popCardFromDeck();
    });
});


function Game() {

    var id = ++lastGameId;
    var players = [];
    var deck = createDeck();

    function createDeck() {
        var deck = [
            1,1,1,1,1,
            2,2,
            3,3,
            4,4,
            5,5,
            6,
            7,
            8
        ];

        function shuffle(a) {
            var j, x, i;
            for (i = a.length - 1; i > 0; i--) {
                j = Math.floor(Math.random() * (i + 1));
                x = a[i];
                a[i] = a[j];
                a[j] = x;
            }
            return a;
        }

        return shuffle(deck);
    }


    this.getId = function() {
        return id;
    }

    this.addPlayer = function(player) {
        return players.push(player);
    }

    this.canJoin = function() {
        return players.length <= 4;
    }
}




