# Vue.js + PHP Contact Form
# Vue.js + PHP お問い合わせフォーム

A full-stack contact form application with Vue.js frontend and PHP backend, containerized with Docker.
Vue.jsフロントエンドとPHPバックエンドを使用したフルスタックのお問い合わせフォームアプリケーション。Dockerでコンテナ化されています。

## Tech Stack / 技術スタック

<!-- Frontend Technologies / フロントエンド技術 -->
![Vue.js](https://img.shields.io/badge/Vue.js-3.4.21-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-5.1.6-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

<!-- Backend Technologies / バックエンド技術 -->
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)

<!-- DevOps & Tools / DevOpsとツール -->
![Docker](https://img.shields.io/badge/Docker-24.0+-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Docker Compose](https://img.shields.io/badge/Docker_Compose-2.0+-2496ED?style=for-the-badge&logo=docker&logoColor=white)

<!-- Other / その他 -->
![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)

## Features / 機能

- Modern Vue 3 frontend with Vite / Viteを使用したモダンなVue 3フロントエンド
- PHP 8.4 backend API with validation / バリデーション機能付きPHP 8.4バックエンドAPI
- Docker Compose setup for easy deployment / 簡単なデプロイのためのDocker Composeセットアップ
- Form validation with real-time feedback / リアルタイムフィードバック付きフォームバリデーション
- Responsive design (mobile, tablet, desktop) / レスポンシブデザイン（モバイル、タブレット、デスクトップ）
- Japanese language support / 日本語サポート
- Three-screen flow (Input → Confirmation → Completion) / 3画面フロー（入力 → 確認 → 完了）
- Smooth animations and transitions / スムーズなアニメーションと遷移
- Security features (XSS protection, CORS, input validation) / セキュリティ機能（XSS保護、CORS、入力バリデーション）

## Quick Start / クイックスタート

### Using Docker Compose (Recommended) / Docker Composeを使用（推奨）

1. **Install Docker / Dockerをインストール:**
   - Download [Docker Desktop](https://www.docker.com/products/docker-desktop) for your OS
   - お使いのOS用の[Docker Desktop](https://www.docker.com/products/docker-desktop)をダウンロード

2. **Start all services / すべてのサービスを起動:**
```bash
docker compose up --build
```

3. **Access the application / アプリケーションにアクセス:**
   - Frontend: http://localhost:5173
   - Backend API: http://localhost:8080/api.php

4. **Stop services / サービスを停止:**
```bash
docker compose down
```

**For detailed Docker testing guide, see [DOCKER_TEST.md](./DOCKER_TEST.md)**
**詳細なDockerテストガイドについては、[DOCKER_TEST.md](./DOCKER_TEST.md)を参照してください**

### Local Development / ローカル開発

#### Frontend / フロントエンド

```bash
cd frontend
npm install
npm run dev
```

The frontend will be available at `http://localhost:5173`
フロントエンドは `http://localhost:5173` で利用可能です

#### Backend / バックエンド

```bash
cd backend/public
php -S localhost:8000
```

The backend API will be available at `http://localhost:8000/api.php`
バックエンドAPIは `http://localhost:8000/api.php` で利用可能です

**Note:** For local development, update `frontend/vite.config.js` to set the API proxy target to `http://localhost:8000` instead of `http://backend`.
**注意:** ローカル開発では、`frontend/vite.config.js` のAPIプロキシターゲットを `http://backend` から `http://localhost:8000` に変更してください。

## Project Structure / プロジェクト構造

```
.
├── frontend/                    # Vue.js frontend application / Vue.jsフロントエンドアプリケーション
│   ├── src/
│   │   ├── components/
│   │   │   └── ContactForm.vue  # Main contact form component / メインお問い合わせフォームコンポーネント
│   │   ├── styles/
│   │   │   ├── variables.css    # CSS variables / CSS変数
│   │   │   ├── base.css         # Base styles / ベーススタイル
│   │   │   ├── animations.css   # Animations / アニメーション
│   │   │   └── utilities.css    # Utility classes / ユーティリティクラス
│   │   ├── App.vue              # Root component / ルートコンポーネント
│   │   └── main.js              # Entry point / エントリーポイント
│   ├── Dockerfile
│   ├── package.json
│   └── vite.config.js
├── backend/                     # PHP backend API / PHPバックエンドAPI
│   ├── public/
│   │   └── api.php              # API endpoint / APIエンドポイント
│   ├── src/
│   │   ├── Config.php           # Configuration / 設定
│   │   ├── CorsHandler.php      # CORS handling / CORS処理
│   │   ├── Validator.php        # Validation logic / バリデーションロジック
│   │   ├── EmailService.php     # Email service / メールサービス
│   │   └── ResponseHandler.php  # Response handling / レスポンス処理
│   └── Dockerfile
├── docker-compose.yml           # Docker Compose configuration / Docker Compose設定
└── README.md                    # This file / このファイル
```

## API Documentation / APIドキュメント

See [API.md](./docs/API.md) for detailed API documentation.
詳細なAPIドキュメントについては [API.md](./docs/API.md) を参照してください。

### Quick API Reference / クイックAPIリファレンス

**Endpoint:** `POST /api.php`

**Request Body:**
```json
{
  "name": "Your Name",
  "email": "your.email@example.com",
  "message": "Your message"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "送信しました。"
}
```

**Validation Error Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": "氏名は必須です。",
    "email": "メールアドレスの形式が不正です。",
    "message": "お問い合わせ内容は必須です。"
  }
}
```

## Configuration / 設定

### Email Recipient / メール受信者

The email is sent to the **user's email address** that they enter in the contact form. This serves as a confirmation email to the user that their inquiry was received.
メールは、お問い合わせフォームでユーザーが入力した**ユーザーのメールアドレス**に送信されます。これは、お問い合わせが受け付けられたことをユーザーに確認するメールとして機能します。

### Frontend Environment Variables / フロントエンド環境変数

For local development, you can set the `API_URL` environment variable to override the API proxy target:
ローカル開発では、`API_URL` 環境変数を設定してAPIプロキシターゲットを上書きできます：

```bash
API_URL=http://localhost:8000 npm run dev
```

### Backend Configuration / バックエンド設定

Backend configuration can be modified in `backend/src/Config.php`:
バックエンド設定は `backend/src/Config.php` で変更できます：

- CORS allowed origins / CORS許可オリジン
- Email from address / メール送信元アドレス
- Email subject / メール件名
- Field length limits / フィールド文字数制限

## Development / 開発

See [DEVELOPMENT.md](./docs/DEVELOPMENT.md) for detailed development guide.
詳細な開発ガイドについては [DEVELOPMENT.md](./docs/DEVELOPMENT.md) を参照してください。

## Deployment / デプロイ

See [DEPLOYMENT.md](./docs/DEPLOYMENT.md) for deployment instructions.
デプロイ手順については [DEPLOYMENT.md](./docs/DEPLOYMENT.md) を参照してください。

## Architecture / アーキテクチャ

See [ARCHITECTURE.md](./docs/ARCHITECTURE.md) for architecture documentation.
アーキテクチャドキュメントについては [ARCHITECTURE.md](./docs/ARCHITECTURE.md) を参照してください。

## Technologies / 技術スタック

- **Frontend:** Vue 3, Vite, CSS3
- **Backend:** PHP 8.4, Apache
- **Containerization:** Docker, Docker Compose
- **Validation:** Client-side (Vue) and Server-side (PHP)
- **Security:** XSS protection, CORS, Input sanitization

