#!/bin/bash

# SSL Certificate Setup Script for CSAR Platform
# This script will install Certbot and obtain SSL certificates for all subdomains

set -e

echo "=== SSL Certificate Setup for CSAR Platform ==="
echo "Domain: csar.sn"
echo "Subdomains: admin.csar.sn, drh.csar.sn, dg.csar.sn, agent.csar.sn, entrepot.csar.sn"
echo ""

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root. Please use sudo."
   exit 1
fi

# Update package list
echo "Updating package list..."
apt update

# Install Certbot and Apache plugin
echo "Installing Certbot and Apache plugin..."
apt install -y certbot python3-certbot-apache

# Check if Apache is running
echo "Checking Apache status..."
systemctl status apache2 || systemctl start apache2

# Enable Apache SSL module
echo "Enabling Apache SSL module..."
a2enmod ssl
systemctl restart apache2

# Define domains
DOMAIN="csar.sn"
SUBDOMAINS=("admin" "drh" "dg" "agent" "entrepot")

# Create array of all domains to include in certificate
ALL_DOMAINS=("$DOMAIN")
for subdomain in "${SUBDOMAINS[@]}"; do
    ALL_DOMAINS+=("$subdomain.$DOMAIN")
done

# Build the certbot command arguments
DOMAIN_ARGS=""
for domain in "${ALL_DOMAINS[@]}"; do
    DOMAIN_ARGS="$DOMAIN_ARGS -d $domain"
done

echo "Requesting SSL certificate for: ${ALL_DOMAINS[*]}"
echo ""

# Obtain SSL certificate
echo "Obtaining SSL certificate..."
certbot --apache $DOMAIN_ARGS --email admin@csar.sn --agree-tos --non-interactive --redirect

# Check if certificate was obtained successfully
if [ $? -eq 0 ]; then
    echo "✅ SSL certificate obtained successfully!"
    
    # Test certificate renewal
    echo "Testing certificate renewal..."
    certbot renew --dry-run
    
    if [ $? -eq 0 ]; then
        echo "✅ Certificate renewal test passed!"
    else
        echo "⚠️  Certificate renewal test failed. Please check the configuration."
    fi
    
    # Display certificate information
    echo ""
    echo "Certificate information:"
    certbot certificates
    
    # Set up automatic renewal
    echo ""
    echo "Setting up automatic renewal..."
    systemctl enable certbot.timer
    systemctl start certbot.timer
    
    echo "✅ Automatic renewal configured!"
    
    # Restart Apache to apply changes
    echo "Restarting Apache..."
    systemctl restart apache2
    
    echo ""
    echo "=== SSL Setup Complete ==="
    echo "Your SSL certificates are now active for:"
    for domain in "${ALL_DOMAINS[@]}"; do
        echo "  - https://$domain"
    done
    echo ""
    echo "Certificates will be automatically renewed by Certbot."
    echo "You can check renewal status with: certbot renew --dry-run"
    
else
    echo "❌ Failed to obtain SSL certificate."
    echo "Please check the following:"
    echo "1. DNS records are properly configured for all subdomains"
    echo "2. Apache virtual hosts are correctly set up"
    echo "3. Port 80 is open and accessible"
    echo "4. Domain names are pointing to this server (153.92.211.42)"
    exit 1
fi
