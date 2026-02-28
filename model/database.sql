

-- TABLE `users` (Stocke les joueurs et leurs informations de profil)
CREATE TABLE users (
    id_user SERIAL PRIMARY KEY,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    d_nee DATE NOT NULL,
    progre INT DEFAULT 0,
    score INT DEFAULT 0,
    bio TEXT, -- Courte description de l’utilisateur
    avatar VARCHAR(255) DEFAULT 'default.jpg', -- Photo de profil par défaut
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date d'inscription
    derniere_connexion TIMESTAMP DEFAULT NULL, -- Dernière connexion
    compte_supprime BOOLEAN DEFAULT FALSE, -- Pour désactiver un compte sans le supprimer
    CHECK (EXTRACT(YEAR FROM AGE(d_nee)) >= 16) -- Vérifie que l'utilisateur a au moins 16 ans
);

-- TABLE `enigmes` (Stocke les énigmes du jeu)
CREATE TABLE enigmes (
    id_enigme SERIAL PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    contenu TEXT NOT NULL,
    reponse VARCHAR(100) NOT NULL,
    difficulte VARCHAR(20) CHECK (difficulte IN ('Facile', 'Moyen', 'Difficile'))
);

-- TABLE `progression` (Suivi des énigmes résolues par les joueurs)
CREATE TABLE progression (
    id_progression SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user) ON DELETE CASCADE,
    id_enigme INT REFERENCES enigmes(id_enigme) ON DELETE CASCADE,
    etat VARCHAR(20) CHECK (etat IN ('En cours', 'Réussi', 'Échoué')),
    temps_pris INT, -- En secondes
    date_resolution TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE `chatbot` (Messages échangés entre l'IA et le joueur)
CREATE TABLE chatbot (
    id_message SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user) ON DELETE CASCADE,
    sender VARCHAR(10) CHECK (sender IN ('Joueur', 'IA')),
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE `fichiers_corrompus` (Indices cachés dans des fichiers)
CREATE TABLE fichiers_corrompus (
    id_fichier SERIAL PRIMARY KEY,
    id_enigme INT REFERENCES enigmes(id_enigme) ON DELETE CASCADE,
    nom_fichier VARCHAR(100) NOT NULL,
    contenu TEXT NOT NULL,
    statut VARCHAR(20) CHECK (statut IN ('Trouvé', 'Non trouvé')) DEFAULT 'Non trouvé'
);

-- TABLE `admins` (Stocke les comptes administrateurs)
CREATE TABLE admins (
    id_admin SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user) ON DELETE CASCADE,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- TABLE `form` (Stocke les données du formulaire de contact)
CREATE TABLE form (
    id_form SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user) ON DELETE CASCADE,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    sujet VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (char_length(message) <= 1000) 
    -- Ici, on limite la taille du message à 1000 caractères,
    -- ce qui correspond approximativement à ~150 mots.
);


-- 🔹🔹🔹 INSERTION DE DONNÉES TEST 🔹🔹🔹

-- Ajouter un utilisateur test
INSERT INTO users (pseudo, nom, prenom, email, mot_de_passe, d_nee, bio, avatar)
VALUES ('ElinaBZ', 'BAZZAZ', 'Elina', 'ElinaBZ@gmail.com', 'motdepasse123', '2003-10-30', 
        'Passionnée de cybersécurité', 'avatars/elina.jpg');

-- Ajouter quelques énigmes test
INSERT INTO enigmes (titre, contenu, reponse, difficulte) 
VALUES 
('Corruption système', 'Trouve le bon checksum', '45A7C3', 'Difficile'),
('Protocole Inconnu', 'Trouve le port caché du serveur', '8080', 'Moyen');

-- Ajouter une progression test
INSERT INTO progression (id_user, id_enigme, etat, temps_pris) 
VALUES (1, 1, 'Réussi', 300);

-- Ajouter des messages du chatbot
INSERT INTO chatbot (id_user, sender, message) 
VALUES 
(1, 'IA', 'Bienvenue dans le jeu. Résous l’énigme pour avancer.'),
(1, 'Joueur', 'Je cherche un indice.');

-- Ajouter un fichier corrompu test
INSERT INTO fichiers_corrompus (id_enigme, nom_fichier, contenu) 
VALUES (1, 'log_system_error.txt', 'Erreur 45A7C3 détectée.');

-- Création automatique du user admin + liaison admins
WITH new_admin AS (
  INSERT INTO users (pseudo, nom, prenom, email, mot_de_passe, d_nee)
  VALUES (
    'AdminMaster',
    'Admin',
    'Master',
    'admin@deathrunners.com',
    'adminpassword',
    '2000-01-01'
  )
  RETURNING id_user
)
INSERT INTO admins (id_user, pseudo, email, mot_de_passe)
SELECT id_user,
       'AdminMaster',
       'admin@deathrunners.com',
       'adminpassword'
FROM new_admin;
