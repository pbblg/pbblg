import { combineReducers } from 'redux'

const initialState = {
    games: []
};

let lasGameId = 0;

const app = (state = initialState, action) => {
    switch (action.type) {
        case 'CREATE_NEW_GAME':
            return Object.assign({}, state, {
                games: [
                    ...state.games,
                    {
                        id: ++lasGameId,
                    }
                ]
            });
        default:
            return state
    }
};

export default app