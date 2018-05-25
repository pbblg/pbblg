import React from 'react';
import {connect} from 'react-redux';
import JoinGamesListComponent from '../components/JoinGamesListComponent';
import {currentPlayerRequestJoinGame, requestJoinGamesList} from '../actions/index';


class JoinGamesListContainer extends React.Component {

    constructor(props) {
        super(props)

        this.handleOnGameClick = this.handleOnGameClick.bind(this)
    }

    componentDidMount() {
        console.log('JoinGamesListContainer');
        this.props.dispatch(requestJoinGamesList())
    }

    handleOnGameClick(gameId) {
        this.props.dispatch(currentPlayerRequestJoinGame(gameId))
    }

    render() {
        const {games} = this.props

        return (
            <div>
                {Object.keys(games).length === 0 &&
                <p>Empty</p>
                }
                {Object.keys(games).length > 0 &&
                    <JoinGamesListComponent games={games} onGameClick={this.handleOnGameClick}/>
                }
            </div>
        )
    }
}



export default connect(state => state)(JoinGamesListContainer);