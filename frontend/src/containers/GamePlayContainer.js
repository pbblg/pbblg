import React from 'react';
import ExitGameButtonComponent from '../components/ExitGameButtonComponent';
import {exitGame} from "../actions";
import {connect} from "react-redux";

class GamePlayContainer extends React.Component {

    constructor(props) {
        super(props)

        this.handleOnExitGameClick = this.handleOnExitGameClick.bind(this)
    }

    handleOnExitGameClick() {
        this.props.dispatch(exitGame())
    }

    render() {

        return (
            <div className="game-play">
                <h3>Game play {this.props.match.params.gameId}</h3>
                <ExitGameButtonComponent onClick={this.handleOnExitGameClick} />
            </div>
        )
    }
}

export default connect(state => state)(GamePlayContainer);
