# Changelog
# 変更履歴

All notable changes to this project will be documented in this file.
このプロジェクトのすべての重要な変更は、このファイルに記録されます。

## [Unreleased] / [未リリース]

### Added / 追加
- Initial project setup with Vue 3 and PHP 8.4 / Vue 3とPHP 8.4での初期プロジェクトセットアップ
- Three-screen contact form flow (Input → Confirmation → Completion) / 3画面のお問い合わせフォームフロー（入力 → 確認 → 完了）
- Real-time form validation with visual feedback / 視覚的フィードバック付きリアルタイムフォームバリデーション
- Responsive design for mobile, tablet, and desktop / モバイル、タブレット、デスクトップ用のレスポンシブデザイン
- Smooth animations and transitions / スムーズなアニメーションと遷移
- Docker Compose setup for easy development / 簡単な開発のためのDocker Composeセットアップ
- Comprehensive documentation (API, Development, Deployment, Architecture) / 包括的なドキュメント（API、開発、デプロイ、アーキテクチャ）
- Bilingual support (English and Japanese) / バイリンガルサポート（英語と日本語）

### Security / セキュリティ
- XSS protection with input sanitization / 入力サニタイズによるXSS保護
- CORS configuration with whitelist support / ホワイトリストサポート付きCORS設定
- Input length validation / 入力文字数バリデーション
- JSON payload validation / JSONペイロードバリデーション

### Backend Improvements / バックエンドの改善
- Modular class structure (Config, CorsHandler, Validator, EmailService, ResponseHandler) / モジュラークラス構造（Config、CorsHandler、Validator、EmailService、ResponseHandler）
- Development mode email logging / 開発モードメールログ記録
- Comprehensive error handling / 包括的なエラーハンドリング
- RFC-compliant email validation / RFC準拠のメールアドレスバリデーション

### Frontend Improvements / フロントエンドの改善
- CSS architecture with variables, base styles, animations, and utilities / 変数、ベーススタイル、アニメーション、ユーティリティを含むCSSアーキテクチャ
- Staggered animations for confirmation screen / 確認画面の段階的アニメーション
- Loading states and error handling / ローディング状態とエラーハンドリング
- Real-time validation feedback / リアルタイムバリデーションフィードバック

## [1.0.0] - 2025-XX-XX

### Initial Release / 初回リリース
- Complete contact form application / 完全なお問い合わせフォームアプリケーション
- Full documentation / 完全なドキュメント
- Docker support / Dockerサポート
- Production-ready code / 本番環境対応コード

---

## Notes / 注意事項

### Known Issues / 既知の問題
- None currently / 現在なし

### Future Improvements / 今後の改善
- [ ] Add rate limiting to prevent spam / スパム防止のためのレート制限を追加
- [ ] Consider using SMTP library for better email reliability / より高いメール信頼性のためにSMTPライブラリの使用を検討
- [ ] Add error reporting service (e.g., Sentry) / エラー報告サービス（例：Sentry）を追加
- [ ] Add retry logic for network failures / ネットワーク障害時のリトライロジックを追加
- [ ] Add database support for storing inquiries / お問い合わせを保存するためのデータベースサポートを追加

