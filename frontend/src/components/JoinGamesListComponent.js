import React from 'react';
import PropTypes from 'prop-types';

const JoinGamesListComponent = ({games, onGameClick}) => (
    <div className="games-list">
        <ul>
            {Object.keys(games).map(gameId => (
                <li key={gameId} onClick={() => onGameClick(gameId)} className="games-list-item">
                        Game {gameId} ({games[gameId].countFreePlaces} free places)
                </li>
            ))}
        </ul>
    </div>
);

JoinGamesListComponent.propTypes = {
    games: PropTypes.objectOf(
        PropTypes.shape({
            id: PropTypes.number.isRequired,
            countFreePlaces: PropTypes.number.isRequired
        })
    ).isRequired,
    onGameClick: PropTypes.func.isRequired
};

export default JoinGamesListComponent;
