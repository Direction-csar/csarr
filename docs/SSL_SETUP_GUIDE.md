# SSL Certificate Setup Guide for CSAR Platform

## Overview
This guide provides comprehensive instructions for setting up SSL certificates for the CSAR platform across all subdomains:
- Main domain: `csar.sn`
- Subdomains: `admin.csar.sn`, `drh.csar.sn`, `dg.csar.sn`, `agent.csar.sn`, `entrepot.csar.sn`

## Prerequisites
Before setting up SSL certificates, ensure:
1. ✅ DNS A records are configured for all subdomains pointing to `153.92.211.42`
2. ✅ Apache virtual hosts are properly configured for all subdomains
3. ✅ The server is accessible on port 80 (HTTP)
4. ✅ Firewall allows incoming traffic on ports 80 and 443

## Option 1: Let's Encrypt with Certbot (Production Recommended)

### For Ubuntu VPS (Production)

#### Automated Setup
Use the provided script for automated SSL setup:

```bash
# Upload the script to your VPS
scp setup_ssl_certificates.sh root@153.92.211.42:/root/

# SSH into your VPS
ssh root@153.92.211.42

# Make the script executable
chmod +x setup_ssl_certificates.sh

# Run the SSL setup script
sudo ./setup_ssl_certificates.sh
```

#### Manual Setup
If you prefer manual setup:

```bash
# Update package list
sudo apt update

# Install Certbot and Apache plugin
sudo apt install -y certbot python3-certbot-apache

# Enable SSL module
sudo a2enmod ssl
sudo systemctl restart apache2

# Obtain SSL certificate for all domains
sudo certbot --apache \
  -d csar.sn \
  -d admin.csar.sn \
  -d drh.csar.sn \
  -d dg.csar.sn \
  -d agent.csar.sn \
  -d entrepot.csar.sn \
  --email admin@csar.sn \
  --agree-tos \
  --non-interactive \
  --redirect

# Test automatic renewal
sudo certbot renew --dry-run

# Enable automatic renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### For Windows Development

#### Using Certbot on Windows
1. Download Certbot for Windows from [https://certbot.eff.org/](https://certbot.eff.org/)
2. Install with IIS plugin support
3. Run as Administrator:

```powershell
cd 'C:\Program Files (x86)\Certbot'
.\certbot.exe certonly --apache `
  -d csar.sn `
  -d admin.csar.sn `
  -d drh.csar.sn `
  -d dg.csar.sn `
  -d agent.csar.sn `
  -d entrepot.csar.sn `
  --email admin@csar.sn `
  --agree-tos `
  --non-interactive
```

## Option 2: Self-Signed Certificate (Development Only)

### For Windows Development with XAMPP

#### Using OpenSSL (if available)
```powershell
# Create certificates directory
mkdir ssl-certificates

# Generate private key
openssl genrsa -out ssl-certificates\csar.sn.key 2048

# Create certificate signing request
openssl req -new -key ssl-certificates\csar.sn.key -out ssl-certificates\csar.sn.csr -subj "/C=SN/ST=Dakar/L=Dakar/O=CSAR/OU=IT/CN=csar.sn"

# Generate self-signed certificate
openssl x509 -req -days 365 -in ssl-certificates\csar.sn.csr -signkey ssl-certificates\csar.sn.key -out ssl-certificates\csar.sn.crt
```

#### Configure XAMPP Apache
1. Copy certificates to XAMPP:
   ```powershell
   copy ssl-certificates\csar.sn.crt C:\xampp\apache\conf\ssl.crt\
   copy ssl-certificates\csar.sn.key C:\xampp\apache\conf\ssl.key\
   ```

2. Update `C:\xampp\apache\conf\extra\httpd-ssl.conf`:
   ```apache
   SSLCertificateFile "conf/ssl.crt/csar.sn.crt"
   SSLCertificateKeyFile "conf/ssl.key/csar.sn.key"
   ```

3. Restart Apache

## Option 3: Windows Certificate Manager

