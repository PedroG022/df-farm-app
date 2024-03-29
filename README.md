# DF Farm App

This README is in WIP

---

Requirements:

* PHP/Composer
* Symfony
* NodeJs
* Docker

---

Installation instructions:

```bash
git clone https://github.com/pedrog022/df-farm-app
cd ./df-farm-app/
composer install
npm install
npm run build
docker compose up --pull always -d --wait
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony serve
```