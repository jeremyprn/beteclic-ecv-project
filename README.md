## Initialiser Docker

```
docker-compose up -d
docker-compose exec php composer install
```

```
Création de la base (si docker ne l'a pas fait):
docker-compose exec php bin/console d:d:c
```

```
Mettre à jour les tables
docker-compose exec php bin/console d:s:u -f
```

```
Insérer les sets de données symfony
docker-compose exec php bin/console d:f:l -n
```

```
Compiler les assets
npm run dev
```

---

#### Accéder à la base de données

localhost:8080
