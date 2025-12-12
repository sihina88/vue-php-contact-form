# Frontend Dockerfile
# フロントエンドDockerfile
#
# Builds the Vue.js frontend application using Node.js
# Node.jsを使用してVue.jsフロントエンドアプリケーションをビルド

# Use Node.js 20 as base image / Node.js 20をベースイメージとして使用
FROM node:20

# Set working directory / 作業ディレクトリを設定
WORKDIR /app

# Copy package files first for better layer caching / レイヤーキャッシュのため、まずパッケージファイルをコピー
COPY package*.json ./

# Install dependencies / 依存関係をインストール
# Clean install to fix npm optional dependency bug
# npmのオプション依存関係のバグを修正するためにクリーンインストール
RUN rm -rf node_modules package-lock.json && \
    npm install

# Copy all application files / すべてのアプリケーションファイルをコピー
COPY . .

# Expose Vite development server port / Vite開発サーバーポートを公開
EXPOSE 5173

# Note: The actual command is specified in docker-compose.yml
# 注意：実際のコマンドはdocker-compose.ymlで指定されています