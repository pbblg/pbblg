import React from 'react';
import PropTypes from 'prop-types';

const JoinGamesListComponent = ({games, onGameClick}) => (
    <div className="games-list">
        <ul>
            {games.map(game => (
                <li key={game.id} onClick={() => onGameClick(game.id)} className="games-list-item">
                        Game {game.id} ({game.countFreePlaces} free places)
                </li>
            ))}
        </ul>
    </div>
);

JoinGamesListComponent.propTypes = {
    games: PropTypes.arrayOf(
        PropTypes.shape({
            id: PropTypes.number.isRequired,
            countFreePlaces: PropTypes.number.isRequired
        })
    ).isRequired,
    onGameClick: PropTypes.func.isRequired
};

export default JoinGamesListComponent;
