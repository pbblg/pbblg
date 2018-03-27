import {
    REQUEST_LOGIN,
    REQUEST_LOGOUT,

    START_CREATE_NEW_GAME,
    REQUEST_GAME_STATE,
    REQUEST_JOIN_GAMES_LIST,
    REQUEST_PLAYERS_ONLINE_LIST,
    REQUEST_EXIT_GAME,
    CURRENT_PLAYER_REQUEST_JOIN_GAME,
    LOGIN_PLAYER,
    SOCKET_CONNECTED
} from "../actions/index";

export default socket => store => next => action => {
    if (action.type === REQUEST_LOGIN) {
        socket.emit('login', {login: action.login, password: action.password});
    }
    if (action.type === REQUEST_LOGOUT) {
        socket.emit('logout');
    }
    if (action.type === START_CREATE_NEW_GAME) {
        socket.emit('newGame');
    }
    if (action.type === REQUEST_GAME_STATE) {
        socket.emit('getGameState');
    }
    if (action.type === REQUEST_JOIN_GAMES_LIST) {
        socket.emit('getGameWelcomeState');
    }
    if (action.type === REQUEST_PLAYERS_ONLINE_LIST) {
        socket.emit('getPlayersOnline');
    }
    if (action.type === CURRENT_PLAYER_REQUEST_JOIN_GAME) {
        socket.emit('joinGame', {gameId: action.gameId});
    }
    if (action.type === REQUEST_EXIT_GAME) {
        socket.emit('exitGame');
    }
    if (action.type === LOGIN_PLAYER) {
        socket.emit('authenticate', {playerName: action.playerName});
    }
    if (action.type === SOCKET_CONNECTED) {
        if (store.getState().auth) {
            socket.emit('authenticate', {playerName: store.getState().auth});
        }
    }
    return next(action);
}




