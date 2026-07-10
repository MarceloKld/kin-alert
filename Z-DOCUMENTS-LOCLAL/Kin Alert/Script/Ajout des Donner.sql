USE kin_alert;

-- =========================
-- 1️⃣ UTILISATEURS
-- =========================

INSERT INTO utilisateurs (nom, email, mot_de_passe, telephone, role, statut)
VALUES 
('Admin Kinalert', 'admin@kinalert.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0817655587', 'administrateur', 'actif'),
('Jeans Bilo', 'jeans@gmail.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0994097996', 'utilisateur', 'actif'),
('Marcelo Kalonji', 'marcelo@gmail.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0856542136', 'administrateur', 'actif'),
('Israel Ilunga', 'israel@gmail.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0822457896', 'utilisateur', 'actif'),
('Jolka Tshilanda', 'jolka@gmail.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0991563214', 'utilisateur', 'actif');


-- =========================
-- 2️⃣ VILLES
-- =========================

INSERT INTO villes (nom)
VALUES ('Kinshasa');


-- =========================
-- 3️⃣ COMMUNES
-- =========================

INSERT INTO communes (nom, ville_id)
VALUES 
('Ngombe', 1),
('Limete', 1),
('Ngaliema', 1),
('Kasa-Vubu', 1),
('Bandalungwa', 1),
('Barumbu', 1),
('Kalamu', 1),
('Lemba', 1),
('Matete', 1),
('Ndjili', 1);


-- =========================
-- 4️⃣ TYPES INCIDENTS
-- =========================

INSERT INTO types_incidents (titre, description)
VALUES 
('Inondation', 'Route ou quartier submergé par les eaux après la pluie'),
('Panne d\'électricité', 'Coupure de courant générale ou câble haute tension sectionné'),
('Nid de poule majeur', 'Dégradation importante de la chaussée entravant la circulation'),
('Érosion / Ravin', 'Progression d\'une tête d\'érosion menaçant des habitations'),
('Embouteillage critique', 'Blocage total de la circulation routière sur un axe majeur'),
('Tas d\'immondices', 'Accumulation sauvage de déchets non ramassés sur la voie publique'),
('Tuyau de la REGIDESO cassé', 'Fuite d\'eau importante sur le réseau de distribution d\'eau'),
('Feu de signalisation en panne', 'Feu de régulation routière non fonctionnel provoquant des accidents'),
('Éclairage public défectueux', 'Avenue totalement obscure favorisant l\'insécurité'),
('Nuisance sonore', 'Tapage nocturne ou pollution sonore excessive par des établissements');


-- =========================
-- 5️⃣ INCIDENTS
-- =========================

INSERT INTO incidents (titre, description, type_incident_id, latitude, longitude, commune_id, avenue, statut, nombre_confirmations, utilisateur_id)
VALUES
('Accident sur Boulevard', 'Collision entre deux véhicules', 3, -4.325, 15.322, 1, 'Boulevard 30 Juin', 'en_attente', 0, 2),
('Vol à Lingwala', 'Téléphone arraché', 2, -4.330, 15.300, 2, 'Avenue Kabinda', 'valide', 1, 2),
('Sous-station Limete inondée', 'L\'avenue Lumumba est impraticable suite aux pluies diluviennes', 3, -4.345, 15.332, 3, 'Avenue Lumumba', 'en_attente', 0, 1),

-- 2. Érosion à Ngaliema (commune_id = 4, type_incident_id = 6)
('Ravin menaçant à Binza', 'Une tête d\'érosion progresse rapidement vers les habitations', 6, -4.362, 15.261, 4, 'Avenue Mulele', 'en_attente', 0, 3),

-- 3. Panne d'électricité à Bandalungwa (commune_id = 6, type_incident_id = 4)
('Coupure générale Bandal Synkin', 'Plus d\'électricité depuis 48 heures suite à un câble sectionné', 4, -4.339, 15.284, 6, 'Avenue Kasavubu', 'en_attente', 0, 4),

