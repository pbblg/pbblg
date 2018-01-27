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

console.log('start server on posrt ' + PORT);

io.sockets.on('connection', function (client) {

    console.log('connection',client.id.toString());

    client.on('startGame', function (message) {

        console.log('startGame');

        var game = new Game();
        game.createDeck();

        // try {
        //     client.emit('message', message);
        //     client.broadcast.emit('message', message);
        // } catch (e) {
        //     console.log(e);
        //     client.disconnect();
        // }

    });
});


function Game() {

    this.createDeck = function() {
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
}




