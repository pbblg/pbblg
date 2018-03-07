import React from 'react';
import PropTypes from 'prop-types';

const ExitGameButtonComponent = ({onClick}) => (
    <button className="button" onClick={onClick} >
        Exit
    </button>
);

ExitGameButtonComponent.propTypes = {
    onClick: PropTypes.func.isRequired
};

export default ExitGameButtonComponent;
