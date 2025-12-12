<?php
/**
 * Email Service Class
 * メールサービスクラス
 * 
 * Handles email sending functionality for contact form submissions
 * お問い合わせフォーム送信のメール送信機能を処理
 */
class EmailService
{
    /**
     * Send contact form email
     * お問い合わせフォームのメールを送信
     * 
     * Sends a confirmation email to the user with their inquiry details.
     * In development mode, logs email content instead of sending.
     * 
     * ユーザーに問い合わせ詳細を含む確認メールを送信。
     * 開発モードでは、送信せずにメール内容をログに出力。
     * 
     * @param string $to Recipient email address / 受信者のメールアドレス
     * @param string $name Sender name / 送信者名
     * @param string $email Sender email / 送信者のメールアドレス
     * @param string $message Message content / メッセージ内容
     * @return void
     * @throws Exception If email sending fails / メール送信に失敗した場合
     */
    public static function sendContactEmail(string $to, string $name, string $email, string $message): void
    {
        $isDevelopment = Config::isDevelopment();
        
        // Sanitize input for email body to prevent XSS / XSSを防ぐためにメール本文用に入力をサニタイズ
        $nameSafe = Validator::sanitize($name);
        $emailSafe = Validator::sanitize($email);
        $messageSafe = Validator::sanitize($message);

        // Get email configuration / メール設定を取得
        $subject = Config::getEmailSubject();
        $from = Config::getEmailFrom();
        
        // Build email body / メール本文を構築
        $body = "お問い合わせありがとうございました。\n\n";
        $body .= "以下の内容でお問い合わせを受け付けました。\n\n";
        $body .= "氏名: {$nameSafe}\n";
        $body .= "メールアドレス: {$emailSafe}\n\n";
        $body .= "お問い合わせ内容:\n{$messageSafe}\n";

        // Build email headers / メールヘッダーを構築
        $headers = "From: {$from}\r\n";
        $headers .= "Reply-To: {$from}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Handle based on environment / 環境に応じて処理
        if ($isDevelopment) {
            // Development environment: Log email content instead of sending
            // 開発環境：送信せずにメール内容をログに出力
            self::logEmail($to, $subject, $body);
        } else {
            // Production environment: Actually send email
            // 本番環境：実際にメール送信
            self::sendEmail($to, $subject, $body, $headers);
        }
    }

    /**
     * Log email content (development mode)
     * メール内容をログに出力（開発モード）
     * 
     * Outputs email details to error log for testing purposes
     * テスト目的でメール詳細をエラーログに出力
     * 
     * @param string $to Recipient / 受信者
     * @param string $subject Subject / 件名
     * @param string $body Body / 本文
     * @return void
     */
    private static function logEmail(string $to, string $subject, string $body): void
    {
        error_log("=== メール送信（開発モード） ===");
        error_log("To: {$to}");
        error_log("Subject: {$subject}");
        error_log("Body:\n{$body}");
        error_log("================================");
    }

    /**
     * Send email (production mode)
     * メールを送信（本番モード）
     * 
     * Sends email using PHP mail() function with proper error handling
     * 適切なエラーハンドリングでPHP mail()関数を使用してメールを送信
     * 
     * @param string $to Recipient / 受信者
     * @param string $subject Subject / 件名
     * @param string $body Body / 本文
     * @param string $headers Headers / ヘッダー
     * @return void
     * @throws Exception If sending fails / 送信に失敗した場合
     */
    private static function sendEmail(string $to, string $subject, string $body, string $headers): void
    {
        // Capture last error before mail() call / mail()呼び出し前の最後のエラーをキャプチャ
        // PHP's mail() doesn't throw exceptions, so we need to check error_get_last()
        // PHPのmail()は例外をスローしないため、error_get_last()をチェックする必要があります
        $lastError = error_get_last();
        $mailSent = mail($to, $subject, $body, $headers);

        // Check if mail() returned false or if there was an error / mail()がfalseを返したか、エラーが発生したかをチェック
        if (!$mailSent) {
            $error = error_get_last();
            $errorMessage = $error !== $lastError ? $error['message'] : 'Unknown mail error';

            // Log detailed error for debugging / デバッグ用に詳細なエラーをログに記録
            error_log("Mail sending failed - To: {$to} | Error: {$errorMessage}");
            // TODO: In production, consider using SMTP library (PHPMailer, SwiftMailer) for better reliability
            // TODO: 本番環境では、より高い信頼性のためにSMTPライブラリ（PHPMailer、SwiftMailer）の使用を検討

            throw new Exception('メール送信に失敗しました。');
        }

        // Log successful send for monitoring / 監視のために送信成功をログに記録
        // This helps track email delivery in production
        // これは本番環境でのメール配信を追跡するのに役立ちます
        error_log("Mail sent successfully to: {$to}");
    }
}
