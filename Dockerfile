FROM php:8.2-apache


RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libzip-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++


RUN  docker-php-ext-install mysqli pdo pdo_mysql



# Install Composer
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer




COPY . /var/www/html

# Create a new user 'appuser' with specified UID and GID
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} appuser && \
    useradd -u ${UID} -g appuser -m appuser

# Change ownership of /var/www
RUN chown -R appuser:appuser /var/www

# Use the new user
USER appuser

# Set umask to 000 to allow group members to edit files
RUN echo "umask 000" >> /home/appuser/.bashrc