import {START_CREATE_NEW_GAME} from "../actions/index";

export default socket => store => next => action => {
    if (action.type === START_CREATE_NEW_GAME) {
        socket.emit('newGame');
    }
    return next(action);
}




