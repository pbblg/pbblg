import {START_CREATE_NEW_GAME, REQUEST_GAME_WELCOME_STATE, CURRENT_PLAYER_REQUEST_JOIN_GAME, EXIT_GAME, LOGIN_PLAYER, SOCKET_CONNECTED} from "../actions/index";

export default socket => store => next => action => {
    if (action.type === START_CREATE_NEW_GAME) {
        socket.emit('newGame');
    }
    if (action.type === REQUEST_GAME_WELCOME_STATE) {
        socket.emit('getGameWelcomeState');
    }
    if (action.type === CURRENT_PLAYER_REQUEST_JOIN_GAME) {
        socket.emit('joinGame', {gameId: action.gameId});
    }
    if (action.type === EXIT_GAME) {
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




