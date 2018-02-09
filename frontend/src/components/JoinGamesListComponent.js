import React from 'react';
import PropTypes from 'prop-types';

const JoinGamesListComponent = ({games}) => (
    <div className="games-list">
        <ul>
            {games.map(game => (
                <li key={game.id} className="games-list-item">Game {game.id}</li>
            ))}
        </ul>
    </div>
);

JoinGamesListComponent.propTypes = {
    games: PropTypes.arrayOf(
        PropTypes.shape({
            id: PropTypes.number.isRequired
        })
    ).isRequired
};

export default JoinGamesListComponent;
