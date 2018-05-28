import React from 'react';
import {connect} from 'react-redux';

class LoginContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            login: '',
            password: '',
            error: props.loginError || ''
        };
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-md-center">
                    <div className="col-md-4">
                        <div className="panel panel-primary mt-5">
                            <div className="panel-body">
                                You are not logged, please <a href="/login">login</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default connect(state => state)(LoginContainer);
