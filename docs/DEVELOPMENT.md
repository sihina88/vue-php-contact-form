# Development Guide
# 開発ガイド

Complete guide for developing and contributing to the Contact Form project.
お問い合わせフォームプロジェクトの開発と貢献のための完全なガイド。

## Prerequisites / 前提条件

### Required Software / 必要なソフトウェア

- **Node.js:** Version 20 or higher / バージョン20以上
- **PHP:** Version 8.4 or higher / バージョン8.4以上
- **Docker & Docker Compose:** Latest version (optional, for containerized development) / 最新バージョン（オプション、コンテナ化された開発用）
- **Git:** For version control / バージョン管理用

### Recommended Tools / 推奨ツール

- **VS Code** or **IntelliJ IDEA** - Code editor / コードエディタ
- **Postman** or **curl** - API testing / APIテスト
- **Browser DevTools** - Frontend debugging / フロントエンドデバッグ

## Project Setup / プロジェクトセットアップ

### Clone the Repository / リポジトリのクローン

```bash
git clone <repository-url>
cd vue-php-contact-form
```

### Docker Setup (Recommended) / Dockerセットアップ（推奨）

1. Start all services / すべてのサービスを起動:
```bash
docker-compose up
```

2. Access the application / アプリケーションにアクセス:
   - Frontend: http://localhost:5173
   - Backend: http://localhost:8080

3. Stop services / サービスを停止:
```bash
docker-compose down
```

### Local Setup / ローカルセットアップ

#### Frontend Setup / フロントエンドセットアップ

```bash
cd frontend
npm install
npm run dev
```

The frontend will be available at `http://localhost:5173`
フロントエンドは `http://localhost:5173` で利用可能です

**Environment Variables / 環境変数:**
```bash
# Override API URL for local development / ローカル開発用にAPI URLを上書き
API_URL=http://localhost:8000 npm run dev
```

#### Backend Setup / バックエンドセットアップ

```bash
cd backend/public
php -S localhost:8000
```

The backend API will be available at `http://localhost:8000/api.php`
バックエンドAPIは `http://localhost:8000/api.php` で利用可能です

**Note:** Make sure to update `frontend/vite.config.js` to use `http://localhost:8000` for the API proxy when running locally.
**注意:** ローカルで実行する場合は、`frontend/vite.config.js` のAPIプロキシを `http://localhost:8000` に更新してください。

## Project Structure / プロジェクト構造

### Frontend Structure / フロントエンド構造

```
frontend/
├── src/
│   ├── components/
│   │   └── ContactForm.vue      # Main contact form component / メインお問い合わせフォームコンポーネント
│   ├── styles/
│   │   ├── variables.css         # CSS variables (colors, spacing, etc.) / CSS変数（色、間隔など）
│   │   ├── base.css              # Base styles and reset / ベーススタイルとリセット
│   │   ├── animations.css        # Reusable animations / 再利用可能なアニメーション
│   │   └── utilities.css         # Utility classes / ユーティリティクラス
│   ├── App.vue                   # Root component / ルートコンポーネント
│   └── main.js                   # Application entry point / アプリケーションエントリーポイント
├── index.html                    # HTML template / HTMLテンプレート
├── vite.config.js                # Vite configuration / Vite設定
├── package.json                  # Dependencies and scripts / 依存関係とスクリプト
└── Dockerfile                    # Docker image definition / Dockerイメージ定義
```

### Backend Structure / バックエンド構造

```
backend/
├── public/
│   └── api.php                   # API endpoint (entry point) / APIエンドポイント（エントリーポイント）
├── src/
│   ├── Config.php                 # Configuration management / 設定管理
│   ├── CorsHandler.php           # CORS handling / CORS処理
│   ├── Validator.php              # Validation logic / バリデーションロジック
│   ├── EmailService.php           # Email service / メールサービス
│   └── ResponseHandler.php        # Response handling / レスポンス処理
└── Dockerfile                     # Docker image definition / Dockerイメージ定義
```

## Development Workflow / 開発ワークフロー

### Frontend Development / フロントエンド開発

1. **Start the development server / 開発サーバーを起動:**
   ```bash
   cd frontend
   npm run dev
   ```

2. **Make changes / 変更を加える:**
   - Edit Vue components in `src/components/`
   - Modify styles in `src/styles/`
   - Changes will hot-reload automatically / 変更は自動的にホットリロードされます

3. **Build for production / 本番用にビルド:**
   ```bash
   npm run build
   ```

### Backend Development / バックエンド開発

1. **Start the PHP server / PHPサーバーを起動:**
   ```bash
   cd backend/public
   php -S localhost:8000
   ```

2. **Make changes / 変更を加える:**
   - Edit API endpoint in `public/api.php`
   - Modify classes in `src/`
   - Restart the server to see changes / 変更を確認するにはサーバーを再起動

