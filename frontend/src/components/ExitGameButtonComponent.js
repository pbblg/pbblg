import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom'

const ExitGameButtonComponent = ({onClick}) => (
    <button className="button" onClick={onClick} >
        <Link to="/">Exit</Link>
    </button>
);

ExitGameButtonComponent.propTypes = {
    onClick: PropTypes.func.isRequired
};

export default ExitGameButtonComponent;
