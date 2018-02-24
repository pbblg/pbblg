import React from 'react';
import {connect} from 'react-redux';
import {logoutPlayer, createNewGameAction} from '../actions/index';

import JoinGamesListContainer from '../containers/JoinGamesListContainer';
import NewGameButtonComponent from '../components/NewGameButtonComponent';


class GameWelcomeContainer extends React.Component {

    constructor(props) {
        super(props);

        this.handleOnExitClick = this.handleOnExitClick.bind(this);
        this.handleOnNewGameButtonClick = this.handleOnNewGameButtonClick.bind(this);
    }

    handleOnExitClick(event) {
        this.props.dispatch(logoutPlayer())
    }

    handleOnNewGameButtonClick(event) {
        this.props.dispatch(createNewGameAction())
    }

    render() {
        const auth = this.props.auth

        return (
            <div>
                <div className="game-menu">
                    <p>You are: <b>{auth}</b></p>
                    <button onClick={this.handleOnExitClick} className="button">Exit</button>
                </div>
                <div className="game-welcome">
                    <h3>Start new game</h3>
                    <NewGameButtonComponent onClick={this.handleOnNewGameButtonClick} />
                    <h3>or join</h3>
                    <JoinGamesListContainer />
                </div>
            </div>
        )
    }
}

export default connect(state => state)(GameWelcomeContainer);
