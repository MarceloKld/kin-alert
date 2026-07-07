\#ACCEDER DANS LEZS CONTENAIRE
R/docker exec -it pharmacie\_app bash





\#1.Tu veux redémarrer proprement
R/docker compose up

\#2.Tu modifies Dockerfile
R/docker compose up --build

\#3.Tu modifies docker-compose.yml
R/docker compose down puis up --build

\#4. Nouveau PC
#a. 1️⃣ Réinstalle les dépendances
R/composer install
#b. 2️⃣ Lance Docker proprement
R/docker compose down
docker compose up --build
#c. 3️⃣ Vérifie que les containers tournent
R/docker ps



\#4.Rebuild proprement
R/docker compose down -v
docker compose up --build

#5.Lancer les tests
R/php artisan test



