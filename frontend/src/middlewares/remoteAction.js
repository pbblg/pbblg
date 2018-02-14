import {START_CREATE_NEW_GAME, REQUEST_GAME_WELCOME_STATE} from "../actions/index";

export default socket => store => next => action => {
    if (action.type === START_CREATE_NEW_GAME) {
        socket.emit('newGame');
    }
    if (action.type === REQUEST_GAME_WELCOME_STATE) {
        socket.emit('getGameWelcomeState');
    }
    return next(action);
}




