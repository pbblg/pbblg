export const START_CREATE_NEW_GAME = 'START_CREATE_NEW_GAME';
export const NEW_GAME_WAS_CREATED = 'NEW_GAME_WAS_CREATED';
export const RECEIVE_GAME_WELCOME_STATE = 'RECEIVE_GAME_WELCOME_STATE';
export const REQUEST_GAME_WELCOME_STATE = 'REQUEST_GAME_WELCOME_STATE';

export const createNewGameAction = () => (
    {
        type: START_CREATE_NEW_GAME
    }
);
export const newGameWasCreatedAction = (gameId) => (
    {
        type: NEW_GAME_WAS_CREATED,
        gameId
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
export const joinGame = (gameId) => (
    {
        type: 'JOIN_GAME',
        gameId
    }
);