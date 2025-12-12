/**
 * Application Entry Point
 * アプリケーションエントリーポイント
 * 
 * Initializes Vue 3 application and loads global styles
 * Vue 3アプリケーションを初期化し、グローバルスタイルを読み込み
 */

import { createApp } from 'vue'
import App from './App.vue'

// Import global styles / グローバルスタイルをインポート
// Order matters: variables must be loaded first / 順序が重要：variablesを最初に読み込む必要がある
import './styles/variables.css'    // CSS variables (colors, spacing, etc.) / CSS変数（色、間隔など）
import './styles/base.css'         // Base styles and reset / ベーススタイルとリセット
import './styles/animations.css'   // Reusable animations / 再利用可能なアニメーション
import './styles/utilities.css'    // Utility classes / ユーティリティクラス

// Create and mount Vue application / Vueアプリケーションを作成してマウント
createApp(App).mount('#app')

