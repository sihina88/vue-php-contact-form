# Vue.js Contact Form Frontend
# Vue.js お問い合わせフォーム フロントエンド

A modern Vue 3 contact form that connects to a PHP backend.
PHPバックエンドに接続するモダンなVue 3お問い合わせフォーム。

## Docker Setup (Recommended) / Dockerセットアップ（推奨）

The easiest way to run the project is using Docker Compose:
プロジェクトを実行する最も簡単な方法は、Docker Composeを使用することです：

```bash
# From project root / プロジェクトルートから
docker-compose up
```

Frontend will be available at `http://localhost:5173`
フロントエンドは `http://localhost:5173` で利用可能です

Backend will be available at `http://localhost:8080`
バックエンドは `http://localhost:8080` で利用可能です

## Local Development Setup / ローカル開発セットアップ

1. Install dependencies / 依存関係をインストール:
```bash
npm install
```

2. Update `vite.config.js` to use `http://localhost:8000` for the API proxy target (or set `API_URL` environment variable)
`vite.config.js` を更新して、APIプロキシターゲットに `http://localhost:8000` を使用する（または `API_URL` 環境変数を設定）

3. Start the development server / 開発サーバーを起動:
```bash
npm run dev
```

The app will be available at `http://localhost:5173`
アプリは `http://localhost:5173` で利用可能です

## Backend Setup (Local) / バックエンドセットアップ（ローカル）

Make sure your PHP backend is running. The Vite proxy is configured to forward `/api` requests to the backend.
PHPバックエンドが実行されていることを確認してください。Viteプロキシは `/api` リクエストをバックエンドに転送するように設定されています。

To start a PHP server / PHPサーバーを起動するには:
```bash
cd backend/public
php -S localhost:8000
```

## Build for Production / 本番用にビルド

```bash
npm run build
```

The built files will be in the `dist` directory.
ビルドされたファイルは `dist` ディレクトリにあります。

## Project Structure / プロジェクト構造

```
frontend/
├── src/
│   ├── components/
│   │   └── ContactForm.vue      # Main contact form component / メインお問い合わせフォームコンポーネント
│   ├── styles/
│   │   ├── variables.css         # CSS variables / CSS変数
│   │   ├── base.css              # Base styles / ベーススタイル
│   │   ├── animations.css        # Animations / アニメーション
│   │   └── utilities.css         # Utility classes / ユーティリティクラス
│   ├── App.vue                   # Root component / ルートコンポーネント
│   └── main.js                   # Entry point / エントリーポイント
├── index.html                     # HTML template / HTMLテンプレート
├── vite.config.js                # Vite configuration / Vite設定
└── package.json                  # Dependencies / 依存関係
```

## Features / 機能

- Three-screen flow (Input → Confirmation → Completion) / 3画面フロー（入力 → 確認 → 完了）
- Real-time form validation / リアルタイムフォームバリデーション
- Responsive design / レスポンシブデザイン
- Smooth animations and transitions / スムーズなアニメーションと遷移
- Error handling / エラーハンドリング
- Loading states / ローディング状態
