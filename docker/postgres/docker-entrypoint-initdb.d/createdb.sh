psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    CREATE USER hpuser WITH PASSWORD 'helloprint';
    CREATE DATABASE helloprint;
    GRANT ALL PRIVILEGES ON DATABASE helloprint TO hpuser;

    CREATE TABLE IF NOT EXISTS request (
        id serial PRIMARY KEY,
        message VARCHAR ( 255 ),
        token VARCHAR ( 255 ),
        finished BOOLEAN default false  
    )
EOSQL