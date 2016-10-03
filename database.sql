DROP DATABASE IF EXISTS exchange;
CREATE DATABASE exchange;

\connect exchange

BEGIN;

-----------------------------------------------------------------------
-- users
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "users" CASCADE;

CREATE TABLE "users"
(
  "id" serial NOT NULL,
  "login" VARCHAR(32),
  "email" VARCHAR(64) NOT NULL,
  "firstname" VARCHAR(32),
  "surname" VARCHAR(32),
  "password" VARCHAR(64) NOT NULL,
  "wallet_id" INTEGER NOT NULL,
  "status" VARCHAR(1) DEFAULT '0' NOT NULL,
  PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- wallets
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "wallets" CASCADE;

CREATE TABLE "wallets"
(
  "id" serial NOT NULL,
  "pln" REAL DEFAULT 0 NOT NULL,
  "usd" REAL DEFAULT 0 NOT NULL,
  "eur" REAL DEFAULT 0 NOT NULL,
  "chf" REAL DEFAULT 0 NOT NULL,
  "rub" REAL DEFAULT 0 NOT NULL,
  "czk" REAL DEFAULT 0 NOT NULL,
  "gbp" REAL DEFAULT 0 NOT NULL,
  PRIMARY KEY ("id")
);
-- -----------------------------------------------------------------------
-- -- config
-- -----------------------------------------------------------------------

DROP TABLE IF EXISTS "config" CASCADE;

CREATE TABLE "config"
(
  "name" VARCHAR(64) NOT NULL,
  "value" VARCHAR(255) NOT NULL
);

ALTER TABLE "users" ADD CONSTRAINT "users_fk_d82059"
FOREIGN KEY ("wallet_id")
REFERENCES "wallets" ("id");


INSERT INTO config VALUES
  ('PLN', '100000'),
  ('USD', '100000'),
  ('EUR', '100000'),
  ('CHF', '100000'),
  ('RUB', '100000'),
  ('CZK', '100000'),
  ('GBP', '100000');

-----------------------------------------------------------------------
-- tokens
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tokens" CASCADE;

CREATE TABLE "tokens"
(
  "id" serial NOT NULL,
  "user_id" INTEGER NOT NULL,
  "type" INT2 NOT NULL,
  "value" VARCHAR(64) NOT NULL,
  PRIMARY KEY ("id")
);


ALTER TABLE "tokens" ADD CONSTRAINT "tokens_fk_69bd79"
FOREIGN KEY ("user_id")
REFERENCES "users" ("id");


GRANT SELECT ON users TO PUBLIC;
GRANT SELECT ON config TO PUBLIC;
GRANT SELECT ON wallets TO PUBLIC;


ALTER TABLE users OWNER TO exchange;
ALTER TABLE config OWNER TO exchange;
ALTER TABLE wallets OWNER TO exchange;
ALTER TABLE tokens OWNER TO exchange;


ALTER TABLE users_id_seq OWNER TO exchange;
ALTER TABLE tokens_id_seq OWNER TO exchange;

COMMIT;
