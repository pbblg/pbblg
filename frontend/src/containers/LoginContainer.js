import React from 'react';
import {connect} from 'react-redux';
import {loginPlayer} from '../actions/index';

class LoginContainer extends React.Component {

    constructor(props) {
        super(props);
        this.state = {value: '', error: ''};

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
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

    render() {
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

export default connect(state => state)(LoginContainer);
