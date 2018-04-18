var cookie = require('cookie');
var persistData = require('../persistData');
var DTO = require('../DTO');

module.exports = function(state) {

    function generateAccessToken() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function login(socket, login, password) {
        var loggedPlayer = false;
        var newAccessToken = false;

        for (var playerId in persistData.players) {
            var player = persistData.players[playerId];

            if (player.login == login && player.password == password) {
                newAccessToken = generateAccessToken();

                state.accessTokensBySocketId[socket.id] = newAccessToken;

                state.accessTokens[newAccessToken] = {
                    playerId: player.id,
                    sockets: {}
                };
                state.accessTokens[newAccessToken].sockets[socket.id] = socket;

                loggedPlayer = player;
                break;
            }
        }

        if (newAccessToken) {
            socket.emit('loginSuccess', {
                accessToken: newAccessToken,
                player: DTO.playerDTO(loggedPlayer),
            });
        } else {
            socket.emit('loginFail', {
                error: 'Wrong login or password'
            });
        }
    }

    function logout(socket) {
        if (state.accessTokensBySocketId[socket.id]) {

            var accessToken = state.accessTokensBySocketId[socket.id];

            for (var socketId in state.accessTokens[state.accessTokensBySocketId[socket.id]].sockets) {
                var clientSocket = state.accessTokens[state.accessTokensBySocketId[socket.id]].sockets[socketId];
                clientSocket.emit('loggedOut');

                delete state.accessTokensBySocketId[clientSocket.id];
            }

            delete state.accessTokens[accessToken];
        }
    }

    function disconnect(socket) {
        if (state.accessTokensBySocketId[socket.id]) {
            delete state.accessTokens[state.accessTokensBySocketId[socket.id]].sockets[socket.id];
            delete state.accessTokensBySocketId[socket.id];
        }
    }

    function authenticate(socket) {
        if (socket.request.headers.cookie) {
            var cookies = cookie.parse(socket.request.headers.cookie);
            if (cookies.access_token) {
                if (state.accessTokens[cookies.access_token]) {
                    var player = persistData.players[state.accessTokens[cookies.access_token].playerId];

                    state.accessTokensBySocketId[socket.id] = cookies.access_token;
                    state.accessTokens[cookies.access_token].sockets[socket.id] = socket;

                    socket.emit('authenticated', DTO.playerDTO(player));
                }
            }
        }
    }

    function getOnlinePlayers() {
        var onlinePlayers = {};

        for (var socketId in state.accessTokensBySocketId) {
            var accessToken = state.accessTokensBySocketId[socketId];
            var playerId = state.accessTokens[accessToken].playerId;

            if (!onlinePlayers[playerId]) {
                onlinePlayers[playerId] = Object.assign({}, persistData.players[playerId]);
            }
        }

        return onlinePlayers;
    }

    return {
        login: login,
        logout: logout,
        disconnect: disconnect,
        authenticate: authenticate,
        getOnlinePlayers: getOnlinePlayers,
    }
};