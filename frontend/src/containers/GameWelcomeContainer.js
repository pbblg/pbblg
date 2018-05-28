import React from 'react';
import {connect} from 'react-redux';
import {requestLogout, createNewGameAction} from '../actions/index';

import OpenGamesListContainer from '../containers/OpenGamesListContainer';
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
        const currentPlayer = this.props.currentPlayer;
        const games = this.props.games;

        return (
            <div className="game-welcome">
                <div className="row d-flex justify-content-end">
                    <div className="col-md-3 game-welcome-menu">
                        <p className="game-welcome-menu-player-name d-flex justify-content-end align-items-center">
                            You are: <b>{currentPlayer.name}</b>
                            <button onClick={this.handleOnExitClick} className="game-welcome-menu-exit-button btn btn-sm ml-2">
                                Logout
                                <i className="fa fa-sign-out ml-1"></i>
                            </button>
                        </p>
                    </div>
                </div>

                <div className="row">
                    <div className="col-md-4 order-md-1 offset-2">
                        <h4 className="d-flex justify-content-between align-items-center mb-3">
                            <span className="text-muted">Online players</span>
                            <span className="badge badge-secondary badge-pill">1</span>
                        </h4>
                        <PlayersOnlineListContainer />
                    </div>
                    <div className="col-md-4 order-md-2 mb-4">
                        <h4 className="d-flex justify-content-between align-items-center mb-3">
                            <span className="text-muted pull-left">Open games</span>
                            <div>
                                <NewGameButtonComponent onClick={this.handleOnNewGameButtonClick} />
                                <span className="badge badge-secondary badge-pill">{Object.keys(games).length}</span>
                            </div>
                        </h4>
                        <OpenGamesListContainer />
                    </div>
                </div>
            </div>
        )
    }
}

export default connect(state => state)(GameWelcomeContainer);
