<?php
/**
 * CORS (Cross-Origin Resource Sharing) Handler
 * CORS（クロスオリジンリソース共有）ハンドラー
 * 
 * Manages CORS headers and preflight requests for cross-origin API access
 * クロスオリジンAPIアクセスのためのCORSヘッダーとプリフライトリクエストを管理
 */
class CorsHandler
{
    /**
     * Handle CORS headers based on environment
     * 環境に基づいてCORSヘッダーを処理
     * 
     * Sets appropriate CORS headers:
     * - Development: Allows localhost origins
     * - Production: Only allows whitelisted origins
     * 
     * 適切なCORSヘッダーを設定：
     * - 開発環境：localhostオリジンを許可
     * - 本番環境：ホワイトリストに登録されたオリジンのみ許可
     * 
     * @return void
     */
    public static function handle(): void
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $isDevelopment = Config::isDevelopment();
        $allowedOrigins = Config::getAllowedOrigins();

        // In development, allow localhost origins; in production, only allow specific domains
        // 開発環境ではlocalhostオリジンを許可、本番環境では特定のドメインのみ許可
        if ($isDevelopment) {
            // Allow localhost for development / 開発環境ではlocalhostを許可
            // Using regex to match any port number (e.g., localhost:5173, localhost:3000)
            // 任意のポート番号に一致するように正規表現を使用（例：localhost:5173、localhost:3000）
            if (preg_match('/^http:\/\/localhost(:\d+)?$/', $origin)) {
                header("Access-Control-Allow-Origin: {$origin}");
            }
        } else {
            // Production: only allow whitelisted origins / 本番環境：ホワイトリストに登録されたオリジンのみ許可
            // Strict comparison (===) to prevent type coercion issues
            // 型強制の問題を防ぐために厳密な比較（===）を使用
            if (in_array($origin, $allowedOrigins, true)) {
                header("Access-Control-Allow-Origin: {$origin}");
            }
            // TODO: Consider adding logging for blocked CORS requests in production
            // TODO: 本番環境でブロックされたCORSリクエストのログ記録を検討
        }

        // Set CORS headers / CORSヘッダーを設定
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Credentials: false');
    }

    /**
     * Handle CORS preflight (OPTIONS) requests
     * CORSプリフライト（OPTIONS）リクエストを処理
     * 
     * Responds to browser preflight requests before actual POST request
     * 実際のPOSTリクエストの前にブラウザのプリフライトリクエストに応答
     * 
     * @return void
     */
    public static function handlePreflight(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}
