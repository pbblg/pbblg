import React, { Component } from 'react';
import CreateNewGameContainer from '../containers/CreateNewGameContainer';
import JoinGamesListContainer from '../containers/JoinGamesListContainer';

class GameWelcome extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="game-welcome">
                <h3>Start new game</h3>
                <CreateNewGameContainer />
                <h3>or join</h3>
                <JoinGamesListContainer />
            </div>
        );
    }
}

export default GameWelcome;
