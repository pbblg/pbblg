import {START_CREATE_NEW_GAME, REQUEST_GAME_WELCOME_STATE, JOIN_GAME, EXIT_GAME} from "../actions/index";

export default socket => store => next => action => {
    if (action.type === START_CREATE_NEW_GAME) {
        socket.emit('newGame');
    }
    if (action.type === REQUEST_GAME_WELCOME_STATE) {
        socket.emit('getGameWelcomeState');
    }
    if (action.type === JOIN_GAME) {
        socket.emit('joinGame', {gameId: action.gameId});
    }
    if (action.type === EXIT_GAME) {
        socket.emit('exitGame');
    }
    return next(action);
}




