# POC Microservice (PHP + Kafka + Postgres)
## 
To boot up kafka:
- docker-compose -f kafka/docker-compose.yml up

To boot up the application:
- docker-compose -f docker-compose.yml up

The database should be created automatically, if not login on pgadmin (http://0.0.0.0:5050/login - user: pgadmin4@pgadmin.org pass: admin) and run the sql in the file `docker/postgres/docker-entrypoint-initdb.d/createdb.sh`