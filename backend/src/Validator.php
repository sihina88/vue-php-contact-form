<?php
/**
 * Validation Class
 * バリデーションクラス
 * 
 * Handles all validation logic for form data, JSON parsing, and input sanitization
 * フォームデータ、JSON解析、入力サニタイズのすべてのバリデーションロジックを処理
 */
class Validator
{
    /**
     * Validate contact form data
     * お問い合わせフォームデータをバリデーション
     * 
     * Validates:
     * - Required fields (name, email, message)
     * - Field length limits
     * - Email format (RFC compliant)
     * 
     * バリデーション内容：
     * - 必須フィールド（氏名、メールアドレス、お問い合わせ内容）
     * - フィールドの文字数制限
     * - メールアドレス形式（RFC準拠）
     * 
     * @param array $data Form data (name, email, message) / フォームデータ（氏名、メールアドレス、お問い合わせ内容）
     * @return array Array of errors (empty if valid) / エラーの配列（有効な場合は空）
     */
    public static function validateContactForm(array $data): array
    {
        $errors = [];
        $maxLengths = Config::getMaxLengths();

        // Extract and trim form fields / フォームフィールドを抽出してトリム
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $message = trim($data['message'] ?? '');

        // Character length validation / 文字数制限チェック
        if (strlen($name) > $maxLengths['name']) {
            $errors['name'] = '氏名は' . $maxLengths['name'] . '文字以内で入力してください。';
        }

        if (strlen($email) > $maxLengths['email']) {
            $errors['email'] = 'メールアドレスは' . $maxLengths['email'] . '文字以内で入力してください。';
        }

        if (strlen($message) > $maxLengths['message']) {
            $errors['message'] = 'お問い合わせ内容は' . $maxLengths['message'] . '文字以内で入力してください。';
        }

        // Required field validation / 必須チェック
        if ($name === '') {
            $errors['name'] = '氏名は必須です。';
        }

        if ($email === '') {
            $errors['email'] = 'メールアドレスは必須です。';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // RFC compliant email validation / RFC準拠のメールアドレスチェック
            $errors['email'] = 'メールアドレスの形式が不正です。';
        }

        if ($message === '') {
            $errors['message'] = 'お問い合わせ内容は必須です。';
        }

        return $errors;
    }

    /**
     * Sanitize user input to prevent XSS attacks
     * XSS攻撃を防ぐためにユーザー入力をサニタイズ
     * 
     * Converts special characters to HTML entities
     * 特殊文字をHTMLエンティティに変換
     * 
     * @param string $input User input / ユーザー入力
     * @return string Sanitized input / サニタイズされた入力
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate and parse JSON input
     * JSON入力をバリデーションして解析
     * 
     * Validates JSON format and ensures decoded data is an array
     * JSON形式をバリデーションし、デコードされたデータが配列であることを確認
     * 
     * @param string $raw Raw JSON string / 生のJSON文字列
     * @return array Parsed data / 解析されたデータ
     * @throws Exception If JSON is invalid or empty / JSONが無効または空の場合
     */
    public static function validateJson(string $raw): array
    {
        // Check if input is empty / 入力が空かどうかをチェック
        if (empty($raw)) {
            throw new Exception('Request body is empty');
        }

        // Decode JSON / JSONをデコード
        $data = json_decode($raw, true);
        
        // Check for JSON decode errors / JSONデコードエラーをチェック
        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonError = json_last_error_msg();
            throw new Exception("Invalid JSON format: {$jsonError}");
        }

        // Validate that decoded data is an array / デコードされたデータが配列であることを確認
        if (!is_array($data)) {
            throw new Exception('Invalid request data format');
        }

        return $data;
    }
}
