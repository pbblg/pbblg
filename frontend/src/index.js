import './index.css'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import { createLogger } from 'redux-logger'
import { createStore, applyMiddleware } from 'redux'
import app from './reducers/index'
import AppContainer from './containers/AppContainer'
import ioClient from "socket.io-client";
import {newGameWasCreatedAction, receiveGameWelcomeState} from "./actions/index";
import remoteActionMiddleware from "./middlewares/remoteAction";



const socket = ioClient.connect('http://172.17.0.2:8008');
socket.on('connecting', function () {
    console.log('Соединение...');
});
socket.on('connect', function () {
    console.log('Соединение установлено!');
});


let store = createStore(
    app,
    applyMiddleware(
        createLogger(),
        remoteActionMiddleware(socket)
    )
)


socket.on('newGame', function (data) {
    store.dispatch(newGameWasCreatedAction(data))
});
socket.on('gameWelcomeState', function (data) {
    store.dispatch(receiveGameWelcomeState(data))
});


render(
    <Provider store={store}>
        <AppContainer/>
    </Provider>,
    document.getElementById('root')
)