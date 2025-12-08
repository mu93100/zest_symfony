Routine normale après git pull

git pull
php bin/console cache:clear
symfony serve  # (ou juste relance si déjà lancé)

Le serveur Symfony recharge automatiquement les controllers/routes au hot reload, pas besoin de redémarrer sauf si tu modifies .env.local.

​
Exception : quand redémarrer le serveur

Seulement si tu modifies .env.local (DATABASE_URL, APP_ENV, etc.) → alors :

bash
symfony server:stop
symfony serve --no-tls

Ton setup est parfait maintenant

✅ .env → config Windows (commentée, pour référence)
✅ .env.local → config Mac/MAMP (ignorée par Git, prioritaire)

​
✅ Après git pull → tout reste cohérent automatiquement

