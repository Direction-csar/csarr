#!/bin/bash
set -euo pipefail

# scripts/deploy_hostinger.sh
# Usage:
# 1) Upload to your VPS (Ubuntu 22.04) as root or sudo user
# 2) Export your GitHub token in the shell: export GITHUB_TOKEN=ghp_xxx
#    (Do NOT hardcode the token in this file)
# 3) Run: sudo -E bash scripts/deploy_hostinger.sh csar.sn sultan2096 csar-sn

DOMAIN=${1:-}
GITHUB_USER=${2:-}
REPO=${3:-}
WEB_ROOT="/var/www/${DOMAIN}/public"
REPO_URL="https://github.com/${GITHUB_USER}/${REPO}.git"

if [ -z "$DOMAIN" ] || [ -z "$GITHUB_USER" ] || [ -z "$REPO" ]; then
  echo "Usage: sudo -E bash scripts/deploy_hostinger.sh <domain> <github-user> <repo>"
  exit 1
fi

if [ -z "${GITHUB_TOKEN:-}" ]; then
  echo "ERROR: Please set GITHUB_TOKEN environment variable before running this script."
  echo "Example: export GITHUB_TOKEN=ghp_xxx ; sudo -E bash scripts/deploy_hostinger.sh ${DOMAIN} ${GITHUB_USER} ${REPO}"
  exit 1
fi

echo "Starting deployment for ${DOMAIN} ..."

echo "1) Cleaning old web files and packages (will remove apache2, nodejs, npm)"
apt-get remove --purge -y apache2* nodejs npm || true
apt-get autoremove -y
apt-get update -y && apt-get upgrade -y

echo "2) Install required packages: nginx, certbot, git, php-fpm, unzip"
apt-get install -y nginx git certbot python3-certbot-nginx php-fpm unzip

echo "3) Prepare webroot"
rm -rf /var/www/${DOMAIN}
mkdir -p ${WEB_ROOT}
chown -R www-data:www-data /var/www/${DOMAIN}
chmod -R 755 /var/www/${DOMAIN}

echo "4) Clone repository using token (temporary HTTPS URL)"
cd ${WEB_ROOT}

# Initialize repo and fetch
git init
git remote add origin "https://${GITHUB_USER}:${GITHUB_TOKEN}@github.com/${GITHUB_USER}/${REPO}.git"
git fetch --depth=1 origin main
git reset --hard origin/main

chown -R www-data:www-data /var/www/${DOMAIN}

echo "5) Create Nginx site configuration"
NGINX_CONF=/etc/nginx/sites-available/${DOMAIN}
cat > ${NGINX_CONF} <<EOF
server {
    listen 80;
    server_name ${DOMAIN} www.${DOMAIN};

    root ${WEB_ROOT};
    index index.php index.html index.htm;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to dotfiles
    location ~ /\. { deny all; }

    # Serve static assets with cache-control but allow easy testing
    location ~* \.(js|css|png|jpg|jpeg|gif|svg|ico)$ {
        try_files $uri =404;
        access_log off;
        expires 7d;
        add_header Cache-Control "public, must-revalidate, proxy-revalidate";
    }
}
EOF

ln -sf ${NGINX_CONF} /etc/nginx/sites-enabled/${DOMAIN}
nginx -t && systemctl reload nginx

echo "6) Obtain Let's Encrypt certificate (non-interactive). Ensure DNS points to this server." 
certbot --nginx -d ${DOMAIN} -d www.${DOMAIN} --non-interactive --agree-tos -m admin@${DOMAIN} || {
  echo "Certbot failed. You can run: certbot --nginx -d ${DOMAIN} -d www.${DOMAIN} --agree-tos -m admin@${DOMAIN}"
}

echo "7) File permissions and final adjustments"
chown -R www-data:www-data /var/www/${DOMAIN}
find /var/www/${DOMAIN} -type d -exec chmod 755 {} \;
find /var/www/${DOMAIN} -type f -exec chmod 644 {} \;

echo "Deployment finished. Public site: https://${DOMAIN}"
echo "To update: cd ${WEB_ROOT} && sudo -E git pull origin main (ensure GITHUB_TOKEN is exported)"

echo "Notes and next steps:"
echo "- Do NOT store your GitHub token in files. Use environment variable or a deploy key."
echo "- If responsive issues occur, see DEPLOY_TROUBLESHOOTING.md"
