import React, { Component } from 'react';
import './App';


function NotPlayedCard(props) {

    const card = props.card;
    const style = {
        zIndex: card,
        top: -(card * 2) + 'px',
        left: -(card * 2) + 'px'
    };

    return (
        <div className="card" style={style}>
            <div className="card-shirt"></div>
        </div>
    );
}

function NotPlayedCardDeck(props) {

    const cards = props.cards;
    const deckCards = cards.map((card) =>
        <NotPlayedCard key={card.toString()} card={card} />
    );

    return (
        <div className="table-half table-left deck">
            {deckCards}
        </div>
    );
}

function LastPlayedCard(props) {

    const card = props.card;

    return (
        <div className="table-half table-right">
            <div className="card ">
                <p>{card}</p>
            </div>
        </div>
    );
}

function Table(props) {
    return (
        <div className="table">
            <NotPlayedCardDeck cards={props.notPlayedCards} />
            <LastPlayedCard card={props.lastPlayedCard} />
        </div>
    );
}

class TestPult extends Component {

    constructor(props) {
        super(props);

        this.handleClick = this.handleClick.bind(this);
        this.handleStartGame = this.handleStartGame.bind(this);
        this.handleNewGame = this.handleNewGame.bind(this);
    }

    handleClick(event) {
        this.props.onPopCardFromNotPlayedDeck()
    }
    handleStartGame(event) {
        this.props.onStartGame()
    }
    handleNewGame(event) {
        this.props.onNewGame()
    }

    render() {
        return (
            <div className="table test-pult">
                <button onClick={this.handleClick} className="test-button">Pop card from not played deck</button>
                <button onClick={this.handleStartGame} className="test-button">Star game</button>
                <button onClick={this.handleNewGame} className="test-button">New game</button>
            </div>
        );
    }
}


class App extends Component {

    constructor(props) {
        super(props);

        this.state = {
            notPlayedCards: [1,2,3,4,5,6],
            lastPlayedCard: 7
        };

        this.handlePopCardFromNotPlayedDeck = this.handlePopCardFromNotPlayedDeck.bind(this);
        this.handleStartGame = this.handleStartGame.bind(this);
        this.handleNewGame = this.handleNewGame.bind(this);
    }

    handlePopCardFromNotPlayedDeck() {

        this.setState(function(prevState) {
            const prevNotPlayedCards = prevState.notPlayedCards;
            prevNotPlayedCards.pop();
            return {
                notPlayedCards: prevNotPlayedCards
            }
        });
    }

    handleNewGame() {
        this.props.gameClient.newGame();
    }

    handleStartGame() {
        this.props.gameClient.startGame();
    }

    render() {
        const notPlayedCards = this.state.notPlayedCards;
        const lastPlayedCard = this.state.lastPlayedCard;

        return (
            <div>
                <Table notPlayedCards={notPlayedCards} lastPlayedCard={lastPlayedCard} />
                <TestPult
                    onPopCardFromNotPlayedDeck={this.handlePopCardFromNotPlayedDeck}
                    onStartGame={this.handleStartGame}
                    onNewGame={this.handleNewGame} />
            </div>
        );
    }
}

export default App;
