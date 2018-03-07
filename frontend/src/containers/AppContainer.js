import React from 'react';
import GameWelcomeContainer from './GameWelcomeContainer';
import GamePlayContainer from './GamePlayContainer';
import LoginContainer from './LoginContainer';
import {connect} from "react-redux";
import {requestGameState} from "../actions";

class AppContainer extends React.Component {

    componentDidMount() {
        console.log('AppContainer');
        this.props.dispatch(requestGameState())
    }

    render() {
        const auth = this.props.auth
        const gamePlayId = this.props.gamePlayId
        const isGameStateLoaded = this.props.isGameStateLoaded

        if (!isGameStateLoaded) {
            return (
                <div className="game-welcome">
                    <p>Game state loading...</p>
                </div>
            );
        }

        if (auth) {
            if (gamePlayId) {
                return (
                    <GamePlayContainer gamePlayId={gamePlayId}/>
                );
            }

            return (
                <GameWelcomeContainer/>
            );
        }

        return (
            <LoginContainer/>
        );
    }
}

export default connect(state => state)(AppContainer);
