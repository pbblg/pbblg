import {
    NEW_GAME_WAS_CREATED,
    OTHER_PLAYER_JOINED_GAME,
    CURRENT_PLAYER_JOINED_GAME,
    RECEIVE_GAME_STATE,
    RECEIVE_EXIT_GAME,
    RECEIVE_OTHER_PLAYER_EXIT_GAME,
} from "../actions/index";
import cookies from 'js-cookie';

const initialState = {
    auth: cookies.get('auth'),
    games: {},
    gamePlay: null,
    isGameStateLoaded: false,
};

const app = (state = initialState, action) => {
    switch (action.type) {

        case NEW_GAME_WAS_CREATED:
            let newGames = {};
            newGames[action.game.id] = action.game;
            return Object.assign({}, state, {
                games: Object.assign({}, state.games, newGames)
            });

        case CURRENT_PLAYER_JOINED_GAME:
            return Object.assign({}, state, {
                gamePlay: {
                    gameId: action.gameId
                }
            });

        case RECEIVE_GAME_STATE:
            if (action.data.gamePlay) {
                return Object.assign({}, state, {
                    gamePlay: {
                        gameId: action.data.gamePlay.gameId
                    },
                    isGameStateLoaded: true
                });
            }

            return Object.assign({}, state, {
                gamePlay: null,
                isGameStateLoaded: true
            });

        case RECEIVE_EXIT_GAME:
            return Object.assign({}, state, {
                gamePlay: null
            });

        case OTHER_PLAYER_JOINED_GAME:
            return state;

        case RECEIVE_OTHER_PLAYER_EXIT_GAME:

            return Object.assign({}, state, {
                games: Object.assign({}, state.games, Object.assign({}, state.games[action.game.id], action.game.id))
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