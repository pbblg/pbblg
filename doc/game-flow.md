# Протокол

Клиент общается с сервером посредством протокола [JSON-RPC 2.0](http://www.jsonrpc.org/specification). Например:
```json
{
    "id": <string>,
    "method": <string>,
    "params": <object>
}
```
Успешный ответ всегда имеет структуру:
```json
{
    "id": <string>,
    "result": <string|integer|array|object>
}
``` 
Не успешный ответ всегда имеет структуру:
```json
{
    "id": <string>,
    "error": {
        "code": <integer>, 
        "message": <string>
    }
}
``` 

Сервер уведомляет клиента посредством измененного протокола JSON-RPC (сервер всегда вместо `method` всегда возвращает `event`)
```json
{
    "event": <string>,
    "params": <object>
}
```
 
# Игровой процесс
 
Пользователь может создать новую игру или вступить в уже созданную. 

Возможные методы от Клиента серверу:

## newGame 
```json
{
    "id": 1,
    "method": "newGame"
}
```
 - cервер создает игру
 - в списке игр появляется новая игра

## joinGame(gameId)
```json
{
    "id": 1,
    "method": "joinGame",
    "params": {
        "gameId": 123
    }
}
```
 - текущий пользователь вступает в игру.
 - cервер запоминает игрока
 - и уведомляет всех игроков событием [joinedGame](#joinedGame).
 - каждый игрок видит нового игрока
  
Пользователь который создал игру определяет ее начало.

## startGame(gameId)
```json
{
    "id": 1,
    "method": "startGame",
    "params": {
        "gameId": 123
    }
}
```
- сервер стартует игру:
  - создает колоду
  - рассылает всем игрокам по первой карты событием [firstCard](#firstCard)
  - определяет чей ход
  - отправляет первому игроку карту событием [takeCard](#takeCard)
  - уведомляет остальных игроков о том что игрок взял карту отправляя им событие [userGotCard](#userGotCard)
- остальные игроки видят, что у игрока который ходит 2 карты

Тот чей ход выбирает какую карту сыграть.

## playCard(cardId[, targetUserId, targetCardId]) 
```json
{
    "id": 1,
    "method": "playCard",
    "params": {
        "cardId": 1,
        "targetUserId": 123,
        "targetCardId": 8
    },
}
```
- игрок сыграл карту:
  - сервер запоминает карту
  - уведомляет всех игроков событием [userPlayedCard](#userPlayedCard)
  - уведомляет всех игроков событием результата игры



На сервере возникают такие события:

## newGameCreated
```json
{
    "event": "newGameCreated",
    "params": {
        "gameId": 123
    }
}
```
Возникает после того, как игра создана.

## joinedGame
```json
{
    "event": "joinedGame",
    "params": {
        "user": {
            "id": 1,
            "name": "Sebas"
        }
    }
}
```
Возникает после того, как пользователь присоеденился к игре.

## firstCard
```json
{
    "event": "firstCard",
    "params": {
        "cardId": 1
    }
}
```
Возникает после того, как игра создана, все получают первую карту.

## takeCard
```json
{
    "event": "takeCard",
    "params": {
        "cardId": 1
    }
}
```
Возникает в начале пользовательского хода.

## userGotCard
```json
{
    "event": "userGotCard",
    "params": {
        "userId": 1
    }
}
```
Возникает после того, как пользователь получил карту.

## userPlayedCard
```json
{
    "event": "userPlayedCard",
    "params": {
        "userId": 1,
        "cardId": 5,
        "targetUserId": 2,
        "targetCardId": 8
    }
}
```
Возникает после того, как пользователь сыграл карту.

# Служебные методы

## ping 
```json
{
    "id": 1,
    "method": "ping"
}
```
Response

```json
{
    "id": 1,
    "result": "pong"
}
```

## getGames 
```json
{
    "id": 1,
    "method": "getGames",
    "params": {
        "limit": 10,
        "offset":0
    }
}
```
Response

```json
{
    "id": 1,
    "result": [
        {
            "gameId": 1,
            "userCount": 2,
            "isStarted": false
        },
        {
            "gameId": 2,
            "userCount": 4,
            "isStarted": true
        }
    ]
}
```

## getGame
```json
{
    "id": 1,
    "method": "getGame",
    "params": {
        "gameId": 1
    }
}
```
Response

```json
{
    "id": 1,
    "result": {
        "gameId": 1,
        "userCount": 2,
        "isStarted": false,
        "users": [
            {
                "userId": 1,
                "name": "Sebas",
                "isOut": false
            },
            {
                "gameId": 2,
                "name": "Shirokiy",
                "isOut": true
            }
        ]
    }
}
```

## getGameLog
```json
{
    "id": 1,
    "method": "getGameLog",
    "params": {
        "gameId": 1,
        "limit": 10,
        "offset":0
    }
}
```
Response

```json
{
    "id": 1,
    "result": {
        "gameId": 1,
        "userCount": 2,
        "isFinished": true,
        "users": [
            {
                "userId": 1,
                "name": "Sebas"
            },
            {
                "gameId": 2,
                "name": "Shirokiy"
            }
        ]
        "moves": [
            {
                "userId": 1,
                "cardId": 1,
                "targetUserId": 2,
                "result": "miss|hit",
                "text": "Sebas походил 1 на Shirokiy и сказал 8, не угадал"
            },
            {
                "userId": 2,
                "cardId": 2,
                "targetUserId": null,
                "result": 3,
                "text": "Shirokiy походил 2 на Sebas, увидел 3"
            },
            {
                "userId": 1,
                "cardId": 3,
                "targetUserId": 2,
                "result": "win|loose",
                "text": "Sebas походил 3 на Shirokiy, Sebas выиграл"
            },
            {
                "userId": 2,
                "cardId": 4,
                "targetUserId": null,
                "result": null,
                "text": "Sebas походил 4"
            },
            {
                "userId": 1,
                "cardId": 5,
                "targetUserId": 2,
                "result": "null|hit",
                "text": "Sebas походил 5 на Shirokiy, Shirokiy скинул 3"
            },
            {
                "userId": 1,
                "cardId": 6,
                "targetUserId": 2,
                "result": null,
                "text": "Sebas походил 6 на Shirokiy, Sebas получил 3"
            },
            {
                "userId": 1,
                "cardId": 7,
                "targetUserId": null,
                "result": null,
                "text": "Sebas походил 7"
            }
        ]
    }
}
```