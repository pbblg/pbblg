import {NEW_GAME_WAS_CREATED} from "../actions/index";
import cookies from 'js-cookie';

const initialState = {
    auth: cookies.get('auth'),
    games: []
};

const app = (state = initialState, action) => {
    switch (action.type) {
        case NEW_GAME_WAS_CREATED:
            return Object.assign({}, state, {
                games: [
                    ...state.games,
                    action.game
                ]
            });
        case 'JOIN_GAME':
            return state
        case 'RECEIVE_GAME_WELCOME_STATE':
            return Object.assign({}, state, {
                games: action.data.gamesForJoin
            });
        case 'LOGIN_PLAYER':
            cookies.set('auth', action.playerName)
            return Object.assign({}, state, {
                auth: action.playerName
            });
        case 'LOGOUT_PLAYER':
            cookies.remove('auth')
            return Object.assign({}, state, {
                auth: null
            });
        default:
            return state
    }
};

export default app