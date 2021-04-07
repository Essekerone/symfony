Test Task - STRIX

Install:
1) From `docker` folder run command:
- docker-compose up
2) From `src` folder run command
-  docker exec -it `php_container_name` php bin/console doctrine:migrations:migrate
-  docker exec -it `php_container_name` php bin/console doctrine:fixtures:load


OR

- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load


Get JWT token :

`curl -X POST -H "Content-Type: application/json" http://localhost/api/login_check -d '{"username":"admin@admin.pl","password":"admin"}'`