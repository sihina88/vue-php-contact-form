<template>
  <!-- Contact Form Container / お問い合わせフォームコンテナ -->
  <div class="contact-form-container">
    <!-- Single Transition Wrapper for all steps / すべてのステップ用の単一遷移ラッパー -->
    <transition :name="transitionName">
      <!-- Input Screen / 入力画面 -->
      <div v-if="currentStep === 'input'" key="input" class="step-container">
        <h1>お問い合わせ</h1>
        <!-- Form submission prevents default and calls handleConfirm / フォーム送信はデフォルトを防止し、handleConfirmを呼び出し -->
        <form @submit.prevent="handleConfirm" class="contact-form">
          <!-- Name Field / 氏名フィールド -->
          <div class="form-group">
            <label for="name">氏名 <span class="required">*</span></label>
            <!-- Real-time validation on blur and input / blurとinputでリアルタイムバリデーション -->
            <input
              id="name"
              v-model="form.name"
              type="text"
              :class="{ 'error': errors.name, 'valid': touched.name && !errors.name && form.name.trim() !== '' }"
              placeholder="お名前を入力してください"
              @blur="touched.name = true; validateName()"
              @input="touched.name && validateName()"
            />
            <!-- Error message with slide-down animation / スライドダウンアニメーション付きエラーメッセージ -->
            <transition name="slide-down">
              <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
            </transition>
          </div>

          <!-- Email Field / メールアドレスフィールド -->
          <div class="form-group">
            <label for="email">メールアドレス <span class="required">*</span></label>
            <!-- RFC-compliant email validation / RFC準拠のメールアドレスバリデーション -->
            <input
              id="email"
              v-model="form.email"
              type="email"
              :class="{ 'error': errors.email, 'valid': touched.email && !errors.email && form.email.trim() !== '' }"
              placeholder="メールアドレスを入力してください"
              @blur="touched.email = true; validateEmailField()"
              @input="touched.email && validateEmailField()"
            />
            <transition name="slide-down">
              <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
            </transition>
          </div>

          <!-- Message Field / お問い合わせ内容フィールド -->
          <div class="form-group">
            <label for="message">お問い合わせ内容 <span class="required">*</span></label>
            <textarea
              id="message"
              v-model="form.message"
              :class="{ 'error': errors.message, 'valid': touched.message && !errors.message && form.message.trim() !== '' }"
              placeholder="お問い合わせ内容を入力してください"
              rows="5"
              @blur="touched.message = true; validateMessage()"
              @input="touched.message && validateMessage()"
            ></textarea>
            <transition name="slide-down">
              <span v-if="errors.message" class="error-message">{{ errors.message }}</span>
            </transition>
          </div>

          <!-- Confirm Button / 確認ボタン -->
          <button type="submit" class="submit-btn">
            確認
          </button>

          <!-- General Error Message / 一般的なエラーメッセージ -->
          <transition name="slide-down">
            <div v-if="errors.general" class="error-message-general">
              {{ errors.general }}
            </div>
          </transition>
        </form>
      </div>

      <!-- Confirmation Screen / 確認画面 -->
      <div v-else-if="currentStep === 'confirm'" key="confirm" class="step-container">
        <h1>入力内容の確認</h1>
        <div class="confirm-content">
          <!-- Staggered fade animation for confirmation items / 確認項目の段階的フェードアニメーション -->
          <transition-group name="stagger-fade" tag="div" class="confirm-items-wrapper">
            <!-- Name Display / 氏名表示 -->
            <div key="name" class="confirm-item" :style="{ '--delay': 'var(--transition-stagger-delay-base)' }">
              <label>氏名</label>
              <span class="confirm-value">{{ form.name }}</span>
            </div>

            <!-- Email Display / メールアドレス表示 -->
            <div key="email" class="confirm-item" :style="{ '--delay': 'calc(var(--transition-stagger-delay-base) * 2)' }">
              <label>メールアドレス</label>
              <span class="confirm-value">{{ form.email }}</span>
            </div>

            <!-- Message Display / お問い合わせ内容表示 -->
            <div key="message" class="confirm-item" :style="{ '--delay': 'calc(var(--transition-stagger-delay-base) * 3)' }">
              <label>お問い合わせ内容</label>
              <div class="confirm-message">{{ form.message }}</div>
            </div>
          </transition-group>

          <!-- Action Buttons / アクションボタン -->
          <div class="button-group">
            <!-- Back Button / 戻るボタン -->
            <button @click="goBackToInput" class="back-btn">
              戻る
            </button>
            <!-- Submit Button with Loading State / ローディング状態付き送信ボタン -->
            <button @click="handleSubmit" :disabled="loading" class="submit-btn">
              <span v-if="loading" class="loading-content">
                <span class="spinner"></span>
                <span>送信中...</span>
              </span>
              <span v-else>送信</span>
            </button>
          </div>

          <!-- Error Message Display / エラーメッセージ表示 -->
          <transition name="slide-down">
            <div v-if="errorMessage" class="error-message-general">
              {{ errorMessage }}
            </div>
          </transition>
        </div>
      </div>

      <!-- Completion Screen / 完了画面 -->
      <div v-else-if="currentStep === 'complete'" key="complete" class="step-container">
        <div class="complete-content">
          <!-- Success Icon with Bounce Animation / バウンスアニメーション付き成功アイコン -->
          <div class="complete-icon">✓</div>
          <h1 class="complete-title">送信完了</h1>
          <p class="complete-message">
            お問い合わせありがとうございました。<br>
            内容を確認次第、ご連絡いたします。
          </p>
          <!-- Reset Form Button / フォームリセットボタン -->
          <button @click="resetForm" class="submit-btn">
            新しいお問い合わせ
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
/**
 * Contact Form Component
 * お問い合わせフォームコンポーネント
 * 
 * Three-screen contact form with validation and email submission
 * バリデーションとメール送信機能を持つ3画面のお問い合わせフォーム
 */

