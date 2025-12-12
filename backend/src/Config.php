<?php
/**
 * Configuration Management Class
 * 設定管理クラス
 * 
 * Handles all application configuration including CORS, email, and validation settings
 * CORS、メール、バリデーション設定を含むアプリケーション設定を管理
 */
class Config
{
    /**
     * Get allowed CORS origins
     * 許可されたCORSオリジンを取得
     * 
     * @return array Array of allowed origin URLs
     * @return array 許可されたオリジンURLの配列
     */
    public static function getAllowedOrigins(): array
    {
        return [
            'http://localhost:5173',      // Local development / ローカル開発環境
            'http://localhost:3000',      // Alternative local port / 代替ローカルポート
            // TODO: Update these with your actual production domains
            // TODO: 実際の本番ドメインに更新してください
            'https://yourdomain.com',      // Production domain (update this) / 本番ドメイン（要更新）
            'https://www.yourdomain.com'   // Production domain with www (update this) / www付き本番ドメイン（要更新）
        ];
    }

    /**
     * Check if running in development mode
     * 開発モードで実行中かどうかをチェック
     * 
     * @return bool True if development mode, false if production
     * @return bool 開発モードの場合はtrue、本番環境の場合はfalse
     */
    public static function isDevelopment(): bool
    {
        return getenv('APP_ENV') !== 'production';
    }

    /**
     * Get email sender address
     * メール送信元アドレスを取得
     * 
     * @return string Email address for "From" header
     * @return string "From"ヘッダー用のメールアドレス
     */
    public static function getEmailFrom(): string
    {
        return 'noreply@example.com';
    }

    /**
     * Get email subject line
     * メール件名を取得
     * 
     * @return string Email subject
     * @return string メール件名
     */
    public static function getEmailSubject(): string
    {
        return 'お問い合わせフォームからのメッセージ';
    }

    /**
     * Get maximum length limits for form fields
     * フォームフィールドの最大文字数制限を取得
     * 
     * @return array Associative array with field names as keys and max lengths as values
     * @return array フィールド名をキー、最大文字数を値とする連想配列
     */
    public static function getMaxLengths(): array
    {
        return [
            'name' => 100,      // Maximum characters for name field / 氏名フィールドの最大文字数
            'email' => 255,     // Maximum characters for email field / メールアドレスフィールドの最大文字数
            'message' => 5000   // Maximum characters for message field / お問い合わせ内容フィールドの最大文字数
        ];
    }

    // ============================================================================
    // SMTP CONFIGURATION METHODS (COMMENTED OUT - UNCOMMENT WHEN READY TO USE)
    // SMTP設定メソッド（コメントアウト - 使用準備ができたらコメントを解除）
    // ============================================================================
    // 
    // To enable SMTP email sending:
    // 1. Uncomment the methods below
    // 2. Install Composer dependencies: composer install (in backend directory)
    // 3. Set environment variables in docker-compose.yml or .env file
    // 4. Uncomment SMTP code in EmailService.php
    // 5. Update api.php to use new EmailService signature
    //
    // SMTPメール送信を有効にするには:
    // 1. 以下のメソッドのコメントを解除
    // 2. Composer依存関係をインストール: composer install (backendディレクトリで)
    // 3. docker-compose.ymlまたは.envファイルで環境変数を設定
    // 4. EmailService.phpのSMTPコードのコメントを解除
    // 5. api.phpを更新して新しいEmailServiceシグネチャを使用
    //
    // Required environment variables:
    // - ADMIN_EMAIL: Admin email address for notifications
    // - SMTP_HOST: SMTP server hostname (e.g., smtp.gmail.com)
    // - SMTP_USERNAME: SMTP username/email
    // - SMTP_PASSWORD: SMTP password or app password
    // - SMTP_PORT: SMTP port (usually 587 for TLS, 465 for SSL)
    // - SMTP_ENCRYPTION: 'tls' or 'ssl'
    // - EMAIL_FROM_NAME: Display name for sender
    //
    // 必要な環境変数:
    // - ADMIN_EMAIL: 通知用の管理者メールアドレス
    // - SMTP_HOST: SMTPサーバーホスト名（例: smtp.gmail.com）
    // - SMTP_USERNAME: SMTPユーザー名/メールアドレス
    // - SMTP_PASSWORD: SMTPパスワードまたはアプリパスワード
    // - SMTP_PORT: SMTPポート（TLSは通常587、SSLは465）
    // - SMTP_ENCRYPTION: 'tls' または 'ssl'
    // - EMAIL_FROM_NAME: 送信者の表示名

    // Uncomment the following methods when ready to use SMTP:
    // SMTP使用準備ができたら、以下のメソッドのコメントを解除:
    /*
    // Get admin email address / 管理者メールアドレスを取得
    public static function getAdminEmail(): string
    {
        return getenv('ADMIN_EMAIL') ?: 'admin@example.com';
    }

    // Get SMTP host / SMTPホストを取得
    public static function getSmtpHost(): string
    {
        return getenv('SMTP_HOST') ?: 'smtp.gmail.com';
    }

    // Get SMTP username / SMTPユーザー名を取得
    public static function getSmtpUsername(): string
    {
        return getenv('SMTP_USERNAME') ?: '';
    }

    // Get SMTP password / SMTPパスワードを取得
    public static function getSmtpPassword(): string
    {
        return getenv('SMTP_PASSWORD') ?: '';
    }

    // Get SMTP port / SMTPポートを取得
    public static function getSmtpPort(): int
    {
        return (int)(getenv('SMTP_PORT') ?: 587);
    }

    // Get SMTP encryption method / SMTP暗号化方法を取得
    public static function getSmtpEncryption(): string
    {
        return getenv('SMTP_ENCRYPTION') ?: 'tls';
    }

    // Get email sender display name / メール送信者の表示名を取得
    public static function getEmailFromName(): string
    {
        return getenv('EMAIL_FROM_NAME') ?: 'Contact Form';
    }
    */
}
