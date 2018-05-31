import React from 'react';
import {connect} from 'react-redux';
import PlayersOnlineListComponent from '../components/PlayersOnlineListComponent';
import {requestPlayersOnlineList} from '../actions/index';


class PlayersOnlineListContainer extends React.Component {

    componentDidMount() {
        this.props.dispatch(requestPlayersOnlineList())
    }

    render() {
        const {playersOnline} = this.props;

        return (
            <div>
                {Object.keys(playersOnline).length === 0 &&
                    <p>No users online</p>
                }
                {Object.keys(playersOnline).length > 0 &&
                    <PlayersOnlineListComponent players={playersOnline}/>
                }
            </div>
        )
    }
}



export default connect(state => state)(PlayersOnlineListContainer);