import { ref, reactive, computed } from 'vue'

// Current step: 'input', 'confirm', or 'complete' / 現在のステップ：'input'、'confirm'、または'complete'
const currentStep = ref('input')

// Transition direction: 'forward' or 'backward' / 遷移方向：'forward'または'backward'
const transitionDirection = ref('forward')

// Computed transition name based on current step and direction / 現在のステップと方向に基づく計算された遷移名
const transitionName = computed(() => {
  if (currentStep.value === 'complete') {
    return 'fade'
  }
  return transitionDirection.value === 'forward' ? 'slide-forward' : 'slide-backward'
})

// Form data (reactive) / フォームデータ（リアクティブ）
const form = reactive({
  name: '',      // Name / 氏名
  email: '',     // Email address / メールアドレス
  message: ''    // Message content / お問い合わせ内容
})

// Validation errors / バリデーションエラー
const errors = ref({})

// Loading state for submit button / 送信ボタンのローディング状態
const loading = ref(false)

// General error message / 一般的なエラーメッセージ
const errorMessage = ref('')

// Track which fields have been touched by user / ユーザーが触れたフィールドを追跡
const touched = ref({
  name: false,     // Name field touched / 氏名フィールドが触れられた
  email: false,    // Email field touched / メールアドレスフィールドが触れられた
  message: false   // Message field touched / お問い合わせ内容フィールドが触れられた
})

/**
 * RFC-compliant email validation
 * RFC準拠のメールアドレスバリデーション
 * 
 * @param {string} email Email address to validate / バリデーションするメールアドレス
 * @returns {boolean} True if valid, false otherwise / 有効な場合はtrue、それ以外はfalse
 * 
 * Note: This regex is based on RFC 5322 but simplified for practical use.
 * Backend also validates, so this is just for better UX.
 * 注意: この正規表現はRFC 5322に基づいていますが、実用的な使用のために簡略化されています。
 * バックエンドでもバリデーションするため、これはUX向上のためです。
 */
const validateEmail = (email) => {
  // RFC 5322 compliant regex (simplified version)
  // RFC 5322準拠の正規表現（簡略版）
  const rfcEmailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/
  return rfcEmailRegex.test(email)
  // TODO: Consider using a library like validator.js for more robust validation
  // TODO: より堅牢なバリデーションのためにvalidator.jsなどのライブラリの使用を検討
}