3. **Test the API / APIをテスト:**
   ```bash
   curl -X POST http://localhost:8000/api.php \
     -H "Content-Type: application/json" \
     -d '{"name":"Test","email":"test@example.com","message":"Test message"}'
   ```

## Code Style / コードスタイル

### Frontend / フロントエンド

- **Vue 3 Composition API:** Use `<script setup>` syntax / `<script setup>` 構文を使用
- **CSS:** Use CSS variables from `variables.css` / `variables.css` のCSS変数を使用
- **Naming:** 
  - Components: PascalCase (e.g., `ContactForm.vue`) / PascalCase（例: `ContactForm.vue`）
  - Variables: camelCase / camelCase
  - CSS classes: kebab-case / kebab-case

### Backend / バックエンド

- **PHP:** Follow PSR-12 coding standards / PSR-12コーディング標準に従う
- **Classes:** PascalCase (e.g., `EmailService`) / PascalCase（例: `EmailService`）
- **Methods:** camelCase / camelCase
- **Comments:** Bilingual (English and Japanese) / バイリンガル（英語と日本語）

## Testing / テスト

### Manual Testing / 手動テスト

1. **Frontend Testing / フロントエンドテスト:**
   - Test form validation / フォームバリデーションをテスト
   - Test all three screens (Input, Confirmation, Completion) / 3つの画面すべてをテスト（入力、確認、完了）
   - Test responsive design on different screen sizes / 異なる画面サイズでレスポンシブデザインをテスト
   - Test error handling / エラーハンドリングをテスト

2. **Backend Testing / バックエンドテスト:**
   - Test API endpoint with valid data / 有効なデータでAPIエンドポイントをテスト
   - Test validation errors / バリデーションエラーをテスト
   - Test CORS headers / CORSヘッダーをテスト
   - Test email functionality (check logs in development mode) / メール機能をテスト（開発モードでログを確認）

### API Testing / APIテスト

Use tools like Postman or curl to test the API:
Postmanやcurlなどのツールを使用してAPIをテスト：

```bash
# Test successful submission / 成功した送信をテスト
curl -X POST http://localhost:8000/api.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","message":"Test message"}'

# Test validation error / バリデーションエラーをテスト
curl -X POST http://localhost:8000/api.php \
  -H "Content-Type: application/json" \
  -d '{"name":"","email":"invalid-email","message":""}'
```

## Debugging / デバッグ

### Frontend Debugging / フロントエンドデバッグ

- Use browser DevTools console / ブラウザのDevToolsコンソールを使用
- Vue DevTools extension (recommended) / Vue DevTools拡張機能（推奨）
- Check network tab for API requests / APIリクエストのネットワークタブを確認

### Backend Debugging / バックエンドデバッグ

- Check PHP error logs / PHPエラーログを確認
- In development mode, email content is logged / 開発モードでは、メール内容がログに記録されます
- Use `error_log()` for custom debugging / カスタムデバッグに `error_log()` を使用

## Common Issues / よくある問題

### Frontend Issues / フロントエンドの問題

1. **API connection error / API接続エラー:**
   - Check that backend is running / バックエンドが実行されていることを確認
   - Verify API URL in `vite.config.js` / `vite.config.js` のAPI URLを確認

2. **Hot reload not working / ホットリロードが機能しない:**
   - Restart the dev server / 開発サーバーを再起動
   - Clear browser cache / ブラウザキャッシュをクリア

### Backend Issues / バックエンドの問題

1. **CORS errors / CORSエラー:**
   - Check `CorsHandler.php` configuration / `CorsHandler.php` の設定を確認
   - Verify origin is allowed / オリジンが許可されていることを確認

2. **Email not sending / メールが送信されない:**
   - In development mode, check error logs / 開発モードでは、エラーログを確認
   - In production, ensure mail server is configured / 本番環境では、メールサーバーが設定されていることを確認

## Best Practices / ベストプラクティス

1. **Code Organization / コード整理:**
   - Keep components small and focused / コンポーネントを小さく、焦点を絞って保つ
   - Use CSS variables for consistent styling / 一貫したスタイリングにCSS変数を使用
   - Separate concerns (validation, email, responses) / 関心の分離（バリデーション、メール、レスポンス）

2. **Security / セキュリティ:**
   - Always validate input on both client and server / クライアントとサーバーの両方で入力を常にバリデーション
   - Sanitize user input before using in emails / メールで使用する前にユーザー入力をサニタイズ
   - Use CORS whitelist in production / 本番環境でCORSホワイトリストを使用

3. **Performance / パフォーマンス:**
   - Use CSS variables for efficient styling / 効率的なスタイリングにCSS変数を使用
   - Optimize images and assets / 画像とアセットを最適化
   - Minimize API calls / API呼び出しを最小化

## Contributing / 貢献

See [CONTRIBUTING.md](./CONTRIBUTING.md) for contribution guidelines.
貢献ガイドラインについては [CONTRIBUTING.md](./CONTRIBUTING.md) を参照してください。