1. Open **mmc.exe**
2. **File** > **Add/Remove Snap-in**
3. Select **Certificates** > **Add** > **Computer account** > **Local computer**
4. In **Personal** store, right-click > **All Tasks** > **Request New Certificate**
5. Create a self-signed certificate with the following properties:
   - Friendly name: `csar.sn`
   - Common name: `csar.sn`
   - Subject Alternative Names: All subdomains
6. Export the certificate and configure Apache to use it

## Post-SSL Setup Configuration

### Update Laravel .env File
After SSL certificates are installed, update your `.env` file:

```env
APP_URL=https://csar.sn
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.csar.sn
```

### Update Apache Virtual Hosts
Ensure your virtual hosts are configured for HTTPS. Here's an example for the main domain:

```apache
<VirtualHost *:443>
    ServerName csar.sn
    ServerAlias www.csar.sn
    DocumentRoot /var/www/csar-platform/public
    
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/csar.sn/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/csar.sn/privkey.pem
    Include /etc/letsencrypt/options-ssl-apache.conf
    
    <Directory /var/www/csar-platform/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Rewrite rule to map / to /public
    RewriteEngine On
    RewriteRule ^/$ /public/ [L]
</VirtualHost>
```

### Force HTTPS Redirect
Add this to your Apache configuration to redirect HTTP to HTTPS:

```apache
<VirtualHost *:80>
    ServerName csar.sn
    ServerAlias www.csar.sn admin.csar.sn drh.csar.sn dg.csar.sn agent.csar.sn entrepot.csar.sn
    
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
```

## Testing SSL Setup

### Test Certificate Installation
```bash
# Check certificate details
sudo certbot certificates

# Test HTTPS access
curl -I https://csar.sn
curl -I https://admin.csar.sn
curl -I https://drh.csar.sn
curl -I https://dg.csar.sn
curl -I https://agent.csar.sn
curl -I https://entrepot.csar.sn
```

### Test SSL Configuration
Use online tools to verify your SSL setup:
- [SSL Labs Server Test](https://www.ssllabs.com/ssltest/)
- [Why No Padlock](https://www.whynopadlock.com/)

## Troubleshooting

### Common Issues

#### Certificate Not Found
```bash
# Check certificate status
sudo certbot certificates

# Check Apache error logs
sudo tail -f /var/log/apache2/error.log
```

#### Mixed Content Warnings
Update all URLs in your application to use HTTPS:
- Update `APP_URL` in `.env`
- Update asset URLs in Blade templates
- Update database-stored URLs

#### Renewal Issues
```bash
# Test renewal process
sudo certbot renew --dry-run

# Check renewal timer status
sudo systemctl status certbot.timer

# Force renewal
sudo certbot renew --force-renewal
```

### Log Files
Monitor these log files for SSL-related issues:
- `/var/log/letsencrypt/letsencrypt.log`
- `/var/log/apache2/error.log`
- `/var/log/apache2/access.log`

## Maintenance

### Automatic Renewal
Let's Encrypt certificates are valid for 90 days. Certbot automatically renews them:
```bash
# Check renewal status
sudo systemctl status certbot.timer

# View renewal logs
sudo journalctl -u certbot.service --since "1 day ago"
```

### Certificate Management
```bash
# List all certificates
sudo certbot certificates

# Revoke a certificate
sudo certbot revoke --cert-path /etc/letsencrypt/live/csar.sn/fullchain.pem

# Delete a certificate
sudo certbot delete --cert-name csar.sn
```

## Security Best Practices

1. **Use Strong Cipher Suites**: Configure Apache with modern SSL/TLS settings
2. **Enable HSTS**: Add HTTP Strict Transport Security headers
3. **Regular Updates**: Keep Certbot and Apache updated
4. **Monitor Expiry**: Set up alerts for certificate expiration
5. **Backup Certificates**: Keep backups of your private keys

## Conclusion

SSL certificates are essential for:
- Securing user data transmission
- Building trust with visitors
- Improving SEO rankings
- Meeting modern web standards

Choose the appropriate option based on your environment:
- **Production**: Let's Encrypt with Certbot
- **Development**: Self-signed certificates
- **Enterprise**: Commercial SSL certificates

After completing SSL setup, your CSAR platform will be accessible securely via HTTPS across all subdomains.
