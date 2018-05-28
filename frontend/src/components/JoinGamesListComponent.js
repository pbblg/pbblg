import React from 'react';
import PropTypes from 'prop-types';

const JoinGamesListComponent = ({games, onGameClick}) => (
    <div className="open-games-list">
        <ul className="list-group mb-3">
            {Object.keys(games).map(gameId => (
                <li key={gameId} onClick={() => onGameClick(gameId)}
                    className="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 className="my-0">Game {gameId}</h6>
                        <small className="text-muted">{games[gameId].countFreePlaces} free places</small>
                    </div>
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
