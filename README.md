# POC Microservice (PHP + Kafka + Postgres)
## 
To boot up kafka:
- docker-compose -f kafka/docker-compose.yml up

To boot up the application:
- docker-compose -f docker-compose.yml up

Install dependencies
- docker-compose exec app composer install

Start services
- docker-compose exec app php services/topic-a.php
- docker-compose exec app php services/topic-b.php

Send request
- docker-compose exec app php services/requester.php

The database should be created automatically, if not login on pgadmin (http://0.0.0.0:5050/login - user: pgadmin4@pgadmin.org pass: admin) and run the sql in the file `docker/postgres/docker-entrypoint-initdb.d/createdb.sh`