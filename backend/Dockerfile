# Backend Dockerfile
# バックエンドDockerfile
#
# Builds the PHP backend application using PHP 8.4 with Apache
# PHP 8.4とApacheを使用してPHPバックエンドアプリケーションをビルド

# Use PHP 8.4 with Apache as base image / PHP 8.4とApacheをベースイメージとして使用
FROM php:8.4-apache

# Install system dependencies required for mbstring extension
# mbstring拡張機能に必要なシステム依存関係をインストール
RUN apt-get update && apt-get install -y \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Install mbstring extension for string manipulation / 文字列操作のためのmbstring拡張機能をインストール
RUN docker-php-ext-install mbstring

# ============================================================================
# COMPOSER INSTALLATION (COMMENTED OUT - UNCOMMENT WHEN USING SMTP)
# Composerインストール（コメントアウト - SMTP使用時にコメントを解除）
# ============================================================================
# Uncomment the following lines when ready to use PHPMailer/SMTP:
# SMTP使用準備ができたら、以下の行のコメントを解除:
#
# Install system dependencies for Composer / Composer用のシステム依存関係をインストール
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     && rm -rf /var/lib/apt/lists/*
#
# Install Composer / Composerをインストール
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#
# Install PHP dependencies (PHPMailer) / PHP依存関係（PHPMailer）をインストール
# WORKDIR /var/www
# COPY composer.json composer.lock* ./
# RUN composer install --no-dev --optimize-autoloader

# Enable Apache mod_rewrite / Apache mod_rewriteを有効化
RUN a2enmod rewrite

# Set working directory to Apache document root / Apacheドキュメントルートに作業ディレクトリを設定
WORKDIR /var/www/html

# Note: Application files are mounted via volume in docker-compose.yml
# 注意：アプリケーションファイルはdocker-compose.ymlでボリュームとしてマウントされます

# Note: Port 80 is exposed by default in the php:8.4-apache image
# 注意：ポート80はphp:8.4-apacheイメージでデフォルトで公開されています