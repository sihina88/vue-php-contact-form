# Architecture Documentation
# アーキテクチャドキュメント

Complete architecture overview of the Contact Form application.
お問い合わせフォームアプリケーションの完全なアーキテクチャ概要。

## System Overview / システム概要

The Contact Form application is a full-stack web application with a clear separation between frontend and backend:
お問い合わせフォームアプリケーションは、フロントエンドとバックエンドが明確に分離されたフルスタックWebアプリケーションです：

```
┌─────────────────┐         ┌─────────────────┐
│   Frontend      │         │    Backend      │
│   (Vue.js)      │────────▶│    (PHP)        │
│   Port: 5173    │  HTTP   │    Port: 8080   │
└─────────────────┘         └─────────────────┘
```

## Frontend Architecture / フロントエンドアーキテクチャ

### Technology Stack / 技術スタック

- **Framework:** Vue 3 (Composition API) / Vue 3（Composition API）
- **Build Tool:** Vite / Vite
- **Styling:** CSS3 with CSS Variables / CSS変数を使用したCSS3
- **State Management:** Vue Reactivity API (`ref`, `reactive`) / Vue Reactivity API（`ref`、`reactive`）

### Component Structure / コンポーネント構造

```
App.vue (Root)
└── ContactForm.vue
    ├── Input Screen (Step 1)
    ├── Confirmation Screen (Step 2)
    └── Completion Screen (Step 3)
```

### State Management / 状態管理

The application uses Vue's Composition API for state management:
アプリケーションは状態管理にVueのComposition APIを使用：

- **Form Data:** `reactive({ name, email, message })` / フォームデータ: `reactive({ name, email, message })`
- **Current Step:** `ref('input' | 'confirm' | 'complete')` / 現在のステップ: `ref('input' | 'confirm' | 'complete')`
- **Errors:** `ref({})` / エラー: `ref({})`
- **Loading State:** `ref(false)` / ローディング状態: `ref(false)`

### CSS Architecture / CSSアーキテクチャ

Modular CSS structure:
モジュラーCSS構造：

```
styles/
├── variables.css    # Design tokens (colors, spacing, etc.) / デザイントークン（色、間隔など）
├── base.css         # Global reset and base styles / グローバルリセットとベーススタイル
├── animations.css   # Reusable animations / 再利用可能なアニメーション
└── utilities.css    # Utility classes / ユーティリティクラス
```

### Form Flow / フォームフロー

```
1. Input Screen
   ├── User fills form / ユーザーがフォームに入力
   ├── Real-time validation / リアルタイムバリデーション
   └── Click "確認" button / 「確認」ボタンをクリック
       │
       ▼
2. Confirmation Screen
   ├── Display form data / フォームデータを表示
   ├── Staggered fade-in animation / 段階的フェードインアニメーション
   └── Click "送信" button / 「送信」ボタンをクリック
       │
       ▼
3. Completion Screen
   ├── Success message / 成功メッセージ
   ├── Bounce animation / バウンスアニメーション
   └── Click "新しいお問い合わせ" / 「新しいお問い合わせ」をクリック
       │
       ▼
   Reset to Input Screen / 入力画面にリセット
```

### Validation Strategy / バリデーション戦略

**Client-Side Validation / クライアントサイドバリデーション:**
- Real-time validation on `blur` and `input` events / `blur` と `input` イベントでのリアルタイムバリデーション
- RFC-compliant email validation using regex / 正規表現を使用したRFC準拠のメールアドレスバリデーション
- Visual feedback (error/valid states) / 視覚的フィードバック（エラー/有効状態）

**Server-Side Validation / サーバーサイドバリデーション:**
- All client-side validations are re-checked / すべてのクライアントサイドバリデーションが再チェック
- Additional security checks / 追加のセキュリティチェック
- Length limits enforced / 文字数制限が強制

## Backend Architecture / バックエンドアーキテクチャ

### Technology Stack / 技術スタック

- **Language:** PHP 8.4 / PHP 8.4
- **Web Server:** Apache / Apache
- **Architecture:** Modular class-based structure / モジュラークラスベース構造

### Class Structure / クラス構造

```
api.php (Entry Point)
│
├── Config.php           # Configuration management / 設定管理
├── CorsHandler.php      # CORS handling / CORS処理
├── Validator.php         # Input validation / 入力バリデーション
├── EmailService.php      # Email functionality / メール機能
└── ResponseHandler.php   # Response formatting / レスポンスフォーマット
```

### Request Flow / リクエストフロー

```
1. Request arrives at api.php
   │
   ├── Set JSON content type / JSONコンテンツタイプを設定
   ├── Handle CORS / CORSを処理
   └── Handle preflight (OPTIONS) / プリフライト（OPTIONS）を処理
       │
       ▼
2. Validate HTTP method (must be POST) / HTTPメソッドをバリデーション（POSTである必要がある）
       │
       ▼
3. Read and validate JSON input / JSON入力を読み込んでバリデーション
   ├── Read php://input / php://inputを読み込み
   ├── Validate JSON format / JSON形式をバリデーション
   └── Parse to array / 配列に解析
       │
       ▼
4. Extract and trim form data / フォームデータを抽出してトリム
   ├── name
   ├── email
   └── message
       │
       ▼
5. Validate form data / フォームデータをバリデーション
   ├── Check required fields / 必須フィールドをチェック
   ├── Check field lengths / フィールドの文字数をチェック
   └── Validate email format / メールアドレス形式をバリデーション
       │
       ├── If errors: Return 422 with errors / エラーがある場合: エラーとともに422を返す
       │
       ▼
6. Send email / メールを送信
   ├── Sanitize input (XSS protection) / 入力をサニタイズ（XSS保護）
   ├── Build email content / メールコンテンツを構築
   └── Send via mail() or log (dev mode) / mail()で送信またはログに記録（開発モード）
       │
       ├── If error: Return 500 / エラーの場合: 500を返す
       │
       ▼
7. Return success response (200) / 成功レスポンス（200）を返す
```

