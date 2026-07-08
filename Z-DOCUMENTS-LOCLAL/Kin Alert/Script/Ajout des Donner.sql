USE kin_alert;

-- =========================
-- 1️⃣ UTILISATEURS
-- =========================

INSERT INTO utilisateurs (nom, email, mot_de_passe, telephone, role, statut)
VALUES 
('Admin KinAlert', 'admin@kinalert.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0817655587', 'administrateur', 'actif'),
('Jean Citoyen', 'jean@kinalert.com', '\$2y\$12\$QtlHaiIy5FnOS3.Ghz1eC.NtcyEq.AhxQZ5Zdih4oUgOs92EefjAa', '0994037996', 'utilisateur', 'actif');


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
('Gombe', 1),
('Lingwala', 1);


-- =========================
-- 4️⃣ TYPES INCIDENTS
-- =========================

INSERT INTO types_incidents (titre, description)
VALUES 
('Accident', 'Accident routier'),
('Vol', 'Vol à main armée');


-- =========================
-- 5️⃣ INCIDENTS
-- =========================

INSERT INTO incidents 
(titre, description, type_incident_id, latitude, longitude, commune_id, avenue, statut, nombre_confirmations, utilisateur_id)
VALUES
('Accident sur Boulevard', 'Collision entre deux véhicules', 1, -4.325, 15.322, 1, 'Boulevard 30 Juin', 'en_attente', 0, 2),
('Vol à Lingwala', 'Téléphone arraché', 2, -4.330, 15.300, 2, 'Avenue Kabinda', 'valide', 1, 2);


-- =========================
-- 6️⃣ CONFIRMATIONS
-- =========================

INSERT INTO confirmations (utilisateur_id, incident_id)
VALUES (1, 2);


-- =========================
-- 7️⃣ HISTORIQUE STATUTS
-- =========================

INSERT INTO historique_statuts (incident_id, ancien_statut, nouveau_statut, modifie_par)
VALUES
(2, NULL, 'en_attente', 2),
(2, 'en_attente', 'valide', 1);


-- =========================
-- 8️⃣ NOTIFICATIONS
-- =========================

INSERT INTO notifications (utilisateur_id, message, lu)
VALUES
(2, 'Votre incident a été validé.', false);


-- =========================
-- 9️⃣ PREUVES
-- =========================

INSERT INTO preuves (incident_id, fichier, type_fichier)
VALUES
(3, 'accident1.jpg', 'image'),
(2, 'vol1.mp4', 'video');