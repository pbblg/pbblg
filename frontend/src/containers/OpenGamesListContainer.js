import React from 'react';
import {connect} from 'react-redux';
import OpenGamesListComponent from '../components/OpenGamesListComponent';
import {currentPlayerRequestJoinGame, requestJoinGamesList} from '../actions/index';


class OpenGamesListContainer extends React.Component {

    constructor(props) {
        super(props)

        this.handleOnGameClick = this.handleOnGameClick.bind(this)
    }

    componentDidMount() {
        console.log('OpenGamesListContainer');
        this.props.dispatch(requestJoinGamesList())
    }

    handleOnGameClick(gameId) {
        this.props.dispatch(currentPlayerRequestJoinGame(gameId))
    }

    render() {
        const {games} = this.props;
        console.log(games);

        return (
            <div>
                {Object.keys(games).length === 0 &&
                    <p>No games</p>
                }
                {Object.keys(games).length > 0 &&
                    <OpenGamesListComponent games={games} onGameClick={this.handleOnGameClick}/>
                }
            </div>
        )
    }
}



export default connect(state => state)(OpenGamesListContainer);
