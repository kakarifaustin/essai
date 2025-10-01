-- Création de la base de données
CREATE DATABASE IF NOT EXISTS cem_bd;
USE cem_bd;

-- Table: administratif
CREATE TABLE administratif (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(25) NOT NULL,
    PRIMARY KEY (id)
);

-- Table: centre
CREATE TABLE centre (
    id INT(11) NOT NULL AUTO_INCREMENT,
    designation VARCHAR(200) NOT NULL,
    PRIMARY KEY (id)
);

-- Table: section
CREATE TABLE section (
    id INT(11) NOT NULL AUTO_INCREMENT,
    id_centre INT(11) NOT NULL,
    designation VARCHAR(200) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_centre) REFERENCES centre(id) ON DELETE CASCADE
);

-- Table: inscription
CREATE TABLE inscription (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nom_prenom_apprenant VARCHAR(255) NOT NULL,
    nom_prenom_pere VARCHAR(500) NOT NULL,
    nom_prenom_mere VARCHAR(500) NOT NULL,
    nom_prenom_tuteur VARCHAR(500) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    id_centre INT(11) NOT NULL,
    id_section INT(11) NOT NULL,
    bulletin VARCHAR(50) NOT NULL,
    statuts TINYINT(1) DEFAULT 2, -- 1 for validated, 2 for not validated
    PRIMARY KEY (id),
    FOREIGN KEY (id_centre) REFERENCES centre(id) ON DELETE CASCADE,
    FOREIGN KEY (id_section) REFERENCES section(id) ON DELETE CASCADE
);

-- Insertion de l'administrateur par défaut
INSERT INTO administratif (user_name, password) VALUES ('admin', 'admin@2025');
