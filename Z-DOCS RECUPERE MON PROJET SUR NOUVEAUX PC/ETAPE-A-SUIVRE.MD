# 1.Cloner le projet depuis GitHub
R/git clone <URL_DE_TON_DEPOT_PRIVE_GITHUB>
cd kin-alert

# 2.Installer les dépendances PHP
R/composer install

# 3.Créer et configurer ton fichier d'environnement .env
Le fichier .env contenant tes accès locaux n'est pas synchronisé. 
Tu dois dupliquer le fichier d'exemple fourni par Laravel :
R/cp src/.env.example src/.env
# Explication : Ouvre ensuite ce nouveau fichier src/.env avec VS Code et ajuste les configurations de ta base de données selon l'environnement de ce nouveau PC :

DB_HOST : 127.0.0.1 ou localhost (ou db si tu lances le MySQL du conteneur Docker sur ce PC).

DB_PORT : 3306 ou 3307 (selon le port MySQL disponible sur la nouvelle machine).

DB_DATABASE : kin_alert

DB_USERNAME : root (ou ton utilisateur configuré).

DB_PASSWORD : Le mot de passe de ton MySQL local.

# 4.Générer la clé de chiffrement Laravel
R/php artisan key:generate

# 5.Rebâtir la base de données instantanément
R/php artisan migrate
