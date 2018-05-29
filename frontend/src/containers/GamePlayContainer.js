import React from 'react';
import {requestExitGame, requestLogout} from "../actions";
import {connect} from "react-redux";

class GamePlayContainer extends React.Component {

    constructor(props) {
        super(props)

        this.handleOnExitGameClick = this.handleOnExitGameClick.bind(this)
    }

    handleOnExitClick(event) {
        this.props.dispatch(requestLogout())
    }

    handleOnExitGameClick() {
        this.props.dispatch(requestExitGame())
    }

    render() {
        const currentPlayer = this.props.currentPlayer;

        return (
            <div className="container-fluid m-1 game-table">
                <div className="row d-flex justify-content-end">
                    <div className="col-md-5 game-welcome-menu">
                        <div className="game-welcome-menu-player-name d-flex justify-content-end align-items-center">
                            <div className="mr-2">Game #{this.props.gamePlay.gameId}</div>|
                            You are: <b>{currentPlayer.name}</b>
                            <button onClick={this.handleOnExitGameClick} className="game-welcome-menu-exit-button btn btn-sm ml-2">
                                Exit game
                                <i className="fa fa-sign-out ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div className="d-flex flex-row justify-content-center mb-5">
                    <div className="col-md-1 mt-3">
                        <div className="avatar d-flex justify-content-center align-items-center">
                            <span>some</span>
                        </div>
                    </div>
                    <div className="col-md-2">
                        <div className="card-contour">
                            <div className="card-shirt"></div>
                        </div>
                    </div>

                    <div className="col-md-1 mt-3 offset-1">
                        <div className="avatar d-flex justify-content-center align-items-center">
                            <span>some</span>
                        </div>
                    </div>
                    <div className="col-md-2">
                        <div className="card-contour">
                            <div className="card-shirt"></div>
                        </div>
                    </div>
                </div>
                <div className="d-flex flex-row justify-content-center mb-5 mt-5">
                    <div className="col-md-1 mt-3">
                        <div className="avatar d-flex justify-content-center align-items-center">
                            <span>some</span>
                        </div>
                    </div>
                    <div className="col-md-2">
                        <div className="card-contour">
                            <div className="card-shirt"></div>
                        </div>
                    </div>

                    <div className="col-md-2 offset-1">
                        <div className="card">
                            <div className="card-shirt"></div>
                        </div>
                    </div>
                    <div className="col-md-2">
                        <div className="card-contour">
                            <div className="card-shirt"></div>
                        </div>
                    </div>

                    <div className="col-md-2 offset-1">
                        <div className="card-contour">
                            <div className="card-shirt"></div>
                        </div>
                    </div>
                    <div className="col-md-1 mt-3">
                        <div className="avatar d-flex justify-content-center align-items-center">
                            <span>some</span>
                        </div>
                    </div>
                </div>
                <div className="d-flex flex-row justify-content-center mt-5">
                    <div className="col-md-1 mt-3">
                        <div className="avatar d-flex justify-content-center align-items-center">
                            <span>you</span>
                        </div>
                    </div>
                    <div className="col-md-3">
                        <div className="card-contour">
                            {/*<p>1</p>*/}
                        </div>
                    </div>
                </div>
                {/*<div className="card">*/}
                    {/*<p>1</p>*/}
                {/*</div>*/}
            </div>
        )
    }
}

export default connect(state => state)(GamePlayContainer);
