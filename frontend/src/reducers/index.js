import {
    NEW_GAME_WAS_CREATED,
    OTHER_PLAYER_JOINED_GAME,
    PLAYER_JOINED_GAME,
    RECEIVE_GAME_STATE,
    RECEIVE_EXIT_GAME,
    RECEIVE_OTHER_PLAYER_EXIT_GAME,
    RECEIVE_PLAYERS_ONLINE_LIST,
    PLAYER_AUTHENTICATED,
    RECEIVE_LOGOUT,
    RECEIVE_LOGIN
} from "../actions/index";
import {GAME_WAS_REMOVED, REQUEST_EXIT_GAME} from "../actions";

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
                games: Object.assign({}, state.games, newGames),
            });

        case PLAYER_JOINED_GAME:
            let player = action.player;
            let game = action.game;

            if (state.currentPlayer.id === player.id) {
                return Object.assign({}, state, {
                    gamePlay: { gameId: game.id }
                });
            }

            return state;

        case GAME_WAS_REMOVED:
            delete state.games[action.game.id];

            return Object.assign({}, state, {
                games: state.games
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
                games: Object.assign({}, {}, action.data)
            });

        case PLAYER_AUTHENTICATED:
            action.player.id = parseInt(action.player.id, 10);
            return Object.assign({}, state, {
                isAuthenticated: true,
                currentPlayer: action.player,
            });

        case RECEIVE_LOGOUT:
            let loggedOutUserId = action.user.id;
            delete state.playersOnline[loggedOutUserId];
            return Object.assign(
                {},
                state,
                {
                    playersOnline: Object.assign({}, state.playersOnline)
                }
            );

        case RECEIVE_LOGIN:
            let loggedInUserId = action.user.id;
            state.playersOnline[loggedInUserId] = action.user;
            return Object.assign(
                {},
                state,
                {
                    playersOnline: Object.assign({}, state.playersOnline)
                }
            );

        case REQUEST_EXIT_GAME:
            return Object.assign({}, state, {
                gamePlay: null
            });

        default:
            return state
    }
};

export default app