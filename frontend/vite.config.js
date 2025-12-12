import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ mode }) => {
  // Load environment variables from .env file
  // .envファイルから環境変数を読み込み
  const env = loadEnv(mode, process.cwd(), '')
  
  // Get API_URL from env file or process.env (for Docker)
  // 環境ファイルまたはprocess.envからAPI_URLを取得（Docker用）
  const apiUrl = env.API_URL || process.env.API_URL || 'http://localhost:8000'
  
  return {
    plugins: [vue()],
    server: {
      port: 5173,
      host: '0.0.0.0', // Allow external connections / 外部接続を許可
      strictPort: true, // Exit if port is already in use / ポートが既に使用されている場合は終了
      hmr: {
        // HMR configuration for Docker / Docker用のHMR設定
        host: 'localhost',
        port: 5173
      },
      proxy: {
        '/api': {
          // In Docker: uses service name 'backend' (port 80 internally)
          // For local dev: use 'http://localhost:8000' or set API_URL environment variable
          // Docker環境: サービス名'backend'を使用（内部でポート80）
          // ローカル開発: 'http://localhost:8000'を使用するか、API_URL環境変数を設定
          target: apiUrl,
          changeOrigin: true,
          rewrite: (path) => path.replace(/^\/api/, '')
        }
      }
    }
  }
})

