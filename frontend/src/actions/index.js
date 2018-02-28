export const START_CREATE_NEW_GAME = 'START_CREATE_NEW_GAME';
export const NEW_GAME_WAS_CREATED = 'NEW_GAME_WAS_CREATED';
export const RECEIVE_GAME_WELCOME_STATE = 'RECEIVE_GAME_WELCOME_STATE';
export const REQUEST_GAME_WELCOME_STATE = 'REQUEST_GAME_WELCOME_STATE';
export const CURRENT_PLAYER_REQUEST_JOIN_GAME = 'CURRENT_PLAYER_REQUEST_JOIN_GAME';
export const CURRENT_PLAYER_JOINED_GAME = 'CURRENT_PLAYER_JOINED_GAME';
export const EXIT_GAME = 'EXIT_GAME';
export const LOGIN_PLAYER = 'LOGIN_PLAYER';
export const LOGOUT_PLAYER = 'LOGOUT_PLAYER';
export const SOCKET_CONNECTED = 'SOCKET_CONNECTED';
export const DEBUG_SERVER_STATE = 'DEBUG_SERVER_STATE';
export const OTHER_PLAYER_JOINED_GAME = 'OTHER_PLAYER_JOINED_GAME';

export const createNewGameAction = () => (
    {
        type: START_CREATE_NEW_GAME
    }
);
export const newGameWasCreatedAction = (game) => (
    {
        type: NEW_GAME_WAS_CREATED,
        game
    }
);
export const requestGameWelcomeState = () => (
    {
        type: REQUEST_GAME_WELCOME_STATE
    }
);
export const receiveGameWelcomeState = (data) => (
    {
        type: RECEIVE_GAME_WELCOME_STATE,
        data
    }
);
export const currentPlayerRequestJoinGame = (gameId) => (
    {
        type: CURRENT_PLAYER_REQUEST_JOIN_GAME,
        gameId
    }
);
export const currentPlayerJoinedGame = (gameId) => (
    {
        type: CURRENT_PLAYER_JOINED_GAME,
        gameId
    }
);
export const exitGame = () => (
    {
        type: EXIT_GAME,
    }
);
export const loginPlayer = (playerName) => (
    {
        type: LOGIN_PLAYER,
        playerName
    }
)
export const logoutPlayer = () => (
    {
        type: LOGOUT_PLAYER
    }
)
export const socketConnectedAction = () => (
    {
        type: SOCKET_CONNECTED
    }
)
export const debugServerState = (serverState) => (
    {
        type: DEBUG_SERVER_STATE,
        serverState
    }
)
export const otherPlayerJoinedGame = (player) => (
    {
        type: OTHER_PLAYER_JOINED_GAME,
        player
    }
)