-- 4. Nid de poule majeur à Kasa-Vubu (commune_id = 5, type_incident_id = 5)
('Trou béant sur la chaussée', 'Un immense nid de poule bloque la bande droite et crée des bouchons', 5, -4.335, 15.301, 5, 'Avenue Huileries', 'valide', 3, 5),

-- 5. Tas d'immondices à Lemba (commune_id = 10, type_incident_id = 8)
('Décharge sauvage Terminus', 'Accumulation d\'ordures bloquant le passage des piétons au terminus', 8, -4.378, 15.325, 10, 'Avenue By Pass', 'en_attente', 0, 4),

-- 6. Tuyau cassé à Barumbu (commune_id = 8, type_incident_id = 9)
('Fuite d\'eau Regideso', 'Gaspillage d\'eau important suite à une rupture de conduite', 9, -4.318, 15.315, 8, 'Avenue Flambeau', 'en_attente', 0, 3),

-- 7. Embouteillage à Limete (commune_id = 3, type_incident_id = 7)
('Blocage total Échangeur', 'Embouteillage monstre suite à un camion en panne sur la voie', 7, -4.365, 15.352, 3, 'Boulevard Lumumba', 'valide', 5, 4),

-- 8. Éclairage défectueux à Gombe (commune_id = 1, type_incident_id = 11)
('Avenue dans le noir total', 'Tous les poteaux d\'éclairage sont éteints, insécurité grandissante', 1, -4.305, 15.305, 1, 'Avenue des Aviateurs', 'en_attente', 0, 5),

-- 9. Nuisance sonore à Kalamu (commune_id = 9, type_incident_id = 12)
('Tapage nocturne Matonge', 'Un bar joue de la musique à un volume excessif au-delà des heures légales', 9, -4.341, 15.312, 9, 'Avenue Stade', 'en_attente', 0, 4),

-- 10. Feu en panne à Lingwala (commune_id = 2, type_incident_id = 10)
('Feu de signalisation éteint', 'Le feu du croisement ne fonctionne plus, circulation anarchique', 10, -4.326, 15.302, 2, 'Avenue Libération', 'valide', 2, 2);


-- =========================
-- 6️⃣ CONFIRMATIONS
-- =========================

INSERT INTO confirmations (utilisateur_id, incident_id)
VALUES 
-- 3 citoyens confirment l'inondation à Limete (Incident ID 2 ou 4 selon vos tests)
(3, 49),
(4, 50),
(3, 53),

-- Des citoyens confirment l'érosion à Ngaliema (Incident ID 3 ou 5)
(4, 54),
(5, 51),

-- Confirmations pour la panne d'électricité à Bandalungwa (Incident ID 6)
(2, 52),
(1, 52),

-- Confirmations pour le nid de poule majeur à Kasa-Vubu (Incident ID 7)
(5, 53),

-- Confirmations pour le tas d'immondices à Lemba (Incident ID 8)
(1, 54),
(3, 54);


-- =========================
-- 7️⃣ HISTORIQUE STATUTS
-- =========================

INSERT INTO historique_statuts (incident_id, ancien_statut, nouveau_statut, modifie_par)
VALUES

-- Incident ID 4 (Nid de poule majeur) : Traitement et Résolution
(49, 'valide', 'en_cours', 1),
(49, 'en_cours', 'resolu', 1),