/**
 * Validate name field
 * 氏名フィールドをバリデーション
 * 
 * @returns {boolean} True if valid, false otherwise / 有効な場合はtrue、それ以外はfalse
 */
const validateName = () => {
  if (form.name.trim() === '') {
    errors.value.name = '氏名は必須です。'
    return false
  }
  delete errors.value.name
  return true
}

/**
 * Validate email field
 * メールアドレスフィールドをバリデーション
 * 
 * Checks for required field and RFC-compliant format
 * 必須フィールドとRFC準拠の形式をチェック
 * 
 * @returns {boolean} True if valid, false otherwise / 有効な場合はtrue、それ以外はfalse
 */
const validateEmailField = () => {
  if (form.email.trim() === '') {
    errors.value.email = 'メールアドレスは必須です。'
    return false
  } else if (!validateEmail(form.email.trim())) {
    errors.value.email = 'メールアドレスの形式が不正です。'
    return false
  }
  delete errors.value.email
  return true
}

/**
 * Validate message field
 * お問い合わせ内容フィールドをバリデーション
 * 
 * @returns {boolean} True if valid, false otherwise / 有効な場合はtrue、それ以外はfalse
 */
const validateMessage = () => {
  if (form.message.trim() === '') {
    errors.value.message = 'お問い合わせ内容は必須です。'
    return false
  }
  delete errors.value.message
  return true
}

/**
 * Validate all form fields
 * すべてのフォームフィールドをバリデーション
 * 
 * Marks all fields as touched and validates each field
 * すべてのフィールドをtouchedとしてマークし、各フィールドをバリデーション
 * 
 * @returns {boolean} True if all fields are valid, false otherwise / すべてのフィールドが有効な場合はtrue、それ以外はfalse
 */
const validateForm = () => {
  errorMessage.value = ''
  
  // Mark all fields as touched and run validation / すべてのフィールドをtouchedにしてバリデーション実行
  touched.value.name = true
  touched.value.email = true
  touched.value.message = true
  
  const nameValid = validateName()
  const emailValid = validateEmailField()
  const messageValid = validateMessage()

  return nameValid && emailValid && messageValid
}

/**
 * Handle confirm button click
 * 確認ボタンのクリックを処理
 * 
 * Validates form and navigates to confirmation screen if valid
 * フォームをバリデーションし、有効な場合は確認画面に遷移
 */
const handleConfirm = () => {
  if (validateForm()) {
    transitionDirection.value = 'forward'
    currentStep.value = 'confirm'
  }
}

/**
 * Navigate back to input screen
 * 入力画面に戻る
 * 
 * Resets transition direction and clears error messages
 * 遷移方向をリセットし、エラーメッセージをクリア
 */
const goBackToInput = () => {
  transitionDirection.value = 'backward'
  currentStep.value = 'input'
  errorMessage.value = ''
}

/**
 * Handle form submission
 * フォーム送信を処理
 * 
 * Validates form, sends data to API, and handles response
 * フォームをバリデーションし、データをAPIに送信し、レスポンスを処理
 * 
 * TODO: Add retry logic for network failures
 * TODO: ネットワーク障害時のリトライロジックを追加
 */
