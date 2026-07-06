CREATE DATABASE kin_alert CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kin_alert;


--✅ TABLE 1 : utilisateurs
CREATE TABLE utilisateurs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    role ENUM('utilisateur','moderateur','administrateur') DEFAULT 'utilisateur',
    statut ENUM('actif','suspendu') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--villes
CREATE TABLE villes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

--communes
CREATE TABLE communes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville_id BIGINT,
    FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE
);

--types_incidents
CREATE TABLE types_incidents (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT
);

--incidents
CREATE TABLE incidents (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    type_incident_id BIGINT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    commune_id BIGINT,
    avenue VARCHAR(150),
    statut ENUM('en_attente','valide','rejete') DEFAULT 'en_attente',
    nombre_confirmations INT DEFAULT 0,
    utilisateur_id BIGINT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (type_incident_id) REFERENCES types_incidents(id),
    FOREIGN KEY (commune_id) REFERENCES communes(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

--confirmations
CREATE TABLE confirmations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id BIGINT,
    incident_id BIGINT,
    date_confirmation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(utilisateur_id, incident_id),

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE
);

--commentaires
CREATE TABLE commentaires (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    utilisateur_id BIGINT,
    incident_id BIGINT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE
);

--notifications
CREATE TABLE notifications (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id BIGINT,
    message TEXT NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

--Upload de preuves
CREATE TABLE preuves (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    incident_id BIGINT,
    fichier VARCHAR(255),
    type_fichier VARCHAR(50),
    FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE
);

--Historique des statuts
CREATE TABLE historique_statuts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    incident_id BIGINT,
    ancien_statut VARCHAR(50),
    nouveau_statut VARCHAR(50),
    modifie_par BIGINT,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE historique_statuts
    ADD CONSTRAINT fk_historique_incident FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_historique_utilisateur FOREIGN KEY (modifie_par) REFERENCES utilisateurs(id) ON DELETE SET NULL;
