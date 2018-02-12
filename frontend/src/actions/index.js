export const createNewGame = () => (
    {
        type: 'CREATE_NEW_GAME'
    }
);

export const joinGame = (gameId) => (
    {
        type: 'JOIN_GAME',
        gameId
    }
);