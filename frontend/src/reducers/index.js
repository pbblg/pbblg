import {
    NEW_GAME_WAS_CREATED,
    OTHER_PLAYER_JOINED_GAME,
    CURRENT_PLAYER_JOINED_GAME,
    RECEIVE_GAME_STATE,
    RECEIVE_EXIT_GAME
} from "../actions/index";
import cookies from 'js-cookie';

const initialState = {
    auth: cookies.get('auth'),
    games: {},
    gamePlayId: null,
};

const app = (state = initialState, action) => {
    switch (action.type) {

        case NEW_GAME_WAS_CREATED:
            let newGames = {};
            newGames[action.game.id] = action.game;
            return Object.assign({}, state, {
                games: Object.assign({}, state.games, newGames)
            });

        case OTHER_PLAYER_JOINED_GAME:

            return state;

        case CURRENT_PLAYER_JOINED_GAME:
            return Object.assign({}, state, {
                gamePlayId: action.gameId
            });

        case RECEIVE_GAME_STATE:
            return Object.assign({}, state, {
                gamePlayId: action.data.gamePlayId
            });

        case RECEIVE_EXIT_GAME:
            return Object.assign({}, state, {
                gamePlayId: null
            });

        case 'RECEIVE_JOIN_GAMES_LIST':

            return Object.assign({}, state, {
                games: Object.assign({}, state.games, action.data.gamesForJoin)
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