const handleSubmit = async () => {
  // Re-validate form before submission / 送信前に再度バリデーション
  // This is important because user might have changed data on confirmation screen
  // これは重要です。ユーザーが確認画面でデータを変更した可能性があるため
  if (!validateForm()) {
    transitionDirection.value = 'backward'
    currentStep.value = 'input'
    return
  }

  errorMessage.value = ''
  loading.value = true

  try {
    // Send POST request to API / APIにPOSTリクエストを送信
    // Note: Using /api/api.php because Vite proxy rewrites /api to backend
    // 注意: Viteプロキシが/apiをバックエンドに書き換えるため、/api/api.phpを使用
    const response = await fetch('/api/api.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: form.name,
        email: form.email,
        message: form.message
      })
    })

    const data = await response.json()

    if (!response.ok) {
      // Handle error response / エラーレスポンスを処理
      transitionDirection.value = 'backward'
      if (data.errors) {
        // Validation errors from backend / バックエンドからのバリデーションエラー
        errors.value = data.errors
        currentStep.value = 'input'
      } else {
        // General error message / 一般的なエラーメッセージ
        errors.value = { general: data.message || 'エラーが発生しました。もう一度お試しください。' }
        currentStep.value = 'input'
      }
    } else {
      // Success: Navigate to completion screen / 成功：完了画面に遷移
      transitionDirection.value = 'forward'
      currentStep.value = 'complete'
    }
  } catch (error) {
    // Network error handling / ネットワークエラーの処理
    // This catches fetch failures, timeouts, etc.
    // これはfetchの失敗、タイムアウトなどをキャッチします
    transitionDirection.value = 'backward'
    errors.value = { general: 'ネットワークエラーが発生しました。接続を確認して再度お試しください。' }
    currentStep.value = 'input'
    console.error('Error:', error)
    // TODO: Consider adding error reporting service (e.g., Sentry) in production
    // TODO: 本番環境でエラー報告サービス（例：Sentry）の追加を検討
  } finally {
    // Always reset loading state / 常にローディング状態をリセット
    // Even if request fails, we need to allow user to try again
    // リクエストが失敗しても、ユーザーが再試行できるようにする必要があります
    loading.value = false
  }
}

/**
 * Reset form to initial state
 * フォームを初期状態にリセット
 * 
 * Clears all form data, errors, and returns to input screen
 * すべてのフォームデータとエラーをクリアし、入力画面に戻る
 */
const resetForm = () => {
  transitionDirection.value = 'backward'
  form.name = ''
  form.email = ''
  form.message = ''
  errors.value = {}
  errorMessage.value = ''
  touched.value = {
    name: false,
    email: false,
    message: false
  }
  currentStep.value = 'input'
}
</script>

<style scoped>
/**
 * Component-specific Styles
 * コンポーネント固有のスタイル
 * 
 * All styles use CSS variables defined in variables.css
 * すべてのスタイルはvariables.cssで定義されたCSS変数を使用
 */

/* Container Styles / コンテナスタイル */
.contact-form-container {
  background: var(--color-white);
  border-radius: var(--radius-xl);
  padding: var(--spacing-4xl);
  box-shadow: var(--shadow-lg);
  /* --- CSS Grid Overlap Fix --- */
  /* Use CSS Grid to force all step containers to overlap in the same grid cell */
  /* This prevents the "double height" jump when Vue adds both old and new pages to DOM */
  display: grid;
  grid-template-areas: "stack";
  min-height: 400px;
  position: relative;
  overflow: hidden;
}

/* Optimize transitions to prevent white flash / 白いフラッシュを防ぐために遷移を最適化 */
/* Note: will-change is now applied only during active transitions via animation classes */
/* 注意: will-changeはアニメーションクラスを通じてアクティブな遷移中にのみ適用されます */
.contact-form-container > * {
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

/* Step Container / ステップコンテナ */
.step-container {
  /* --- CSS Grid Overlap Fix --- */
  /* Force all steps to sit in the same "stack" grid area */
  /* This makes them overlap visually instead of stacking vertically */
  grid-area: stack;
  width: 100%;
  position: relative;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  background: var(--color-white);
  z-index: 1;
}

/* Heading Styles / 見出しスタイル */
h1 {
  color: var(--color-text);
  margin-bottom: var(--spacing-2xl);
  text-align: center;
  font-size: var(--font-size-4xl);
}

/* Transitions are now in animations.css / 遷移はanimations.cssに移動済み */

/* Form Styles / フォームスタイル */
.contact-form {
  width: var(--form-width);
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-sm);
}

label {
  font-weight: 600;
  color: var(--color-text-light);
  font-size: var(--font-size-sm);
}

.required {
  color: var(--color-error);
}

input,
textarea {
  padding: var(--spacing-md);
  border: 2px solid var(--color-border);
  border-radius: var(--radius-md);
  font-size: var(--font-size-base);
  font-family: inherit;
  transition: all var(--transition-base);
  width: 100%;
}

textarea {
  min-height: 120px;
  resize: vertical;
}

input:focus,
textarea:focus {
  outline: none;
  border-color: var(--color-primary);
  transform: scale(1.01);
  box-shadow: var(--shadow-focus);
}

