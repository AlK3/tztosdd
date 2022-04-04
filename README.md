# tztosdd
Запускается на 80 порту хоста.

##### Необходимое ПО для развертывания приложения
* Docker
* Docker Compose
* Debian-based Linux Distribution

##### Команды для развертывания приложения
```
sudo chmod -R ugo+rw src
sudo docker-compose up -d
```

##### Состав приложения
* docker-compose.yml — содержит инструкции, необходимые для запуска и настройки сервисов;
* .env — содержит переменные среды;
* categories.json — файл импорта данных;
* functions.php — функции по импорту/эĸспорту данных, sql-запросы, вызываются при открытии list_menu.php;
* list_menu.php — страница на ĸоторой выводится в столбец списоĸ меню из БД с отсупами;
* type_a.txt и type_a.txt — сгенерированные приложением файлы;
* default.conf — конфиг nginx'а;
* Dockerfile — для образа php.
