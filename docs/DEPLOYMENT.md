# Deployment Guide
# デプロイガイド

Complete guide for deploying the Contact Form application to production.
お問い合わせフォームアプリケーションを本番環境にデプロイするための完全なガイド。

## Prerequisites / 前提条件

- Server with Docker and Docker Compose installed / DockerとDocker Composeがインストールされたサーバー
- Domain name (optional) / ドメイン名（オプション）
- Email server configured (for production email sending) / メールサーバーが設定されている（本番メール送信用）

## Production Configuration / 本番環境設定

### Environment Variables / 環境変数

#### Backend / バックエンド

Set the following environment variable in `docker-compose.yml` or as a system environment variable:
`docker-compose.yml` またはシステム環境変数として以下の環境変数を設定：

```yaml
backend:
  environment:
    - APP_ENV=production
```

#### Frontend / フロントエンド

Update `frontend/vite.config.js` to use the production API URL:
`frontend/vite.config.js` を更新して本番API URLを使用：

```javascript
proxy: {
  '/api': {
    target: process.env.API_URL || 'https://yourdomain.com',
    changeOrigin: true,
  }
}
```

### Backend Configuration / バックエンド設定

Update `backend/src/Config.php` with production settings:
`backend/src/Config.php` を本番設定で更新：

```php
public static function getAllowedOrigins(): array
{
    return [
        'https://yourdomain.com',
        'https://www.yourdomain.com'
    ];
}

public static function getEmailFrom(): string
{
    return 'noreply@yourdomain.com';
}
```

## Deployment Methods / デプロイ方法

### Method 1: Docker Compose (Recommended) / 方法1: Docker Compose（推奨）

#### Step 1: Build and Start Services / ステップ1: サービスをビルドして起動

```bash
# Build images / イメージをビルド
docker-compose build

# Start services in detached mode / デタッチモードでサービスを起動
docker-compose up -d

# Check service status / サービスステータスを確認
docker-compose ps
```

#### Step 2: Configure Reverse Proxy (Optional) / ステップ2: リバースプロキシを設定（オプション）

If using a reverse proxy like Nginx:
Nginxなどのリバースプロキシを使用する場合：

