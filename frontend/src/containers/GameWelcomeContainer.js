import React from 'react';
import {connect} from 'react-redux';
import {loginPlayer, logoutPlayer, createNewGameAction} from '../actions/index';

import JoinGamesListContainer from '../containers/JoinGamesListContainer';

import NewGameButtonComponent from '../components/NewGameButtonComponent';


class GameWelcomeContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {value: '', error: ''};

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleOnExitClick = this.handleOnExitClick.bind(this);
        this.handleOnNewGameButtonClick = this.handleOnNewGameButtonClick.bind(this);
    }

    handleChange(event) {
        this.setState({value: event.target.value});
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.value.length < 3) {
            this.setState({error: 'Name must be at least 3 characters'})
        } else {
            this.setState({error: ''})
        }

        this.props.dispatch(loginPlayer(this.state.value))
    }

    handleOnExitClick(event) {
        this.props.dispatch(logoutPlayer())
    }

    handleOnNewGameButtonClick(event) {
        this.props.dispatch(createNewGameAction())
    }

    render() {
        const auth = this.props.auth

        if (auth) {
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
            );
        }

        return (
            <div className="game-welcome">
               <h1>Enter your name:</h1>
                <form onSubmit={this.handleSubmit}>
                    <input type="text" value={this.state.value} onChange={this.handleChange} />
                    <p>{this.state.error}</p>
                    <br/>
                    <input type="submit" value="Submit" />
                </form>
            </div>
        );
    }
}



export default connect(state => state)(GameWelcomeContainer);
