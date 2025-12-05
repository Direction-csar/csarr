# Notes de déploiement

Sécurité du token GitHub
- Ne commit pas ton token GitHub dans le dépôt. Cela le rend public et peut compromettre ton compte.
- Alternatives sûres :
  - Utiliser une deploy key (clé SSH) ajoutée au dépôt GitHub (lecture seule pour le clone).
  - Utiliser GitHub Actions + secrets pour déployer.
  - Exporter le token en variable d'environnement au moment du déploiement :

```bash
export GITHUB_TOKEN=ghp_xxx
sudo -E bash scripts/deploy_hostinger.sh csar.sn sultan2096 csar-sn
```

Mettre à jour le site sur le serveur
- Se connecter au serveur et s'assurer que `GITHUB_TOKEN` est exporté dans la session (ou configurer une clé SSH)
- Puis dans le dossier :

```bash
cd /var/www/csar.sn/public
sudo -E git pull origin main
sudo systemctl reload php8.1-fpm nginx
```

Déployer assets (si nécessaire)
- Si ton projet contient `package.json` et nécessite un build :

```bash
cd /var/www/csar.sn/public
sudo -E npm ci
sudo -E npm run build
sudo systemctl reload nginx
```

Créer une deploy key (SSH) recommandée
1) Sur le serveur : `ssh-keygen -t ed25519 -C "deploy@${DOMAIN}"` (ne pas mettre de passphrase)
2) Copier le contenu de `~/.ssh/id_ed25519.pub`
3) Dans GitHub > Repository > Settings > Deploy keys > Add key (donner accès "Read only")
4) Vérifier le clone via SSH : `git clone git@github.com:sultan2096/csar-sn.git`
