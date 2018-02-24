import React from 'react';
import GameWelcomeContainer from './GameWelcomeContainer';
import LoginContainer from './LoginContainer';
import {connect} from "react-redux";

class AppContainer extends React.Component {

    render() {
        const auth = this.props.auth

        if (auth) {
            return (
                <GameWelcomeContainer/>
            );
        }

        return (
            <LoginContainer/>
        );
    }
}

export default connect(state => state)(AppContainer);
