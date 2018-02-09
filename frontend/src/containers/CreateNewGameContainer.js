import {connect} from 'react-redux';
import NewGameButtonComponent from '../components/NewGameButtonComponent';
import {createNewGame} from '../actions/index';

const mapStateToProps = (state, ownProps) => {
    return {};
}

const mapDispatchToProps = (dispatch, ownProps) => {
    return {
        onClick: () => {
            dispatch(createNewGame())
        }
    }
}


const CreateNewGameContainer = connect(
    mapStateToProps,
    mapDispatchToProps
)(NewGameButtonComponent);

export default CreateNewGameContainer;
