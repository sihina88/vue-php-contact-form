# SMTP Setup Guide
# SMTPセットアップガイド

This guide explains how to enable SMTP email sending using PHPMailer.
このガイドでは、PHPMailerを使用してSMTPメール送信を有効にする方法を説明します。

## Current Status / 現在の状態

- ✅ PHPMailer dependency added to `composer.json`
- ✅ SMTP code implemented (commented out)
- ✅ Configuration methods added to `Config.php` (commented out)
- ✅ Dockerfile updated with Composer installation instructions (commented out)

- ✅ PHPMailer依存関係が`composer.json`に追加済み
- ✅ SMTPコード実装済み（コメントアウト）
- ✅ `Config.php`に設定メソッド追加済み（コメントアウト）
- ✅ DockerfileにComposerインストール手順追加済み（コメントアウト）

## Steps to Enable SMTP / SMTPを有効にする手順

### Step 1: Install Composer Dependencies / Composer依存関係をインストール

```bash
cd backend
composer install
```

This will install PHPMailer and its dependencies.
これにより、PHPMailerとその依存関係がインストールされます。

### Step 2: Uncomment Dockerfile Changes / Dockerfileの変更をコメント解除

Edit `backend/Dockerfile` and uncomment the Composer installation section:
`backend/Dockerfile`を編集し、Composerインストールセクションのコメントを解除:

```dockerfile
# Uncomment these lines:
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader
```

### Step 3: Uncomment Config.php Methods / Config.phpのメソッドをコメント解除

Edit `backend/src/Config.php` and uncomment all SMTP configuration methods:
`backend/src/Config.php`を編集し、すべてのSMTP設定メソッドのコメントを解除:

- `getAdminEmail()`
- `getSmtpHost()`
- `getSmtpUsername()`
- `getSmtpPassword()`
- `getSmtpPort()`
- `getSmtpEncryption()`
- `getEmailFromName()`

### Step 4: Uncomment EmailService.php Methods / EmailService.phpのメソッドをコメント解除

Edit `backend/src/EmailService.php` and:
`backend/src/EmailService.php`を編集し:

1. Uncomment the `use` statement at the top:
   上部の`use`ステートメントのコメントを解除:
   ```php
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   ```

2. Uncomment the `sendContactEmailSMTP()` method
   `sendContactEmailSMTP()`メソッドのコメントを解除

3. Uncomment the `sendEmailViaSMTP()` method
   `sendEmailViaSMTP()`メソッドのコメントを解除

### Step 5: Update api.php / api.phpを更新

Edit `backend/public/api.php` and change:
`backend/public/api.php`を編集し、変更:

**From / 変更前:**
```php
EmailService::sendContactEmail($email, $name, $email, $message);
```

**To / 変更後:**
```php
EmailService::sendContactEmailSMTP($email, $name, $message);
```

### Step 6: Set Environment Variables / 環境変数を設定

Edit `docker-compose.yml` and uncomment the environment section for backend:
`docker-compose.yml`を編集し、バックエンドのenvironmentセクションのコメントを解除:

```yaml
backend:
  # ... other config ...
  environment:
    - APP_ENV=production
    - ADMIN_EMAIL=admin@yourdomain.com
    - SMTP_HOST=smtp.gmail.com
    - SMTP_USERNAME=your-email@gmail.com
    - SMTP_PASSWORD=your-app-password
    - SMTP_PORT=587
    - SMTP_ENCRYPTION=tls
    - EMAIL_FROM_NAME=Your Company Name
```

**Important Notes / 重要な注意事項:**

- For Gmail, you need to use an "App Password" instead of your regular password
- Generate an App Password at: https://myaccount.google.com/apppasswords
- Make sure 2-Step Verification is enabled on your Google account

- Gmailの場合、通常のパスワードの代わりに「アプリパスワード」を使用する必要があります
- アプリパスワードを生成: https://myaccount.google.com/apppasswords
- Googleアカウントで2段階認証が有効になっていることを確認してください

### Step 7: Rebuild Docker Containers / Dockerコンテナを再ビルド

```bash
docker compose down
docker compose up --build
```

## Testing / テスト

After enabling SMTP, test the contact form:
SMTPを有効にした後、お問い合わせフォームをテスト:

1. Submit a test form
   テストフォームを送信

2. Check that both user and admin receive emails
   ユーザーと管理者の両方がメールを受信することを確認

3. Check Docker logs for any SMTP errors:
   SMTPエラーのDockerログを確認:
   ```bash
   docker compose logs backend
   ```

## Troubleshooting / トラブルシューティング

### Enable SMTP Debug Mode / SMTPデバッグモードを有効化

In `EmailService.php`, uncomment this line:
`EmailService.php`で、この行のコメントを解除:

```php
$mail->SMTPDebug = 2; // Uncomment for debugging
```

This will output detailed SMTP communication to help diagnose issues.
これにより、問題の診断に役立つ詳細なSMTP通信が出力されます。

### Common Issues / よくある問題

1. **Authentication failed / 認証失敗**
   - Check SMTP_USERNAME and SMTP_PASSWORD
   - For Gmail, ensure you're using an App Password
   - SMTP_USERNAMEとSMTP_PASSWORDを確認
   - Gmailの場合、アプリパスワードを使用していることを確認

2. **Connection timeout / 接続タイムアウト**
   - Check SMTP_HOST and SMTP_PORT
   - Verify firewall settings
   - SMTP_HOSTとSMTP_PORTを確認
   - ファイアウォール設定を確認

3. **Emails not received / メールが受信されない**
   - Check spam/junk folder
   - Verify ADMIN_EMAIL is correct
   - Check Docker logs for errors
   - スパム/迷惑メールフォルダを確認
   - ADMIN_EMAILが正しいことを確認
   - エラーのDockerログを確認

## Benefits of SMTP / SMTPの利点

- ✅ Better email deliverability / より良いメール配信性
- ✅ Reliable email sending / 信頼性の高いメール送信
- ✅ Support for authentication / 認証のサポート
- ✅ Better error handling / より良いエラーハンドリング
- ✅ Sends to both user and admin / ユーザーと管理者の両方に送信

