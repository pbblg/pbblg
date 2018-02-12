import React, { Component } from 'react';

const GamePlayComponent = (props) => (
    <div className="game-play">
        <h3>Game play {props.match.params.gameId}</h3>
    </div>
);

export default GamePlayComponent;
