#!/bin/bash
set -e

# Use PORT environment variable if set by Render (e.g. 10000), default to 80
PORT="${PORT:-80}"

# Dynamically set Apache port at container startup
sed -i "s/Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/*.conf

# Start Apache in foreground
exec apache2-foreground
