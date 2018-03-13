import React from 'react';
import ExitGameButtonComponent from '../components/ExitGameButtonComponent';
import {requestExitGame} from "../actions";
import {connect} from "react-redux";

class GamePlayContainer extends React.Component {

    constructor(props) {
        super(props)

        this.handleOnExitGameClick = this.handleOnExitGameClick.bind(this)
    }

    handleOnExitGameClick() {
        this.props.dispatch(requestExitGame())
    }

    render() {

        return (
            <div>
                <div className="game-menu">
                    <ExitGameButtonComponent onClick={this.handleOnExitGameClick} />
                </div>
                <div className="game-play">
                    <h3>Game play {this.props.gamePlay.gameId}</h3>
                </div>
            </div>
        )
    }
}

export default connect(state => state)(GamePlayContainer);
