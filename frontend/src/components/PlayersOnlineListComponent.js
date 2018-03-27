import React from 'react';
import PropTypes from 'prop-types';

const PlayersOnlineListComponent = ({players}) => (
    <div className="players-online-list">
        <ul>
            {Object.keys(players).map(playerId => (
                <li key={playerId} className="players-online-list-item">
                    Player #{playerId} {players[playerId].name}
                </li>
            ))}
        </ul>
    </div>
);

PlayersOnlineListComponent.propTypes = {
    players: PropTypes.objectOf(
        PropTypes.shape({
            id: PropTypes.number.isRequired,
            name: PropTypes.string.isRequired
        })
    ).isRequired
};

export default PlayersOnlineListComponent;
