import React from 'react';
import {connect} from 'react-redux';
import {requestLogout, createNewGameAction} from '../actions/index';

import JoinGamesListContainer from '../containers/JoinGamesListContainer';
import NewGameButtonComponent from '../components/NewGameButtonComponent';
import PlayersOnlineListContainer from '../containers/PlayersOnlineListContainer';


class GameWelcomeContainer extends React.Component {

    constructor(props) {
        super(props);

        this.handleOnExitClick = this.handleOnExitClick.bind(this);
        this.handleOnNewGameButtonClick = this.handleOnNewGameButtonClick.bind(this);
    }

    handleOnExitClick(event) {
        this.props.dispatch(requestLogout())
    }

    handleOnNewGameButtonClick(event) {
        this.props.dispatch(createNewGameAction())
    }

    render() {
        const currentPlayer = this.props.currentPlayer

        return (
            <div className="game-welcome">
                <div className="game-welcome-menu">
                    <p className="game-welcome-menu-player-name">You are: <b>{currentPlayer.name}</b></p>
                    <button onClick={this.handleOnExitClick} className="game-welcome-menu-exit-button button">Exit</button>
                </div>
                <div className="game-welcome-container">
                    <h3>Start new game</h3>
                    <NewGameButtonComponent onClick={this.handleOnNewGameButtonClick} />
                    <h3>or join</h3>
                    <JoinGamesListContainer />
                </div>
                <div className="game-welcome-online-players">
                    <h2>Online players</h2>
                    <PlayersOnlineListContainer />
                </div>
            </div>
        )
    }
}

export default connect(state => state)(GameWelcomeContainer);
