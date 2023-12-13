--Datos de conexion a Bd en app -> config -> Database.php -> array $default 

--Base de datos PostgreSql (usada V.12)

-- CREACION DE TABLAS : 
CREATE TABLE users (
    id SERIAL not null,
    email VARCHAR(30) NOT NULL,
    password VARCHAR(100) NOT NULL,
    userName VARCHAR(50) NOT NULL,
    name VARCHAR(50),
    lastName VARCHAR(50),
    phoneNumber VARCHAR(20),
    gender varchar(1),
    birthDate DATE,
    address VARCHAR(100),
    validated boolean,
    PRIMARY KEY (id)
);

create table library(
	id serial not null,
	idMovie integer not null,
	idUser integer not null,
	primary key(id),
	foreign key (idUser) references users(id)
);


-- Database: FilmCave 
-- DROP DATABASE IF EXISTS "FilmCave";
CREATE DATABASE "FilmCave"
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Spanish_Argentina.1252'
    LC_CTYPE = 'Spanish_Argentina.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;

-- Datos para prueba en bd: 

INSERT INTO users (email, password, userName, name, lastName, phoneNumber, gender, birthDate, address)
VALUES 
('axelx33@example.com', '$2y$10$m9WqDlHM4lQtFcrOyMdm4eeAVkozeaYAosXazV.5mx/iRBhoV1vcC', 'Axelx33', 'Axel', 'Kaechele', '3764622541', 'M', '1999-12-15', '123 Street'),
('usuario2@example.com', '$2y$10$m9WqDlHM4lQtFcrOyMdm4eeAVkozeaYAosXazV.5mx/iRBhoV1vcC', 'usuario2', 'Jane', 'Smith', '9876543210', 'F', '1995-05-10', '456 Avenue'),
('usuario3@example.com', '$2y$10$m9WqDlHM4lQtFcrOyMdm4eeAVkozeaYAosXazV.5mx/iRBhoV1vcC', 'usuario3', 'Mike', 'Johnson', '5555555555', 'M', '1985-12-25', '789 Road');

select * from users
$2y$10$m9WqDlHM4lQtFcrOyMdm4eeAVkozeaYAosXazV.5mx/iRBhoV1vcC  --> 15300139


-- Ante algun problema en la creacion de tablas =>
-- Table: public.users
CREATE TABLE IF NOT EXISTS public.users
(
    id integer NOT NULL DEFAULT nextval('users_id_seq'::regclass),
    email character varying(30) COLLATE pg_catalog."default" NOT NULL,
    password character varying(100) COLLATE pg_catalog."default" NOT NULL,
    username character varying(50) COLLATE pg_catalog."default" NOT NULL,
    name character varying(50) COLLATE pg_catalog."default",
    lastname character varying(50) COLLATE pg_catalog."default",
    phonenumber character varying(20) COLLATE pg_catalog."default",
    gender character varying(1) COLLATE pg_catalog."default",
    birthdate date,
    address character varying(100) COLLATE pg_catalog."default",
    validated boolean DEFAULT false,
    CONSTRAINT users_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.users
    OWNER to postgres;

---------------------------------------

CREATE TABLE IF NOT EXISTS public.library
(
    id integer NOT NULL DEFAULT nextval('library_id_seq'::regclass),
    idmovie integer NOT NULL,
    iduser integer NOT NULL,
    CONSTRAINT library_pkey PRIMARY KEY (id),
    CONSTRAINT library_iduser_fkey FOREIGN KEY (iduser)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.library
    OWNER to postgres;
