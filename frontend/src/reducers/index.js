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
                    action.game
                ]
            });
        case 'JOIN_GAME':
            return state
        case 'RECEIVE_GAME_WELCOME_STATE':
            return Object.assign({}, state, {
                games: action.data.gamesForJoin
            });
        default:
            return state
    }
};

export default app