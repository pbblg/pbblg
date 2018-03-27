import React from 'react';
import {connect} from 'react-redux';
import {requestLogin} from '../actions/index';

class LoginContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            login: '',
            password: '',
            error: props.loginError || ''
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {

        this.setState({[event.target.getAttribute('name')]: event.target.value});
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.login.length < 3 || this.state.password.length < 3) {
            this.setState({error: 'Login or password must be at least 3 characters'})
        } else {
            this.setState({error: ''})

            this.props.dispatch(requestLogin(this.state.login, this.state.password))
        }
    }

    render() {
        const error = this.state.error || this.props.loginError;

        return (
            <div className="game-enter">
                <form onSubmit={this.handleSubmit}>
                    Login: <input className="game-enter-input-login"name="login" type="text" value={this.state.login} onChange={this.handleChange} />
                    <br/>
                    Password: <input className="game-enter-input-password" name="password" type="password" value={this.state.password} onChange={this.handleChange} />
                    <br/>
                    <p>{error}</p>
                    <br/>
                    <input className="game-enter-submit-button" type="submit" value="Submit" />
                </form>
            </div>
        );
    }
}

export default connect(state => state)(LoginContainer);
