#Настройка окружения для разработки

Должен быть установлен docker. [Установить Docker](https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/#set-up-the-repository)

Один раз собираем image
```bash
cd frontend
sudo docker build -t pbblg/frontend .
```
и устанавливаем js зависимости
```bash
sudo docker run -it --rm -v=путь к папке pbblg/frontend:/home/app pbblg/frontend bash
yarn install
exit
```
В дальнейшем используем команды:

вначале понимаем контейнер
```bash
sudo docker run --name pbblg-frontend -v=путь к папке pbblg/frontend:/home/app -d -it --rm pbblg/frontend
```

поднять сервер
```bash
sudo docker exec -it pbblg-frontend  yarn start
```
сделать билд
```bash
sudo docker exec -it pbblg-frontend  yarn build
```
прогнать тесты
```bash
sudo docker exec -it pbblg-frontend  yarn test
```
