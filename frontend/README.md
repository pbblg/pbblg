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
sudo docker run --name pbblg-frontend -v=/home/sebaks/projects/pbblg/frontend:/home/app -d -it --rm pbblg/frontend
```

поднять сервер
```bash
sudo docker exec -it pbblg-frontend  yarn start
sudo docker exec -it pbblg-frontend  yarn startTestServer
```
сделать билд
```bash
sudo docker exec -it pbblg-frontend  yarn build
```
прогнать тесты
```bash
sudo docker exec -it pbblg-frontend  yarn test
```


**Selenium server**

затянуть образ
```bash
docker pull selenium/standalone-chrome
```
поднять контеинер
```bash
sudo docker run --name selenium-server -p 4444:4444 -v /dev/shm:/dev/shm -d -it --rm selenium/standalone-chrome
```

запустить тесты
```bash
sudo docker exec -it pbblg-frontend  yarn start
sudo docker exec -it pbblg-frontend  yarn startTestServer
./vendor/bin/codecept run
```