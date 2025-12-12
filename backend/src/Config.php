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
}
