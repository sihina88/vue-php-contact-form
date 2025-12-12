<?php
/**
 * Email Service Class
 * メールサービスクラス
 * 
 * Handles email sending functionality for contact form submissions
 * お問い合わせフォーム送信のメール送信機能を処理
 * 
 * NOTE: SMTP implementation is commented out below
 * To enable SMTP:
 * 1. Install PHPMailer: composer install (in backend directory)
 * 2. Uncomment the SMTP code sections below
 * 3. Update Config.php to uncomment SMTP methods
 * 4. Set environment variables (SMTP_HOST, SMTP_USERNAME, etc.)
 * 5. Update api.php to use new sendContactEmail signature
 * 
 * 注意: SMTP実装は以下でコメントアウトされています
 * SMTPを有効にするには:
 * 1. PHPMailerをインストール: composer install (backendディレクトリで)
 * 2. 以下のSMTPコードセクションのコメントを解除
 * 3. Config.phpを更新してSMTPメソッドのコメントを解除
 * 4. 環境変数を設定（SMTP_HOST、SMTP_USERNAMEなど）
 * 5. api.phpを更新して新しいsendContactEmailシグネチャを使用
 */

// Uncomment when ready to use SMTP:
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

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

    // ============================================================================
    // SMTP IMPLEMENTATION (COMMENTED OUT - UNCOMMENT WHEN READY TO USE)
    // SMTP実装（コメントアウト - 使用準備ができたらコメントを解除）
    // ============================================================================
    //
    // This section contains the SMTP-based email sending implementation using PHPMailer.
    // It provides better reliability and deliverability compared to PHP mail().
    //
    // このセクションには、PHPMailerを使用したSMTPベースのメール送信実装が含まれています。
    // PHP mail()と比較して、より高い信頼性と配信性を提供します。
    //
    // To enable:
    // 1. Uncomment the sendContactEmailSMTP method below
    // 2. Uncomment the sendEmailViaSMTP method below
    // 3. Update api.php to call sendContactEmailSMTP instead of sendContactEmail
    // 4. Make sure Config.php SMTP methods are uncommented
    // 5. Set required environment variables
    //
    // 有効にするには:
    // 1. 以下のsendContactEmailSMTPメソッドのコメントを解除
    // 2. 以下のsendEmailViaSMTPメソッドのコメントを解除
    // 3. api.phpを更新してsendContactEmailの代わりにsendContactEmailSMTPを呼び出す
    // 4. Config.phpのSMTPメソッドのコメントが解除されていることを確認
    // 5. 必要な環境変数を設定

    // Uncomment the following method when ready to use SMTP:
    // SMTP使用準備ができたら、以下のメソッドのコメントを解除:
    /*
    public static function sendContactEmailSMTP(string $userEmail, string $name, string $message): void
    {
        $isDevelopment = Config::isDevelopment();
        
        // Sanitize input / 入力をサニタイズ
        $nameSafe = Validator::sanitize($name);
        $emailSafe = Validator::sanitize($userEmail);
        $messageSafe = Validator::sanitize($message);

        // Get email configuration / メール設定を取得
        $subject = Config::getEmailSubject();
        $fromEmail = Config::getEmailFrom();
        $adminEmail = Config::getAdminEmail();
        
        // Build email body for user (confirmation) / ユーザー用のメール本文を構築（確認）
        $userBody = "お問い合わせありがとうございました。\n\n";
        $userBody .= "以下の内容でお問い合わせを受け付けました。\n\n";
        $userBody .= "氏名: {$nameSafe}\n";
        $userBody .= "メールアドレス: {$emailSafe}\n\n";
        $userBody .= "お問い合わせ内容:\n{$messageSafe}\n";
        
        // Build email body for admin (notification) / 管理者用のメール本文を構築（通知）
        $adminBody = "新しいお問い合わせが届きました。\n\n";
        $adminBody .= "氏名: {$nameSafe}\n";
        $adminBody .= "メールアドレス: {$emailSafe}\n\n";
        $adminBody .= "お問い合わせ内容:\n{$messageSafe}\n";

        // Handle based on environment / 環境に応じて処理
        if ($isDevelopment) {
            // Development: Log emails / 開発環境：メールをログに記録
            self::logEmail($userEmail, $subject, $userBody);
            if ($adminEmail && $adminEmail !== 'admin@example.com') {
                self::logEmail($adminEmail, "【管理者通知】" . $subject, $adminBody);
            }
        } else {
            // Production: Send emails via SMTP / 本番環境：SMTP経由でメール送信
            // Send to user (confirmation) / ユーザーに送信（確認）
            self::sendEmailViaSMTP($userEmail, $subject, $userBody, $fromEmail);
            
            // Send to admin (notification) / 管理者に送信（通知）
            if ($adminEmail && $adminEmail !== 'admin@example.com') {
                self::sendEmailViaSMTP($adminEmail, "【管理者通知】" . $subject, $adminBody, $fromEmail);
            }
        }
    }

    private static function sendEmailViaSMTP(string $to, string $subject, string $body, string $fromEmail): void
    {
        $mail = new PHPMailer(true);
        
        try {
            // SMTP Configuration / SMTP設定
            $mail->isSMTP();
            $mail->Host = Config::getSmtpHost();
            $mail->SMTPAuth = true;
            $mail->Username = Config::getSmtpUsername();
            $mail->Password = Config::getSmtpPassword();
            $mail->SMTPSecure = Config::getSmtpEncryption(); // 'tls' or 'ssl'
            $mail->Port = Config::getSmtpPort();
            $mail->CharSet = 'UTF-8';
            
            // Optional: Enable verbose debug output (for troubleshooting)
            // オプション: 詳細なデバッグ出力を有効化（トラブルシューティング用）
            // $mail->SMTPDebug = 2; // Uncomment for debugging / デバッグ用にコメントを解除
            
            // Email content / メール内容
            $mail->setFrom($fromEmail, Config::getEmailFromName());
            $mail->addAddress($to);
            $mail->isHTML(false); // Plain text email / プレーンテキストメール
            $mail->Subject = $subject;
            $mail->Body = $body;
            
            $mail->send();
            error_log("Mail sent successfully via SMTP to: {$to}");
            
        } catch (Exception $e) {
            error_log("SMTP Mail sending failed - To: {$to} | Error: {$mail->ErrorInfo}");
            throw new Exception('メール送信に失敗しました。');
        }
    }
    */
}
