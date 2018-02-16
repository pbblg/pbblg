import React from 'react';
import {connect} from 'react-redux';
import JoinGamesListComponent from '../components/JoinGamesListComponent';
import {joinGame, requestGameWelcomeState} from '../actions/index';


// const mapStateToProps = (state, ownProps) => {
//     return state;
// }
//
// const mapDispatchToProps = (dispatch, ownProps) => {
//     return {
//         onGameClick: (gameId) => {
//             dispatch(joinGame(gameId))
//         }
//     }
// }


class JoinGamesListContainer extends React.Component {
    constructor(props) {
        super(props)

        this.handleOnGameClick = this.handleOnGameClick.bind(this)
    }

    componentDidMount() {
        this.props.dispatch(requestGameWelcomeState())
    }

    handleOnGameClick(gameId) {
        this.props.dispatch(joinGame(gameId))
    }

    render() {
        const {games} = this.props

        return (
            <div>
                {games.length === 0 &&
                <p>Empty</p>
                }
                {games.length > 0 &&
                    <JoinGamesListComponent games={games} onGameClick={this.handleOnGameClick}/>
                }
            </div>
        )
    }
}



export default connect(state => state)(JoinGamesListContainer);