```nginx
# Nginx configuration example / Nginx設定例
server {
    listen 80;
    server_name yourdomain.com;

    # Frontend / フロントエンド
    location / {
        proxy_pass http://localhost:5173;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }

    # Backend API / バックエンドAPI
    location /api {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

#### Step 3: Set Up SSL (Recommended) / ステップ3: SSLを設定（推奨）

Use Let's Encrypt for free SSL certificates:
無料のSSL証明書にLet's Encryptを使用：

```bash
sudo certbot --nginx -d yourdomain.com
```

### Method 2: Manual Deployment / 方法2: 手動デプロイ

#### Frontend Deployment / フロントエンドデプロイ

1. **Build the frontend / フロントエンドをビルド:**
   ```bash
   cd frontend
   npm install
   npm run build
   ```

2. **Deploy dist folder / distフォルダをデプロイ:**
   - Upload the `dist` folder to your web server / `dist` フォルダをWebサーバーにアップロード
   - Configure web server to serve static files / 静的ファイルを提供するようにWebサーバーを設定

#### Backend Deployment / バックエンドデプロイ

1. **Upload backend files / バックエンドファイルをアップロード:**
   ```bash
   # Upload backend directory / バックエンドディレクトリをアップロード
   scp -r backend/ user@server:/path/to/deployment/
   ```

2. **Configure web server / Webサーバーを設定:**
   - Point document root to `backend/public` / ドキュメントルートを `backend/public` にポイント
   - Ensure PHP 8.4+ is installed / PHP 8.4+がインストールされていることを確認
   - Enable required PHP extensions (mbstring) / 必要なPHP拡張機能（mbstring）を有効化

3. **Set permissions / 権限を設定:**
   ```bash
   chmod -R 755 backend/public
   ```

## Email Configuration / メール設定

### Development Mode / 開発モード

In development, emails are logged to error logs instead of being sent.
開発環境では、メールは送信されずにエラーログに記録されます。

### Production Mode / 本番モード

For production, configure PHP's `mail()` function or use an SMTP library:
本番環境では、PHPの `mail()` 関数を設定するか、SMTPライブラリを使用：

1. **Configure PHP mail() / PHP mail()を設定:**
   - Set up sendmail or SMTP in `php.ini` / `php.ini` でsendmailまたはSMTPを設定
   - Or use a service like SendGrid, Mailgun, etc. / またはSendGrid、Mailgunなどのサービスを使用

2. **Update EmailService.php (if using SMTP) / EmailService.phpを更新（SMTPを使用する場合）:**
   ```php
   // Example using PHPMailer / PHPMailerを使用する例
   use PHPMailer\PHPMailer\PHPMailer;
   
   // Configure SMTP settings / SMTP設定を構成
   $mail = new PHPMailer(true);
   $mail->isSMTP();
   $mail->Host = 'smtp.example.com';
   $mail->SMTPAuth = true;
   $mail->Username = 'your-email@example.com';
   $mail->Password = 'your-password';
   // ... more configuration
   ```

## Security Checklist / セキュリティチェックリスト

- [ ] Set `APP_ENV=production` / `APP_ENV=production` を設定
- [ ] Update CORS allowed origins in `Config.php` / `Config.php` のCORS許可オリジンを更新
- [ ] Use HTTPS for all connections / すべての接続にHTTPSを使用
- [ ] Configure proper file permissions / 適切なファイル権限を設定
- [ ] Remove or secure development files / 開発ファイルを削除または保護
- [ ] Set up firewall rules / ファイアウォールルールを設定
- [ ] Enable error logging (disable error display) / エラーログを有効化（エラー表示を無効化）
- [ ] Regularly update dependencies / 依存関係を定期的に更新

## Monitoring / 監視

### Logs / ログ

Monitor application logs:
アプリケーションログを監視：

```bash
# Docker logs / Dockerログ
docker-compose logs -f frontend
docker-compose logs -f backend

# PHP error logs / PHPエラーログ
tail -f /var/log/php/error.log
```

### Health Checks / ヘルスチェック

Set up health check endpoints:
ヘルスチェックエンドポイントを設定：

```bash
# Check frontend / フロントエンドをチェック
curl http://localhost:5173

# Check backend / バックエンドをチェック
curl http://localhost:8080/api.php
```

## Backup / バックアップ

### Database (if applicable) / データベース（該当する場合）

If you add a database in the future:
将来的にデータベースを追加する場合：

```bash
# Backup database / データベースをバックアップ
mysqldump -u user -p database_name > backup.sql
```

### Application Files / アプリケーションファイル

```bash
# Backup application / アプリケーションをバックアップ
tar -czf backup-$(date +%Y%m%d).tar.gz frontend/ backend/
```

## Troubleshooting / トラブルシューティング

### Common Issues / よくある問題

1. **Services not starting / サービスが起動しない:**
   ```bash
   # Check logs / ログを確認
   docker-compose logs
   
   # Restart services / サービスを再起動
   docker-compose restart
   ```

2. **CORS errors / CORSエラー:**
   - Verify allowed origins in `Config.php` / `Config.php` の許可オリジンを確認
   - Check that `APP_ENV=production` is set / `APP_ENV=production` が設定されていることを確認

3. **Email not sending / メールが送信されない:**
   - Check PHP mail configuration / PHPメール設定を確認
   - Verify email server is accessible / メールサーバーにアクセスできることを確認
   - Check error logs for details / 詳細についてはエラーログを確認

## Rollback / ロールバック

If deployment fails:
デプロイが失敗した場合：

```bash
# Stop current services / 現在のサービスを停止
docker-compose down

# Restore from backup / バックアップから復元
# ... restore files ...

# Restart services / サービスを再起動
docker-compose up -d
```

## Updates / 更新

To update the application:
アプリケーションを更新するには：

```bash
# Pull latest changes / 最新の変更をプル
git pull

# Rebuild and restart / リビルドして再起動
docker-compose build
docker-compose up -d
```

