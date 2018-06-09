import './index.css'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import { createLogger } from 'redux-logger'
import { createStore, applyMiddleware } from 'redux'
import socket from './socket'
import app from './reducers/index'
import AppContainer from './containers/AppContainer'
import {
    playerAuthenticated,
    // receiveLoginFail,
    // receiveLoginSuccess,
    receiveLogout,
    receiveLogin,
    newGameWasCreatedAction,
    gameRemoved,
    // receiveJoinGamesList,
    // receivePlayersOnlineList,
    // receiveExitGame,
    // socketConnectedAction,
    //debugServerState,
    // otherPlayerJoinedGame,
    joinedGame,
    // receiveOtherPlayerExitGame,
} from "./actions/index";
import remoteActionMiddleware from "./middlewares/remoteAction";
import serverStateLoggerAction from "./middlewares/serverStateLoggerAction";

let store = createStore(
    app,
    applyMiddleware(
        createLogger(),
        remoteActionMiddleware(socket),
        serverStateLoggerAction
    )
);

socket.connect();

socket.emit('getMyself', {}, function (player) {
    store.dispatch(playerAuthenticated(player))
});
socket.on('authenticated', function (player) {
    store.dispatch(playerAuthenticated(player))
});
socket.on('newGameCreated', function (data) {
    store.dispatch(newGameWasCreatedAction(data))
});
socket.on('userLoggedOut', function (data) {
    store.dispatch(receiveLogout(data))
});
socket.on('userLoggedIn', function (data) {
    store.dispatch(receiveLogin(data))
});
socket.on('gameRemoved', function (data) {
    store.dispatch(gameRemoved(data))
});
socket.on('joinedGame', function (data) {
    store.dispatch(joinedGame(data))
});

/*
socket.on('loginFail', function (data) {
    store.dispatch(receiveLoginFail(data.error))
});
socket.on('loginSuccess', function (data) {
    store.dispatch(receiveLoginSuccess(data.accessToken, data.player))
});

socket.on('serverState', function (data) {
    //store.dispatch(debugServerState(data))
});
socket.on('newGame', function (data) {
    store.dispatch(newGameWasCreatedAction(data))
});
socket.on('exitGame', function (data) {
    store.dispatch(receiveExitGame())
});
socket.on('otherPlayerExitGame', function (data) {
    store.dispatch(receiveOtherPlayerExitGame(data.player, data.game))
});
socket.on('gameState', function (data) {
    store.dispatch(receiveGameState(data))
});
socket.on('gameWelcomeState', function (data) {
    store.dispatch(receiveJoinGamesList(data))
});
socket.on('playersOnlineList', function (data) {
    store.dispatch(receivePlayersOnlineList(data.playersOnline))
});
socket.on('otherPlayerJoinedGame', function (data) {
    store.dispatch(otherPlayerJoinedGame(data.player, data.game))
});

*/

render(
    <Provider store={store}>
        <AppContainer/>
    </Provider>,
    document.getElementById('root')
)