### Security Architecture / セキュリティアーキテクチャ

**Input Validation / 入力バリデーション:**
- JSON payload validation / JSONペイロードバリデーション
- Field length limits / フィールド文字数制限
- Required field checks / 必須フィールドチェック
- Email format validation (RFC-compliant) / メールアドレス形式バリデーション（RFC準拠）

**XSS Protection / XSS保護:**
- All user input sanitized with `htmlspecialchars()` / すべてのユーザー入力を `htmlspecialchars()` でサニタイズ
- Applied before using in email content / メールコンテンツで使用する前に適用

**CORS Security / CORSセキュリティ:**
- Development: Allows localhost origins / 開発環境: localhostオリジンを許可
- Production: Whitelist-based origin checking / 本番環境: ホワイトリストベースのオリジンチェック
- Credentials disabled / 資格情報を無効化

**Error Handling / エラーハンドリング:**
- Try-catch blocks for all critical operations / すべての重要な操作にtry-catchブロック
- Detailed error logging in production / 本番環境での詳細なエラーログ
- User-friendly error messages / ユーザーフレンドリーなエラーメッセージ

## Data Flow / データフロー

### Form Submission Flow / フォーム送信フロー

```
User Input (Frontend)
    │
    ├── Client-side validation / クライアントサイドバリデーション
    │
    ▼
Confirmation Screen
    │
    ├── User reviews data / ユーザーがデータを確認
    │
    ▼
Submit Button Clicked
    │
    ├── POST /api/api.php
    │   ├── JSON payload: { name, email, message }
    │   │
    │   ▼
    │   Server-side validation / サーバーサイドバリデーション
    │   │
    │   ├── If invalid: Return 422 with errors / 無効な場合: エラーとともに422を返す
    │   │
    │   ▼
    │   Sanitize input / 入力をサニタイズ
    │   │
    │   ▼
    │   Send email / メールを送信
    │   │
    │   ├── If error: Return 500 / エラーの場合: 500を返す
    │   │
    │   ▼
    │   Return 200 success / 200成功を返す
    │
    ▼
Frontend receives response
    │
    ├── If error: Show error message, return to input / エラーの場合: エラーメッセージを表示、入力画面に戻る
    │
    ▼
Completion Screen (if success) / 完了画面（成功の場合）
```

## Deployment Architecture / デプロイアーキテクチャ

### Docker Architecture / Dockerアーキテクチャ

```
┌─────────────────────────────────────┐
│      Docker Compose                 │
│                                     │
│  ┌───────────────┐  ┌─────────────┐ │
│  │  Frontend     │  │  Backend    │ │
│  │  Container    │  │  Container  │ │
│  │               │  │             │ │
│  │  Node.js 20   │  │  PHP 8.4    │ │
│  │  Port: 5173   │  │  Port: 80   │ │
│  └───────────────┘  └─────────────┘ │
│                                     │
└─────────────────────────────────────┘
```

### Volume Mounts / ボリュームマウント

- **Frontend:** `./frontend:/app` (for hot reload) / ホットリロード用
- **Backend:** `./backend/public:/var/www/html` (for code updates) / コード更新用

## Performance Considerations / パフォーマンスの考慮事項

### Frontend / フロントエンド

- **CSS Variables:** Efficient styling without repetition / 繰り返しなしの効率的なスタイリング
- **Lazy Loading:** Components loaded on demand / オンデマンドでコンポーネントを読み込み
- **Optimized Animations:** CSS transitions with hardware acceleration / ハードウェアアクセラレーションを使用したCSS遷移

### Backend / バックエンド

- **Modular Classes:** Easy to optimize and cache / 最適化とキャッシュが容易
- **Efficient Validation:** Early return on errors / エラー時の早期リターン
- **Error Logging:** Minimal overhead in production / 本番環境での最小限のオーバーヘッド

## Scalability / スケーラビリティ

### Current Architecture / 現在のアーキテクチャ

- **Stateless Backend:** Easy to scale horizontally / 水平スケーリングが容易
- **No Database:** Simple deployment / シンプルなデプロイ
- **Static Frontend:** Can be served via CDN / CDN経由で提供可能

### Future Enhancements / 将来の拡張

- Add database for storing inquiries / お問い合わせを保存するためのデータベースを追加
- Implement rate limiting / レート制限を実装
- Add caching layer / キャッシュレイヤーを追加
- Use message queue for email sending / メール送信にメッセージキューを使用

## Technology Decisions / 技術決定

### Why Vue 3? / なぜVue 3？

- Modern Composition API / モダンなComposition API
- Excellent developer experience / 優れた開発者体験
- Small bundle size / 小さなバンドルサイズ
- Great documentation / 優れたドキュメント

### Why PHP? / なぜPHP？

- Simple deployment / シンプルなデプロイ
- Built-in email functionality / 組み込みメール機能
- No additional dependencies / 追加の依存関係なし
- Easy to maintain / メンテナンスが容易

### Why Docker? / なぜDocker？

- Consistent development environment / 一貫した開発環境
- Easy deployment / 簡単なデプロイ
- Isolation of services / サービスの分離
- Reproducible builds / 再現可能なビルド

