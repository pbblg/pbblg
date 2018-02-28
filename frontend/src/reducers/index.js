import {NEW_GAME_WAS_CREATED, PLAYER_JOINED_GAME} from "../actions/index";
import cookies from 'js-cookie';

const initialState = {
    auth: cookies.get('auth'),
    games: {}
};

const app = (state = initialState, action) => {
    switch (action.type) {
        case NEW_GAME_WAS_CREATED:
            return (() => {
                let newState = Object.assign({}, state);
                newState.games[action.game.id] = action.game;
                return newState;
            })();

        case PLAYER_JOINED_GAME:

            return (() => {
                let newState = Object.assign({}, state);
                newState.games[action.game.id] = action.game;
                return newState;
            })();

        // case 'JOIN_GAME':
        //     return state

        case 'RECEIVE_GAME_WELCOME_STATE':
            return (() => {
                let newState = Object.assign({}, state);
                newState.games = action.data.gamesForJoin;
                return newState;
            })();

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