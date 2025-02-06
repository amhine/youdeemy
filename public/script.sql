CREATE TABLE role (
    id_role SERIAL PRIMARY KEY,
    nom_role VARCHAR(200) NOT NULL
);

CREATE TYPE status_enum AS ENUM ('actif', 'inactif');

CREATE TABLE utilisateur (
    id_user SERIAL PRIMARY KEY,  
    nom_user VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  
    id_role INT NOT NULL,
    status status_enum DEFAULT 'actif', 
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES role(id_role) ON DELETE CASCADE
);
CREATE TABLE categorie (
    id_categorie SERIAL PRIMARY KEY,
    nom_categorie VARCHAR(200) NOT NULL,
    description VARCHAR(500) NOT NULL 
);
CREATE TYPE type_enum AS ENUM ('video', 'document');
CREATE TABLE cours (
    id_cours SERIAL PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_categorie INT NOT NULL,
    id_user INT NOT NULL,
    fichier VARCHAR(255) NOT NULL,   
    statut VARCHAR(50) DEFAULT 'Actif',
    type_cours type_enum NOT NULL, 
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user) ON DELETE CASCADE
);
CREATE TABLE tag (
    id_tag SERIAL PRIMARY KEY,
    nom_tag VARCHAR(200) NOT NULL
);
CREATE TABLE courstag (
    id_cours INT NOT NULL,
    id_tag INT NOT NULL,
    PRIMARY KEY (id_cours, id_tag),
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tag(id_tag) ON DELETE CASCADE
);

CREATE TABLE inscription (
    id_inscription SERIAL PRIMARY KEY,
    id_user INT NOT NULL,  
    id_cours INT NOT NULL,     
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON DELETE CASCADE
);