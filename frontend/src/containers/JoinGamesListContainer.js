import {connect} from 'react-redux';
import JoinGamesListComponent from '../components/JoinGamesListComponent';
import {joinGame} from '../actions/index';

const mapStateToProps = (state, ownProps) => {
    return state;
}

const mapDispatchToProps = (dispatch, ownProps) => {
    return {
        // onClick: () => {
        //     dispatch(joinGame())
        // }
    }
}


const JoinGamesListContainer = connect(
    mapStateToProps,
    mapDispatchToProps
)(JoinGamesListComponent);

export default JoinGamesListContainer;
