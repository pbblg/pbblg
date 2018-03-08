# pbblg
Princess Bubblegum game.

## Комманды
`$ composer serve` - запускает РНР сервер. Сервер доступен на http://localhost::8080.

`$ ./bin/websocket` - запускает Websocket сервер. Сервер доступен на http://localhost::8088. (См. консоль и исходный код индексной страницы)

## Темплит админки

Используем [AdminLTE](https://adminlte.io/docs/2.4/layout) шаблон для админки, чтобы его увидеть, нужно выполнить

```bash
ln -s /home/maxgu/proj/pbblg/vendor/almasaeed2010/adminlte/ /home/maxgu/proj/pbblg/public/
```

т.е. создать символическую ссылку с полными путями.
Тепрь шаблон можно видеть по адресу `http://localhost:8080/adminlte/index.html`.

(А у кого виндоуз, то можно просто скопировать папку adminlte в public). 
