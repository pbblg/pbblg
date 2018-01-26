import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';


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
        this.state = {
            notPlayedCards: props.notPlayedCards
        };
        console.log(this.state);
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick() {

        this.setState(function(prevState) {
            prevState.notPlayedCards.pop();
            console.log({
                notPlayedCards: prevState.notPlayedCards
            });
            return {
                notPlayedCards: prevState.notPlayedCards
            }
        });
    }

    render() {
        return (
            <div className="table test-pult">
                <button onClick={this.handleClick} className="test-button">Pop card from not played deck</button>
            </div>
        );
    }
}


class App extends Component {

    render() {

        const notPlayedCards = [1,2,3,4,5,6];
        const lastPlayedCard = [7];

        return (
            <div>
                <Table notPlayedCards={notPlayedCards} lastPlayedCard={lastPlayedCard} />
                <TestPult notPlayedCards={notPlayedCards} />
            </div>
        );
    }
}

export default App;