-- Incident ID 5 (Tas d'immondices) : De la création à la validation
(50, NULL, 'en_attente', 1),
(50, 'en_attente', 'valide', 1),

-- Incident ID 6 (Panne d'électricité) : Signalement et prise en charge urgente
(51, NULL, 'en_attente', 2),
(51, 'en_attente', 'en_cours', 1),

-- Incident ID 7 (Embouteillage critique) : Signalement, validation puis résolu automatiquement après décoincement
(52, NULL, 'en_attente', 1),
(52, 'en_attente', 'valide', 1),
(52, 'valide', 'resolu', 1),

-- Incident ID 8 (Tuyau cassé) : Signalement initial par un citoyen
(53, NULL, 'en_attente', 2);


-- =========================
-- 8️⃣ NOTIFICATIONS
-- =========================

INSERT INTO notifications (utilisateur_id, message, lu)
VALUES
-- Alertes pour l'utilisateur 2 (Citoyen ayant signalé des incidents)
(2, 'Votre signalement concernant l\'inondation à Limete a été pris en charge par la commune.', false),
(2, 'Félicitations ! Un autre citoyen a confirmé l\'incident que vous avez signalé.', false),
(2, 'Le statut de votre incident "Ravin menaçant à Binza" est passé à : En cours.', false),
(2, 'Bonne nouvelle, l\'incident "Tuyau cassé à Barumbu" a été marqué comme Résolu !', false),
(2, 'Votre compte a été vérifié avec succès. Vous pouvez désormais voter et signaler sans restriction.', true),

-- Alertes pour l'utilisateur 1 (Agent / Administrateur / Autre citoyen)
(1, 'Un nouvel incident majeur (Érosion) vient d\'être signalé dans la commune de Ngaliema.', false),
(1, 'Attention : L\'incident "Embouteillage critique" a atteint plus de 5 confirmations.', false),
(1, 'Un nouveau commentaire a été posté sur l\'incident que vous suivez à Gombe.', false),
(1, 'Rappel : Vous avez un rapport d\'incident en attente de validation depuis plus de 24h.', false),
(1, 'Modifications de profil effectuées avec succès.', true);



-- =========================
-- 9️⃣ PREUVES
-- =========================

INSERT INTO preuves (incident_id, fichier, type_fichier)
VALUES
-- Preuves pour l'incident ID 2 (Inondation à Limete)
(50, 'limete_inondation_sous_station.jpg', 'image'),
(50, 'video_lumumba_eaux.mp4', 'video'),

-- Preuves pour l'incident ID 3 (Érosion à Ngaliema)
(51, 'ravin_binza_vue_haut.png', 'image'),
(51, 'progression_erosion_mulele.mp4', 'video'),

-- Preuve pour l'incident ID 4 (Panne d'électricité à Bandalungwa)
(52, 'cable_haute_tension_tousse.jpg', 'image'),

-- Preuve pour l'incident ID 5 (Nid de poule majeur à Kasa-Vubu)
(52, 'trou_huileries_degats.jpg', 'image'),

-- Preuves pour l'incident ID 6 (Tas d'immondices à Lemba)
(53, 'immondices_terminus_bypass1.png', 'image'),
(53, 'immondices_terminus_bypass2.jpg', 'image'),

-- Preuve pour l'incident ID 7 (Tuyau cassé à Barumbu)
(54, 'fuite_regideso_flambeau.mp4', 'video'),

-- Preuve pour l'incident ID 8 (Embouteillage à Limete)
(55, 'blocage_echangeur_camion.jpg', 'image');



INSERT INTO commentaires (contenu, utilisateur_id, incident_id)
VALUES 
-- Discussions autour de l'incident "Sous-station Limete inondée" (ID 51 en base)
('Est-ce que les voitures légères peuvent passer actuellement ?', 1, 51),
('Non, le boulevard est complètement bloqué, les moteurs se noient. Prenez ByPass.', 2, 51),
('La pluie de ce matin a vraiment fait d\'énormes dégâts ici...', 3, 51),
('Quelqu\'un sait si les agents de la mairie sont déjà sur place ?', 4, 51),
('Toujours rien, la situation est critique à l\'Échangeur.', 5, 51),
('Attention, une parcelle vient de s\'écrouler à cause du ravin !', 4, 51),
('Il faut couper l\'eau rapidement dans la zone pour limiter les dégâts.', 3, 51),
('C\'est une situation qui dure depuis des mois sans intervention sérieuse.', 2, 51),
('Les autorités communales ont promis de passer cet après-midi.', 1, 51),
('Prions qu\'il ne pleuve pas ce soir, sinon le quartier sera coupé en deux.', 1, 51);
