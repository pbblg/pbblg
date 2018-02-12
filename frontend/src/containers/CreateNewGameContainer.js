import {connect} from 'react-redux';
import NewGameButtonComponent from '../components/NewGameButtonComponent';
import {createNewGameAction, requestGameWelcomeState} from '../actions/index';

const mapStateToProps = (state, ownProps) => {
    return {};
}

const mapDispatchToProps = (dispatch, ownProps) => {
    return {
        onClick: () => {
            dispatch(createNewGameAction())
        }
    }
}


const CreateNewGameContainer = connect(
    mapStateToProps,
    mapDispatchToProps
)(NewGameButtonComponent);

export default CreateNewGameContainer;
