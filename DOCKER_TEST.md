# Docker Testing Guide
# Dockerテストガイド

Complete guide for testing the Contact Form application using Docker.
Dockerを使用してお問い合わせフォームアプリケーションをテストするための完全なガイド。

## Prerequisites / 前提条件

### Install Docker / Dockerのインストール

1. **Install Docker Desktop / Docker Desktopをインストール:**
   - **macOS:** Download from [Docker Desktop for Mac](https://www.docker.com/products/docker-desktop)
   - **Windows:** Download from [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop)
   - **Linux:** Follow [Docker Engine installation guide](https://docs.docker.com/engine/install/)

2. **Verify Installation / インストールを確認:**
   ```bash
   docker --version
   docker compose version
   ```

## Quick Start / クイックスタート

### Step 1: Build and Start Containers / ステップ1: コンテナをビルドして起動

From the project root directory:
プロジェクトのルートディレクトリから：

```bash
# Build and start all services / すべてのサービスをビルドして起動
docker compose up --build

# Or run in detached mode (background) / またはデタッチモード（バックグラウンド）で実行
docker compose up --build -d
```

### Step 2: Access the Application / ステップ2: アプリケーションにアクセス

Once containers are running:
コンテナが起動したら：

- **Frontend:** http://localhost:5173
- **Backend API:** http://localhost:8080/api.php

### Step 3: Test the Application / ステップ3: アプリケーションをテスト

1. **Open Frontend / フロントエンドを開く:**
   - Navigate to http://localhost:5173
   - You should see the contact form / お問い合わせフォームが表示されるはずです

2. **Test Form Submission / フォーム送信をテスト:**
   - Fill in the form fields / フォームフィールドに入力
   - Click "確認" (Confirm) button / 「確認」ボタンをクリック
   - Review the confirmation screen / 確認画面を確認
   - Click "送信" (Send) button / 「送信」ボタンをクリック
   - You should see the completion screen / 完了画面が表示されるはずです

3. **Check Backend Logs / バックエンドログを確認:**
   ```bash
   # View backend logs / バックエンドログを表示
   docker compose logs backend
   
   # Follow logs in real-time / リアルタイムでログを追跡
   docker compose logs -f backend
   ```

4. **Check Frontend Logs / フロントエンドログを確認:**
   ```bash
   # View frontend logs / フロントエンドログを表示
   docker compose logs frontend
   
   # Follow logs in real-time / リアルタイムでログを追跡
   docker compose logs -f frontend
   ```

## Testing Scenarios / テストシナリオ

### 1. Test Form Validation / フォームバリデーションをテスト

**Test Required Fields / 必須フィールドをテスト:**
- Leave name field empty → Should show error / 氏名フィールドを空にする → エラーが表示されるはず
- Leave email field empty → Should show error / メールアドレスフィールドを空にする → エラーが表示されるはず
- Leave message field empty → Should show error / お問い合わせ内容フィールドを空にする → エラーが表示されるはず

**Test Email Format / メールアドレス形式をテスト:**
- Enter invalid email (e.g., "invalid-email") → Should show error / 無効なメールアドレス（例：「invalid-email」）を入力 → エラーが表示されるはず
- Enter valid email (e.g., "test@example.com") → Should be accepted / 有効なメールアドレス（例：「test@example.com」）を入力 → 受け入れられるはず

**Test Real-time Validation / リアルタイムバリデーションをテスト:**
- Type in email field → Validation happens on blur / メールアドレスフィールドに入力 → blur時にバリデーションが実行される
- Fix error → Error message should disappear / エラーを修正 → エラーメッセージが消えるはず

### 2. Test Three-Screen Flow / 3画面フローをテスト

1. **Input Screen / 入力画面:**
   - Fill all fields correctly / すべてのフィールドを正しく入力
   - Click "確認" button / 「確認」ボタンをクリック
   - Should navigate to confirmation screen / 確認画面に遷移するはず

2. **Confirmation Screen / 確認画面:**
   - Verify all data is displayed correctly / すべてのデータが正しく表示されることを確認
   - Click "戻る" button → Should go back to input / 「戻る」ボタンをクリック → 入力画面に戻るはず
   - Click "送信" button → Should show loading state / 「送信」ボタンをクリック → ローディング状態が表示されるはず

3. **Completion Screen / 完了画面:**
   - Should show success message / 成功メッセージが表示されるはず
   - Click "新しいお問い合わせ" → Should reset form / 「新しいお問い合わせ」をクリック → フォームがリセットされるはず

### 3. Test Error Handling / エラーハンドリングをテスト

**Test Network Error / ネットワークエラーをテスト:**
```bash
# Stop backend container / バックエンドコンテナを停止
docker compose stop backend

# Try to submit form / フォームを送信してみる
# Should show network error message / ネットワークエラーメッセージが表示されるはず

# Restart backend / バックエンドを再起動
docker compose start backend
```

**Test Backend Validation / バックエンドバリデーションをテスト:**
- Submit form with invalid data directly to API / 無効なデータでAPIに直接フォームを送信
```bash
curl -X POST http://localhost:8080/api.php \
  -H "Content-Type: application/json" \
  -d '{"name":"","email":"invalid","message":""}'
```
- Should return validation errors / バリデーションエラーが返されるはず

### 4. Test Responsive Design / レスポンシブデザインをテスト

- **Desktop:** Open in browser and resize window / ブラウザで開いてウィンドウをリサイズ
- **Tablet:** Use browser dev tools to simulate tablet (768px - 1023px) / ブラウザの開発者ツールでタブレットをシミュレート（768px - 1023px）
- **Mobile:** Use browser dev tools to simulate mobile (<768px) / ブラウザの開発者ツールでモバイルをシミュレート（<768px）

### 5. Test Email Functionality / メール機能をテスト

**In Development Mode (Default) / 開発モード（デフォルト）:**
- Emails are logged instead of sent / メールは送信されずにログに記録される
- Check backend logs to see email content / バックエンドログを確認してメール内容を確認
```bash
docker compose logs backend | grep "メール送信"
```

**In Production Mode / 本番モード:**
```bash
# Set environment variable / 環境変数を設定
export APP_ENV=production

# Restart backend / バックエンドを再起動
docker compose restart backend
```

## Useful Docker Commands / 便利なDockerコマンド

### Container Management / コンテナ管理

```bash
# Start services / サービスを起動
docker compose up

# Start in background / バックグラウンドで起動
docker compose up -d

# Stop services / サービスを停止
docker compose stop

# Stop and remove containers / コンテナを停止して削除
docker compose down

# Restart services / サービスを再起動
docker compose restart

# View running containers / 実行中のコンテナを表示
docker compose ps

# View logs / ログを表示
docker compose logs

# View logs for specific service / 特定のサービスのログを表示
docker compose logs frontend
docker compose logs backend

# Follow logs / ログを追跡
docker compose logs -f
```

### Rebuilding / リビルド

```bash
# Rebuild containers / コンテナをリビルド
docker compose build

# Rebuild and restart / リビルドして再起動
docker compose up --build

# Rebuild without cache / キャッシュなしでリビルド
docker compose build --no-cache
```

### Debugging / デバッグ

```bash
# Execute command in running container / 実行中のコンテナでコマンドを実行
docker compose exec frontend sh
docker compose exec backend bash

# View container details / コンテナの詳細を表示
docker compose ps

# Inspect container / コンテナを検査
docker inspect <container-id>
```

## Troubleshooting / トラブルシューティング

### Port Already in Use / ポートが既に使用されている

**Error:** `bind: address already in use`

**Solution / 解決方法:**
```bash
# Find process using port / ポートを使用しているプロセスを検索
lsof -i :5173  # Frontend port
lsof -i :8080  # Backend port

# Kill process / プロセスを終了
kill -9 <PID>

# Or change ports in docker-compose.yml / またはdocker-compose.ymlでポートを変更
```

### Containers Won't Start / コンテナが起動しない

**Check logs / ログを確認:**
```bash
docker compose logs
```

**Common issues / よくある問題:**
- Missing dependencies / 依存関係の不足
- Port conflicts / ポートの競合
- Permission issues / 権限の問題

**Solution / 解決方法:**
```bash
# Rebuild from scratch / 最初からリビルド
docker compose down
docker compose build --no-cache
docker compose up
```

### Frontend Not Loading / フロントエンドが読み込まれない

**Check / 確認:**
1. Is frontend container running? / フロントエンドコンテナは実行中ですか？
   ```bash
   docker compose ps
   ```

2. Check frontend logs / フロントエンドログを確認
   ```bash
   docker compose logs frontend
   ```

3. Verify port 5173 is accessible / ポート5173にアクセスできることを確認
   ```bash
   curl http://localhost:5173
   ```

### Backend API Not Responding / バックエンドAPIが応答しない

**Check / 確認:**
1. Is backend container running? / バックエンドコンテナは実行中ですか？
   ```bash
   docker compose ps
   ```

2. Check backend logs / バックエンドログを確認
   ```bash
   docker compose logs backend
   ```

3. Test API directly / APIを直接テスト
   ```bash
   curl http://localhost:8080/api.php
   ```

### Changes Not Reflecting / 変更が反映されない

**Solution / 解決方法:**
- Frontend changes should hot-reload automatically / フロントエンドの変更は自動的にホットリロードされるはず
- Backend changes require container restart / バックエンドの変更にはコンテナの再起動が必要
  ```bash
  docker compose restart backend
  ```

## Testing Checklist / テストチェックリスト

- [ ] Frontend loads at http://localhost:5173 / フロントエンドが http://localhost:5173 で読み込まれる
- [ ] Backend API accessible at http://localhost:8080/api.php / バックエンドAPIが http://localhost:8080/api.php でアクセス可能
- [ ] Form validation works (required fields, email format) / フォームバリデーションが動作する（必須フィールド、メールアドレス形式）
- [ ] Real-time validation feedback works / リアルタイムバリデーションフィードバックが動作する
- [ ] Three-screen flow works (Input → Confirm → Complete) / 3画面フローが動作する（入力 → 確認 → 完了）
- [ ] Back button works on confirmation screen / 確認画面で戻るボタンが動作する
- [ ] Form submission works / フォーム送信が動作する
- [ ] Error handling works (network errors, validation errors) / エラーハンドリングが動作する（ネットワークエラー、バリデーションエラー）
- [ ] Responsive design works (mobile, tablet, desktop) / レスポンシブデザインが動作する（モバイル、タブレット、デスクトップ）
- [ ] Animations and transitions work smoothly / アニメーションと遷移がスムーズに動作する
- [ ] Email logging works in development mode / 開発モードでメールログ記録が動作する

## Next Steps / 次のステップ

After successful testing:
テストが成功したら：

1. Review the code / コードを確認
2. Check documentation / ドキュメントを確認
3. Test edge cases / エッジケースをテスト
4. Prepare for deployment / デプロイの準備

For deployment instructions, see [DEPLOYMENT.md](./docs/DEPLOYMENT.md)
デプロイ手順については、[DEPLOYMENT.md](./docs/DEPLOYMENT.md) を参照してください。

