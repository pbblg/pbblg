import {
    NEW_GAME_WAS_CREATED,
    OTHER_PLAYER_JOINED_GAME,
    CURRENT_PLAYER_JOINED_GAME,
    RECEIVE_GAME_STATE,
    RECEIVE_EXIT_GAME,
    RECEIVE_OTHER_PLAYER_EXIT_GAME,
    RECEIVE_PLAYERS_ONLINE_LIST,
    PLAYER_AUTHENTICATED,
    RECEIVE_LOGIN_FAIL,
    RECEIVE_LOGIN_SUCCESS,
    RECEIVE_LOGOUT,
    // LOGOUT_PLAYER,
    // LOGOUT_PLAYER,
} from "../actions/index";
import cookies from 'js-cookie';

const initialState = {
    //auth: cookies.get('access_token'),
    games: {},
    playersOnline: {},
    gamePlay: null,
    isGameStateLoaded: false,

    isAuthenticated: false,
    currentPlayer: null,
    loginError: null,
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

        case RECEIVE_PLAYERS_ONLINE_LIST:
            return Object.assign({}, state, {
                playersOnline: Object.assign({}, state.playersOnline, action.playersOnline)
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

        case PLAYER_AUTHENTICATED:

            return Object.assign({}, state, {
                isAuthenticated: true,
                currentPlayer: action.player,
            });

        case RECEIVE_LOGIN_FAIL:

            return Object.assign({}, state, {
                loginError: action.error
            });

        case RECEIVE_LOGIN_SUCCESS:
            cookies.set('access_token', action.accessToken)

            return Object.assign({}, state, {
                isAuthenticated: true,
                currentPlayer: action.player,
            });

        case RECEIVE_LOGOUT:
            cookies.remove('access_token')

            return Object.assign({}, state, {
                isAuthenticated: false,
                currentPlayer: null,
            });
        default:
            return state
    }
};

export default app