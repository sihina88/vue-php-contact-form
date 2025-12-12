# API Documentation
# APIドキュメント

Complete API reference for the Contact Form backend.
お問い合わせフォームバックエンドの完全なAPIリファレンス。

## Base URL / ベースURL

- **Docker:** `http://localhost:8080`
- **Local Development:** `http://localhost:8000`

## Endpoints / エンドポイント

### POST /api.php

Submit a contact form inquiry.
お問い合わせフォームを送信します。

#### Request / リクエスト

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "name": "string (required, max 100 characters)",
  "email": "string (required, RFC-compliant email, max 255 characters)",
  "message": "string (required, max 5000 characters)"
}
```

**Example:**
```json
{
  "name": "山田 太郎",
  "email": "yamada@example.com",
  "message": "お問い合わせ内容です。"
}
```

#### Response / レスポンス

##### Success Response (200 OK) / 成功レスポンス（200 OK）

```json
{
  "success": true,
  "message": "送信しました。"
}
```

##### Validation Error Response (422 Unprocessable Entity) / バリデーションエラーレスポンス（422 Unprocessable Entity）

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": "氏名は必須です。",
    "email": "メールアドレスの形式が不正です。",
    "message": "お問い合わせ内容は必須です。"
  }
}
```

**Possible Error Messages / 可能なエラーメッセージ:**

- `"氏名は必須です。"` - Name field is required / 氏名フィールドは必須です
- `"氏名は100文字以内で入力してください。"` - Name exceeds maximum length / 氏名が最大文字数を超えています
- `"メールアドレスは必須です。"` - Email field is required / メールアドレスフィールドは必須です
- `"メールアドレスの形式が不正です。"` - Email format is invalid / メールアドレス形式が無効です
- `"メールアドレスは255文字以内で入力してください。"` - Email exceeds maximum length / メールアドレスが最大文字数を超えています
- `"お問い合わせ内容は必須です。"` - Message field is required / お問い合わせ内容フィールドは必須です
- `"お問い合わせ内容は5000文字以内で入力してください。"` - Message exceeds maximum length / お問い合わせ内容が最大文字数を超えています

##### Server Error Response (500 Internal Server Error) / サーバーエラーレスポンス（500 Internal Server Error）

```json
{
  "success": false,
  "message": "メール送信に失敗しました。"
}
```

##### Bad Request Response (400 Bad Request) / 不正リクエストレスポンス（400 Bad Request）

```json
{
  "success": false,
  "message": "Invalid JSON format"
}
```

or / または

```json
{
  "success": false,
  "message": "Invalid request"
}
```

##### Method Not Allowed Response (405 Method Not Allowed) / メソッド許可されていないレスポンス（405 Method Not Allowed）

```json
{
  "success": false,
  "message": "Method not allowed"
}
```

## Validation Rules / バリデーションルール

### Name / 氏名
- **Required:** Yes / 必須: はい
- **Type:** String / 文字列
- **Max Length:** 100 characters / 100文字
- **Validation:** Not empty after trimming / トリム後に空でないこと

### Email / メールアドレス
- **Required:** Yes / 必須: はい
- **Type:** String / 文字列
- **Max Length:** 255 characters / 255文字
- **Validation:** 
  - Not empty after trimming / トリム後に空でないこと
  - RFC-compliant email format / RFC準拠のメールアドレス形式
  - Validated using PHP `filter_var()` with `FILTER_VALIDATE_EMAIL` / PHP `filter_var()` と `FILTER_VALIDATE_EMAIL` でバリデーション

### Message / お問い合わせ内容
- **Required:** Yes / 必須: はい
- **Type:** String / 文字列
- **Max Length:** 5000 characters / 5000文字
- **Validation:** Not empty after trimming / トリム後に空でないこと

## Security / セキュリティ

### CORS / クロスオリジンリソース共有

The API implements CORS with the following behavior:
APIは以下の動作でCORSを実装しています：

- **Development Mode:** Allows requests from `http://localhost:*` / 開発モード: `http://localhost:*` からのリクエストを許可
- **Production Mode:** Only allows requests from whitelisted origins / 本番モード: ホワイトリストに登録されたオリジンのみ許可

CORS headers:
```
Access-Control-Allow-Origin: <origin>
Access-Control-Allow-Headers: Content-Type
Access-Control-Allow-Methods: POST, OPTIONS
Access-Control-Allow-Credentials: false
```

### XSS Protection / XSS保護

All user input is sanitized using `htmlspecialchars()` before being used in email content.
すべてのユーザー入力は、メールコンテンツで使用される前に `htmlspecialchars()` でサニタイズされます。

### Input Validation / 入力バリデーション

- JSON payload validation / JSONペイロードバリデーション
- Field length limits / フィールド文字数制限
- Required field validation / 必須フィールドバリデーション
- Email format validation / メールアドレス形式バリデーション

## Email Functionality / メール機能

### Development Mode / 開発モード

In development mode (when `APP_ENV` is not set to `production`), emails are logged to the error log instead of being sent.
開発モード（`APP_ENV` が `production` に設定されていない場合）では、メールは送信されずにエラーログに記録されます。

### Production Mode / 本番モード

In production mode, emails are sent using PHP's `mail()` function.
本番モードでは、PHPの `mail()` 関数を使用してメールが送信されます。

**Email Details:**
- **To:** User's email address (from form input) / ユーザーのメールアドレス（フォーム入力から）
- **From:** Configured in `Config.php` (default: `noreply@example.com`) / `Config.php` で設定（デフォルト: `noreply@example.com`）
- **Subject:** Configured in `Config.php` (default: `お問い合わせフォームからのメッセージ`) / `Config.php` で設定（デフォルト: `お問い合わせフォームからのメッセージ`）
- **Content-Type:** `text/plain; charset=UTF-8`

## Error Handling / エラーハンドリング

The API uses standard HTTP status codes:
APIは標準的なHTTPステータスコードを使用します：

- **200:** Success / 成功
- **400:** Bad Request (invalid JSON or request format) / 不正リクエスト（無効なJSONまたはリクエスト形式）
- **405:** Method Not Allowed (only POST is allowed) / メソッド許可されていない（POSTのみ許可）
- **422:** Unprocessable Entity (validation errors) / 処理できないエンティティ（バリデーションエラー）
- **500:** Internal Server Error (email sending failure) / 内部サーバーエラー（メール送信失敗）

All error responses include a `success: false` field and an appropriate error message.
すべてのエラーレスポンスには `success: false` フィールドと適切なエラーメッセージが含まれます。

## Example Usage / 使用例

### JavaScript / Fetch API

```javascript
const response = await fetch('/api/api.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: '山田 太郎',
    email: 'yamada@example.com',
    message: 'お問い合わせ内容です。'
  })
});

const data = await response.json();

if (data.success) {
  console.log('Success:', data.message);
} else {
  console.error('Error:', data.message);
  if (data.errors) {
    console.error('Validation errors:', data.errors);
  }
}
```

### cURL

```bash
curl -X POST http://localhost:8080/api.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "山田 太郎",
    "email": "yamada@example.com",
    "message": "お問い合わせ内容です。"
  }'
```