/* Mobile: Remove scale transform on focus for better UX */
@media (max-width: 768px) {
  input:focus,
  textarea:focus {
    transform: none;
    box-shadow: var(--shadow-focus-mobile);
  }
}

input.error,
textarea.error {
  border-color: var(--color-error);
}

input.valid,
textarea.valid {
  border-color: var(--color-success);
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2327ae60' d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 40px;
}

.error-message {
  color: var(--color-error);
  font-size: var(--font-size-xs);
  margin-top: -4px;
}

/* Button Styles / ボタンスタイル */
.submit-btn {
  padding: 14px var(--spacing-2xl);
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  color: var(--color-white);
  border: none;
  border-radius: var(--radius-md);
  font-size: var(--font-size-base);
  font-weight: 600;
  cursor: pointer;
  transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  margin-top: 10px;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Loading spinner and content are now in utilities.css / ローディングスピナーとコンテンツはutilities.cssに移動済み */

/* Confirmation Screen Styles / 確認画面のスタイル */
.confirm-content {
  width: var(--form-width);
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: var(--spacing-2xl);
}

.confirm-items-wrapper {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-xl);
}

.confirm-item {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: var(--spacing-xl);
  background: var(--color-background);
  border-radius: var(--radius-lg);
  border-left: 5px solid var(--color-primary);
  transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  box-shadow: var(--shadow-sm);
}

.confirm-item:hover {
  transform: translateX(4px);
  box-shadow: var(--shadow-md);
}

/* Disable hover effects on mobile */
@media (max-width: 768px) {
  .confirm-item:hover {
    transform: none;
    box-shadow: var(--shadow-sm);
  }
}

