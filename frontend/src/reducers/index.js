import {NEW_GAME_WAS_CREATED} from "../actions/index";

const initialState = {
    games: []
};

const app = (state = initialState, action) => {
    switch (action.type) {
        case NEW_GAME_WAS_CREATED:
            return Object.assign({}, state, {
                games: [
                    ...state.games,
                    {
                        id: action.gameId,
                    }
                ]
            });
        case 'JOIN_GAME':
            return state
        default:
            return state
    }
};

export default app