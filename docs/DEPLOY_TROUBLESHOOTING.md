# Dépannage du déploiement (responsive / CSS / JS)

Si le site n'est pas responsive sur le VPS mais l'est localement, vérifie ces points dans l'ordre :

- **Fichiers bien présents** : Assure-toi que tous les fichiers CSS/JS se trouvent dans `/var/www/csar.sn/public` après le `git clone`.

- **URLs absolues vs relatives** : Vérifie que tes balises `<link>` et `<script>` utilisent des chemins relatifs ou commencent par `/`. Les URLs absolues vers `localhost` ou un autre domaine seront cassées en production.

- **MIME types et compression** : Nginx renvoie normalement les bons types. Si des fichiers `.css` ou `.js` sont servis avec un mauvais type, regarde `/var/log/nginx/error.log` et `access.log`.

- **Cache** : Pour tester, vide le cache du navigateur (CTRL+F5) ou ajoute temporairement `add_header Cache-Control "no-cache, no-store, must-revalidate";` dans la configuration `location` pour CSS/JS et recharge Nginx.

- **Headers CORS** : Si tes assets sont chargés depuis un autre domaine, vérifie les headers CORS.

- **Vérifier la compilation d'actifs** : Si ton projet utilise un builder (Mix, Vite, Webpack) assure-toi d'avoir généré les fichiers de production (ex : `npm ci && npm run build`) avant le déploiement ou pendant le déploiement. Sur le VPS, installe `nodejs` et `npm` temporairement pour builder si nécessaire.

- **PHP/Blade** : Si les templates injectent dynamiquement les liens, compare le HTML généré entre local et prod (View source) et repère les différences.

- **Réécriture URL / base href** : Si ton app utilise une `base href` ou des règles de réécriture, vérifie que `try_files` est configuré comme dans `deploy_hostinger.sh`.

- **Tester localement en mode production** : Sur ta machine locale, exécute un build de production et teste avec un serveur local (`php -S` ou `nginx`) pour reproduire l'environnement serveur.

Commandes utiles sur le serveur :

```bash
# Vérifier les logs nginx
sudo tail -n 200 /var/log/nginx/error.log
sudo tail -n 200 /var/log/nginx/access.log

# Vérifier que les fichiers existent
ls -la /var/www/csar.sn/public

# Forcer un build des assets (si repository contient package.json)
cd /var/www/csar.sn/public
sudo -E npm ci
sudo -E npm run build

# Recharger nginx
sudo systemctl reload nginx
```

Si tu veux, je peux :
- Générer une version du script qui installe aussi `nodejs` et build automatiquement les assets.
- T'expliquer comment créer une clé de déploiement Git (deploy key) pour éviter d'utiliser un token personnel.
