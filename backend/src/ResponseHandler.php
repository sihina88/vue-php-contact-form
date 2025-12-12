<?php
/**
 * Response Handler Class
 * レスポンスハンドラークラス
 * 
 * Handles all HTTP response formatting and sending
 * すべてのHTTPレスポンスのフォーマットと送信を処理
 */
class ResponseHandler
{
    /**
     * Send JSON response
     * JSONレスポンスを送信
     * 
     * Formats and sends JSON response with appropriate HTTP status code
     * 適切なHTTPステータスコードでJSONレスポンスをフォーマットして送信
     * 
     * @param int $statusCode HTTP status code / HTTPステータスコード
     * @param array $data Response data / レスポンスデータ
     * @return void
     */
    public static function sendJson(int $statusCode, array $data): void
    {
        http_response_code($statusCode);
        
        // Encode data to JSON / データをJSONにエンコード
        $jsonResponse = json_encode($data);
        
        // Check for JSON encoding errors / JSONエンコードエラーをチェック
        if ($jsonResponse === false) {
            // Fallback if JSON encoding fails / JSONエンコードに失敗した場合のフォールバック
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'サーバーエラーが発生しました。',
            ]);
            
            // Log error in production / 本番環境でエラーをログに記録
            if (!Config::isDevelopment()) {
                error_log("Response encoding error: " . json_last_error_msg());
            }
            exit;
        }
        
        echo $jsonResponse;
    }

    /**
     * Send success response
     * 成功レスポンスを送信
     * 
     * Sends a standardized success response with 200 status code
     * 200ステータスコードで標準化された成功レスポンスを送信
     * 
     * @param string $message Success message / 成功メッセージ
     * @return void
     */
    public static function sendSuccess(string $message = '送信しました。'): void
    {
        self::sendJson(200, [
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Send error response
     * エラーレスポンスを送信
     * 
     * Sends a standardized error response with specified status code
     * 指定されたステータスコードで標準化されたエラーレスポンスを送信
     * 
     * @param int $statusCode HTTP status code / HTTPステータスコード
     * @param string $message Error message / エラーメッセージ
     * @param array|null $errors Validation errors (optional) / バリデーションエラー（オプション）
     * @return void
     */
    public static function sendError(int $statusCode, string $message, ?array $errors = null): void
    {
        $data = [
            'success' => false,
            'message' => $message,
        ];

        // Add validation errors if provided / 提供された場合はバリデーションエラーを追加
        if ($errors !== null) {
            $data['errors'] = $errors;
        }

        self::sendJson($statusCode, $data);
    }

    /**
     * Send validation error response
     * バリデーションエラーレスポンスを送信
     * 
     * Sends a 422 Unprocessable Entity response with validation errors
     * バリデーションエラーを含む422 Unprocessable Entityレスポンスを送信
     * 
     * @param array $errors Validation errors / バリデーションエラー
     * @return void
     */
    public static function sendValidationError(array $errors): void
    {
        self::sendError(422, 'Validation failed', $errors);
    }
}
