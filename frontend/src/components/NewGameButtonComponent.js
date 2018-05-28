import React from 'react';
import PropTypes from 'prop-types';

const NewGameButton = ({onClick}) => (
    <button className="btn btn-sm mr-2" onClick={onClick}>New game</button>
);

NewGameButton.propTypes = {
    onClick: PropTypes.func.isRequired
};

export default NewGameButton;
