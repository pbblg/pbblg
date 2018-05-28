import React from 'react';
import PropTypes from 'prop-types';

const OpenGamesListComponent = ({games, onGameClick}) => (
    <div className="open-games-list">
        <ul className="list-group mb-3">
            {Object.keys(games).map(gameId => (
                <li key={gameId}
                    className="list-group-item d-flex justify-content-between lh-condensed align-items-center">
                    <div>
                        <div>
                            <h6 className="my-0">Game #{gameId}</h6>
                            <small className="text-muted">{games[gameId].created}, N free places</small>
                        </div>
                    </div>
                    <button className="btn btn-sm btn-success" onClick={() => onGameClick(gameId)}>
                        Join <i className="fa fa-chevron-right"></i>
                    </button>
                </li>
            ))}
        </ul>
    </div>
);

OpenGamesListComponent.propTypes = {
    games: PropTypes.objectOf(
        PropTypes.shape({
            gameId: PropTypes.number.isRequired,
            created: PropTypes.string.isRequired
            // countFreePlaces: PropTypes.number.isRequired
        })
    ).isRequired,
    onGameClick: PropTypes.func.isRequired
};

export default OpenGamesListComponent;
