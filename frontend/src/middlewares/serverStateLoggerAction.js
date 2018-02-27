import {DEBUG_SERVER_STATE} from "../actions/index";

export default store => next => action => {

    if (action.type === DEBUG_SERVER_STATE) {
        console.group(DEBUG_SERVER_STATE);
            let gamesCount = 0;
            let gamesIDs = [];
            let playersCount = 0;
            let playersIDs = [];


            for (var pi in action.serverState.players) {
                playersCount++;
                playersIDs.push(action.serverState.players[pi].id);
            }
            for (var gi in action.serverState.games) {
                gamesCount++;
                gamesIDs.push(action.serverState.games[gi].id);
            }

            console.log('playersCount', playersCount);
            console.log('gamesCount', gamesCount);
            console.log('gamesIDs', gamesIDs);
            console.log('playersIDs', playersIDs);
            console.log('games', action.serverState.games);
            console.log('players', action.serverState.players);
        console.groupEnd();
    }

    return next(action)
}




