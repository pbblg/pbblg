import {
    REQUEST_LOGOUT,

    START_CREATE_NEW_GAME,
    REQUEST_GAME_STATE,
    REQUEST_JOIN_GAMES_LIST,
    REQUEST_PLAYERS_ONLINE_LIST,
    REQUEST_EXIT_GAME,
    CURRENT_PLAYER_REQUEST_JOIN_GAME,
    LOGIN_PLAYER,
    SOCKET_CONNECTED,
    receiveJoinGamesList,
    joinedGame,
    receivePlayersOnlineList
} from "../actions/index";

export default socket => store => next => action => {
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
        socket.emit('getGames', {}, function (data) {
            store.dispatch(receiveJoinGamesList(data))
        });
    }
    if (action.type === REQUEST_PLAYERS_ONLINE_LIST) {
        socket.emit('getOnlineUsers', {}, function (data) {
            store.dispatch(receivePlayersOnlineList(data))
        });
    }
    if (action.type === CURRENT_PLAYER_REQUEST_JOIN_GAME) {
        socket.emit('joinGame', {gameId: action.gameId}, function (data) {
            store.dispatch(joinedGame(data))
        });
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




