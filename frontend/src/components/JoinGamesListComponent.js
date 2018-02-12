import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom'

const JoinGamesListComponent = ({games, onClick}) => (
    <div className="games-list">
        <ul>
            {games.map(game => (
                <li key={game.id} onClick={() => onClick(game.id)} className="games-list-item">
                    <Link to={`/${game.id}`}>
                        Game {game.id}
                    </Link>
                </li>
            ))}
        </ul>
    </div>
);

JoinGamesListComponent.propTypes = {
    games: PropTypes.arrayOf(
        PropTypes.shape({
            id: PropTypes.number.isRequired
        })
    ).isRequired,
    onClick: PropTypes.func.isRequired
};

export default JoinGamesListComponent;
