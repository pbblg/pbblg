import './index.css'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import { createLogger } from 'redux-logger'
import { createStore, applyMiddleware } from 'redux'
import app from './reducers/index'
import AppContainer from './containers/AppContainer'
import ioClient from "socket.io-client";
import {newGameWasCreatedAction, receiveGameWelcomeState, socketConnectedAction, debugServerState, otherPlayerJoinedGame, currentPlayerJoinedGame} from "./actions/index";
import remoteActionMiddleware from "./middlewares/remoteAction";
import serverStateLoggerAction from "./middlewares/serverStateLoggerAction";



const socket = ioClient.connect('http://172.17.0.2:8008');
socket.on('connecting', function () {
    console.log('Соединение...');
});



let store = createStore(
    app,
    applyMiddleware(
        createLogger(),
        remoteActionMiddleware(socket),
        serverStateLoggerAction
    )
)

socket.on('connect', function () {
    store.dispatch(socketConnectedAction())
});
socket.on('serverState', function (data) {
    store.dispatch(debugServerState(data))
});
socket.on('newGame', function (data) {
    store.dispatch(newGameWasCreatedAction(data))
});
socket.on('gameWelcomeState', function (data) {
    store.dispatch(receiveGameWelcomeState(data))
});
socket.on('otherPlayerJoinedGame', function (data) {
    store.dispatch(otherPlayerJoinedGame(data.player, data.game))
});
socket.on('currentPlayerJoinedGame', function (data) {
    store.dispatch(currentPlayerJoinedGame(data.gameId))
});


render(
    <Provider store={store}>
        <AppContainer/>
    </Provider>,
    document.getElementById('root')
)