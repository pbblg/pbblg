import React from 'react';
import GameWelcomeContainer from './GameWelcomeContainer';
import GamePlayContainer from './GamePlayContainer';
import LoginContainer from './LoginContainer';
import {connect} from "react-redux";
import {requestGameState} from "../actions";

class AppContainer extends React.Component {

    componentDidMount() {
        if (this.props.isAuthenticated) {
            this.props.dispatch(requestGameState())
        }
    }

    render() {
        const isAuthenticated = this.props.isAuthenticated;

        //const isGameStateLoaded = this.props.isGameStateLoaded

        // if (!isGameStateLoaded) {
        //     return (
        //         <div className="game-welcome">
        //             <p>Game state loading...</p>
        //         </div>
        //     );
        // }

        if (isAuthenticated) {
            const gamePlay = this.props.gamePlay;
            if (gamePlay) {
                return (
                    <GamePlayContainer gamePlay={gamePlay}/>
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
