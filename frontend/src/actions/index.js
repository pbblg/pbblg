export const START_CREATE_NEW_GAME = 'START_CREATE_NEW_GAME';
export const NEW_GAME_WAS_CREATED = 'NEW_GAME_WAS_CREATED';

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
export const joinGame = (gameId) => (
    {
        type: 'JOIN_GAME',
        gameId
    }
);