.confirm-item label {
  font-weight: 700;
  color: var(--color-primary);
  font-size: var(--font-size-sm);
  margin-bottom: var(--spacing-sm);
  text-transform: uppercase;
  letter-spacing: 0.8px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.confirm-item label::before {
  content: '';
  width: 4px;
  height: 4px;
  background: var(--color-primary);
  border-radius: var(--radius-full);
  display: inline-block;
}

.confirm-value {
  color: var(--color-text);
  font-size: var(--font-size-lg);
  line-height: 1.7;
  /* Prevent horizontal overflow - break long words like URLs / 長い単語（URLなど）を折り返して水平オーバーフローを防止 */
  word-break: break-word;
  overflow-wrap: break-word;
  padding-left: 0;
  font-weight: 400;
}

.confirm-message {
  white-space: pre-wrap;
  background: var(--color-white);
  padding: var(--spacing-lg);
  border-radius: var(--radius-md);
  border: 1px solid var(--color-border);
  margin-top: var(--spacing-xs);
  font-size: var(--font-size-lg);
  line-height: 1.8;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
  color: var(--color-text);
  /* Prevent vertical height jumps - limit height and add scroll / 垂直方向の高さジャンプを防止 - 高さを制限してスクロールを追加 */
  max-height: 300px;
  overflow-y: auto;
}

.button-group {
  display: flex;
  gap: var(--spacing-md);
  margin-top: var(--spacing-2xl);
  padding-top: var(--spacing-sm);
}

.back-btn {
  padding: 14px var(--spacing-2xl);
  background: var(--color-background-light);
  color: var(--color-text);
  border: 2px solid var(--color-border);
  border-radius: var(--radius-md);
  font-size: var(--font-size-base);
  font-weight: 600;
  cursor: pointer;
  transition: background-color var(--transition-fast);
  flex: 1;
}

.back-btn:hover {
  background: #e8e8e8;
}

.button-group .submit-btn {
  flex: 1;
  margin-top: 0;
}

.error-message-general {
  padding: var(--spacing-md);
  background: var(--color-error-bg);
  color: var(--color-error-text);
  border-radius: var(--radius-md);
  text-align: center;
  font-weight: 500;
  margin-top: var(--spacing-md);
}

/* Completion Screen Styles / 完了画面のスタイル */
.complete-content {
  width: var(--form-width);
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: var(--spacing-xl) 0;
}

.complete-icon {
  width: 80px;
  height: 80px;
  border-radius: var(--radius-full);
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  color: var(--color-white);
  font-size: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: var(--spacing-2xl);
  font-weight: bold;
  animation: successBounce 0.6s ease-out;
}

.complete-content h1 {
  color: var(--color-text);
  margin-bottom: var(--spacing-lg);
}

.complete-title {
  animation: fadeInUp 0.5s ease-out 0.2s both;
}

.complete-message {
  color: var(--color-text-muted);
  font-size: var(--font-size-base);
  line-height: 1.8;
  margin-bottom: var(--spacing-4xl);
}

.complete-content .submit-btn {
  max-width: 300px;
  width: 100%;
}

/* Responsive Styles / レスポンシブスタイル */
/* Desktop Styles (≥1024px) / デスクトップスタイル（≥1024px） */
@media (min-width: 1024px) {
  .contact-form {
    width: var(--form-width);
  }

  .confirm-content {
    width: var(--form-width);
  }

  .complete-content {
    width: var(--form-width);
  }
}

/* Tablet Styles (768px - 1023px) / タブレットスタイル（768px - 1023px） */
@media (min-width: 768px) and (max-width: 1023px) {
  .contact-form {
    width: 100%;
    max-width: var(--form-width-tablet);
    min-width: var(--form-width);
  }

  .confirm-content {
    width: 100%;
    max-width: var(--form-width-tablet);
    min-width: var(--form-width);
  }

  .complete-content {
    width: 100%;
    max-width: var(--form-width-tablet);
    min-width: var(--form-width);
  }
}

/* Mobile Styles / モバイルスタイル */
@media (max-width: 767px) {
  .contact-form {
    width: 100%;
    max-width: var(--form-width);
    min-width: var(--form-min-width-mobile);
  }

  .confirm-content {
    width: 100%;
    max-width: var(--form-width);
    min-width: var(--form-min-width-mobile);
  }

  .complete-content {
    width: 100%;
    max-width: var(--form-width);
    min-width: var(--form-min-width-mobile);
  }
}

@media (max-width: 400px) {
  .contact-form {
    width: 100%;
    max-width: var(--form-width);
    min-width: var(--form-min-width-small);
  }

  .confirm-content {
    width: 100%;
    max-width: var(--form-width);
    min-width: var(--form-min-width-small);
  }

  .complete-content {
    width: 100%;
    max-width: var(--form-width);
    min-width: var(--form-min-width-small);
  }
}

/* Mobile Styles / モバイル用レスポンシブスタイル */
@media (max-width: 768px) {
  .contact-form-container {
    padding: var(--spacing-3xl) var(--spacing-2xl);
    margin: 10px;
    border-radius: var(--radius-xl);
  }

  h1 {
    font-size: var(--font-size-4xl);
    margin-bottom: var(--spacing-3xl);
    font-weight: 700;
  }

  .form-group {
    gap: 10px;
    margin-bottom: var(--spacing-xs);
  }

  label {
    font-size: var(--font-size-base);
    font-weight: 600;
  }

  input,
  textarea {
    padding: var(--spacing-lg);
    font-size: var(--font-size-xl);
    border-radius: var(--radius-lg);
    border-width: 2px;
  }

  textarea {
    min-height: 140px;
  }

  .error-message {
    font-size: var(--font-size-xs);
    margin-top: 2px;
  }

  .submit-btn {
    padding: var(--spacing-lg) var(--spacing-2xl);
    font-size: var(--font-size-xl);
    font-weight: 600;
    border-radius: var(--radius-lg);
    margin-top: var(--spacing-md);
    min-height: 52px;
  }

  .button-group {
    flex-direction: column;
    gap: var(--spacing-md);
    margin-top: var(--spacing-xl);
  }

  .back-btn,
  .button-group .submit-btn {
    width: 100%;
    min-height: 52px;
  }

  .back-btn {
    padding: var(--spacing-lg) var(--spacing-2xl);
    font-size: var(--font-size-xl);
    font-weight: 600;
    border-radius: var(--radius-lg);
  }

  .confirm-content {
    gap: var(--spacing-3xl);
  }

  .confirm-items-wrapper {
    gap: var(--spacing-2xl);
  }

  .confirm-item {
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    gap: var(--spacing-md);
    border-left-width: 5px;
  }

  .confirm-item label {
    font-size: var(--font-size-sm);
    margin-bottom: var(--spacing-md);
    letter-spacing: 1px;
  }

  .confirm-value {
    font-size: 1.15rem;
    line-height: 1.8;
    font-weight: 500;
    /* Prevent horizontal overflow on mobile / モバイルで水平オーバーフローを防止 */
    word-break: break-word;
    overflow-wrap: break-word;
  }

  .confirm-message {
    padding: var(--spacing-xl);
    font-size: 1.15rem;
    line-height: 1.9;
    margin-top: 6px;
    border-radius: var(--radius-lg);
    /* Prevent vertical height jumps on mobile / モバイルで垂直方向の高さジャンプを防止 */
    max-height: 300px;
    overflow-y: auto;
    color: var(--color-text);
  }

  .button-group {
    margin-top: var(--spacing-3xl);
    gap: 14px;
  }

  .complete-icon {
    width: 90px;
    height: 90px;
    font-size: 50px;
    margin-bottom: var(--spacing-3xl);
  }

  .complete-content h1 {
    font-size: var(--font-size-4xl);
    margin-bottom: var(--spacing-xl);
    font-weight: 700;
  }

  .complete-message {
    font-size: var(--font-size-xl);
    line-height: 1.9;
    padding: 0 var(--spacing-md);
    margin-bottom: 36px;
  }

  .complete-content .submit-btn {
    max-width: 100%;
    min-height: 52px;
  }

  .error-message-general {
    font-size: var(--font-size-base);
    padding: 14px;
    margin-top: var(--spacing-lg);
  }
}

@media (max-width: 480px) {
  .contact-form-container {
    padding: var(--spacing-2xl) var(--spacing-xl);
    margin: var(--spacing-sm);
    border-radius: var(--radius-xl);
  }

  h1 {
    font-size: var(--font-size-3xl);
    margin-bottom: var(--spacing-2xl);
  }

  .contact-form {
    gap: var(--spacing-xl);
  }

  .form-group {
    gap: 10px;
  }

  label {
    font-size: var(--font-size-sm);
  }

  input,
  textarea {
    padding: 14px;
    font-size: var(--font-size-lg);
  }

  textarea {
    min-height: 130px;
  }

  .submit-btn {
    padding: 14px var(--spacing-xl);
    font-size: var(--font-size-lg);
    min-height: 50px;
  }

  .back-btn {
    padding: 14px var(--spacing-xl);
    font-size: var(--font-size-lg);
    min-height: 50px;
  }

  .confirm-content {
    gap: var(--spacing-2xl);
  }

  .confirm-items-wrapper {
    gap: var(--spacing-xl);
  }

  .confirm-item {
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl);
    gap: var(--spacing-md);
  }

  .confirm-item label {
    font-size: var(--font-size-sm);
    margin-bottom: 10px;
  }

  .confirm-value {
    font-size: var(--font-size-xl);
    line-height: 1.8;
    /* Prevent horizontal overflow on small mobile / 小さなモバイルで水平オーバーフローを防止 */
    word-break: break-word;
    overflow-wrap: break-word;
  }

  .confirm-message {
    padding: var(--spacing-lg);
    font-size: var(--font-size-xl);
    line-height: 1.9;
    margin-top: 6px;
    /* Prevent vertical height jumps on small mobile / 小さなモバイルで垂直方向の高さジャンプを防止 */
    max-height: 250px;
    overflow-y: auto;
    color: var(--color-text);
  }

  .button-group {
    margin-top: var(--spacing-2xl);
    gap: var(--spacing-md);
  }

  .complete-icon {
    width: 80px;
    height: 80px;
    font-size: 44px;
    margin-bottom: var(--spacing-2xl);
  }

  .complete-content h1 {
    font-size: var(--font-size-3xl);
  }

  .complete-message {
    font-size: var(--font-size-lg);
    padding: 0 var(--spacing-sm);
  }

  .error-message-general {
    font-size: var(--font-size-sm);
    padding: var(--spacing-md);
  }
}
</style>
