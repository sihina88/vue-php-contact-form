<?php
/**
 * Contact Form API Endpoint
 * お問い合わせフォームAPIエンドポイント
 * 
 * Main entry point for contact form submissions
 * Handles CORS, validation, and email sending
 * 
 * お問い合わせフォーム送信のメインエントリーポイント
 * CORS、バリデーション、メール送信を処理
 * 
 * TODO: Consider adding rate limiting to prevent spam
 * TODO: スパム防止のためのレート制限の追加を検討
 */

// Load required classes / 必要なクラスを読み込み
// Using require_once to prevent duplicate includes
// 重複インクルードを防ぐためにrequire_onceを使用
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/CorsHandler.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../src/EmailService.php';
require_once __DIR__ . '/../src/ResponseHandler.php';

// Set JSON content type / JSONコンテンツタイプを設定
header('Content-Type: application/json; charset=utf-8');

// Handle CORS / CORSを処理
CorsHandler::handle();
CorsHandler::handlePreflight();

// Validate HTTP method / HTTPメソッドをバリデーション
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ResponseHandler::sendError(405, 'Method not allowed');
    exit;
}

// Read and validate input / 入力を読み込んでバリデーション
try {
    // Read raw input / 生の入力を読み込み
    $raw = file_get_contents('php://input');
    if ($raw === false) {
        throw new Exception('Failed to read request body');
    }

    // Validate and parse JSON / JSONをバリデーションして解析
    $data = Validator::validateJson($raw);
} catch (Exception $e) {
    // Log error in production / 本番環境でエラーをログに記録
    if (!Config::isDevelopment()) {
        error_log("Input processing error: " . $e->getMessage());
    }

    // Determine error type and send appropriate response / エラータイプを判定して適切なレスポンスを送信
    $message = $e->getMessage();
    if (strpos($message, 'JSON') !== false) {
        ResponseHandler::sendError(400, 'Invalid JSON format');
    } else {
        ResponseHandler::sendError(400, 'Invalid request');
    }
    exit;
}

// Extract and trim form data / フォームデータを抽出してトリム
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$message = trim($data['message'] ?? '');

// Validate form data / フォームデータをバリデーション
$errors = Validator::validateContactForm([
    'name' => $name,
    'email' => $email,
    'message' => $message,
]);

// Return validation errors if any / バリデーションエラーがある場合は返却
if (!empty($errors)) {
    ResponseHandler::sendValidationError($errors);
    exit;
}

// Send email / メールを送信
try {
    // Send contact form email to user / ユーザーにお問い合わせフォームのメールを送信
    // Note: Email is sent to the user's own email as a confirmation
    // 注意: 確認として、ユーザー自身のメールアドレスにメールが送信されます
    EmailService::sendContactEmail($email, $name, $email, $message);
    
    // Send success response / 成功レスポンスを送信
    ResponseHandler::sendSuccess();
} catch (Exception $e) {
    // Log error details / エラー詳細をログに記録
    // In production, this helps with debugging email issues
    // 本番環境では、これはメールの問題のデバッグに役立ちます
    $errorMessage = $e->getMessage();
    if (!Config::isDevelopment()) {
        error_log("Email sending exception: {$errorMessage} | To: {$email}");
        // TODO: Consider sending error notification to admin email
        // TODO: 管理者メールにエラー通知を送信することを検討
    }

    // Send error response / エラーレスポンスを送信
    // Don't expose internal error details to client for security
    // セキュリティのため、内部エラー詳細をクライアントに公開しない
    ResponseHandler::sendError(500, $errorMessage);
    exit;
}
