CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

DROP TABLE IF EXISTS "players";
CREATE TABLE "public"."players" (
    "id_user" uuid NOT NULL,
    "pseudo" character varying(128) NOT NULL,
    "last_connection" timestamp NOT NULL,
    CONSTRAINT "players_id" PRIMARY KEY ("id_user"),
    CONSTRAINT "players_pseudo" UNIQUE ("pseudo")
) WITH (oids = false);

DROP TABLE IF EXISTS "sequences";
CREATE TABLE "public"."sequences" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "public" boolean NOT NULL,
    "serie_id" uuid NOT NULL,
    CONSTRAINT "sequences_id" PRIMARY KEY ("id")
) WITH (oids = false);

DROP TABLE IF EXISTS "photos_sequences";
CREATE TABLE "public"."photos_sequences" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "photo_id" uuid NOT NULL,
    "order" int DEFAULT 0 NOT NULL,
    "sequence_id" uuid NOT NULL,
    CONSTRAINT "photos_sequences_id" PRIMARY KEY ("id"),
    CONSTRAINT "photos_sequences_sequence_id" FOREIGN KEY ("sequence_id") REFERENCES "sequences"("id")
) WITH (oids = false);

DROP TABLE IF EXISTS "players_sequences";
CREATE TABLE "public"."players_sequences" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    -- peut être null si la personne est anonyme
    "player_id" uuid,
    "sequence_id" uuid NOT NULL,
    "score" int NOT NULL,
    "status" boolean DEFAULT false NOT NULL,
    "date" timestamp NOT NULL,
    CONSTRAINT "players_sequences_player_id" FOREIGN KEY ("player_id") REFERENCES "players"("id_user"),
    CONSTRAINT "players_sequences_sequence_id" FOREIGN KEY ("sequence_id") REFERENCES "sequences"("id"),
    CONSTRAINT "players_sequences_id" PRIMARY KEY ("id")
) WITH (oids = false);
