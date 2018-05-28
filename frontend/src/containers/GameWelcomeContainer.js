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
                <div className="row">
                    <div className="col-md-2 offset-10 game-welcome-menu">
                        <p className="game-welcome-menu-player-name">
                            You are: <b>{currentPlayer.name}</b>
                            <button onClick={this.handleOnExitClick} className="game-welcome-menu-exit-button btn btn-sm ml-2">
                                Logout
                                <i className="fa fa-sign-out ml-1"></i>
                            </button>
                        </p>
                    </div>
                </div>

                <div className="row">
                    <div className="col-md-3 order-md-1 offset-3">
                        <h4 className="d-flex justify-content-between align-items-center mb-3">
                            <span className="text-muted">Online players</span>
                            <span className="badge badge-secondary badge-pill">1</span>
                        </h4>
                        <PlayersOnlineListContainer />
                    </div>
                    <div className="col-md-3 order-md-2 mb-4">
                        <h4 className="d-flex justify-content-between align-items-center mb-3">
                            <span className="text-muted pull-left">Open games</span>
                            <div>
                                <NewGameButtonComponent onClick={this.handleOnNewGameButtonClick} />
                                <span className="badge badge-secondary badge-pill">0</span>
                            </div>
                        </h4>
                        <JoinGamesListContainer />
                    </div>
                </div>
            </div>
        )
    }
}

export default connect(state => state)(GameWelcomeContainer);
