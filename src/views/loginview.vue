<template>
  <div class="login-page">
    <div class="bubbles" aria-hidden="true">
      <span class="bubble b1"></span>
      <span class="bubble b2"></span>
      <span class="bubble b3"></span>
    </div>

    <div class="login-card">
      <img src="@/assets/logoblue.png" alt="Moveo" class="logo" />
      <h1 class="title">Bienvenido de nuevo</h1>
      <p class="subtitle">Inicia sesión para continuar tu racha</p>

      <form class="form" @submit.prevent="submit">
        <div class="input-wrap" :class="{ focused: focusedEmail, filled: form.email }">
          <label class="float-label">Correo electrónico</label>
          <input
            v-model="form.email"
            type="email"
            autocomplete="email"
            inputmode="email"
            @focus="focusedEmail = true"
            @blur="focusedEmail = false"
          />
        </div>

        <div class="input-wrap" :class="{ focused: focusedPass, filled: form.password }">
          <label class="float-label">Contraseña</label>
          <input
            v-model="form.password"
            :type="showPass ? 'text' : 'password'"
            autocomplete="current-password"
            @focus="focusedPass = true"
            @blur="focusedPass = false"
          />
          <button type="button" class="eye-btn" @click="showPass = !showPass" tabindex="-1">
            <svg v-if="!showPass" width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="#718096" stroke-width="2"/>
              <circle cx="12" cy="12" r="3" stroke="#718096" stroke-width="2"/>
            </svg>
            <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none">
              <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" stroke="#718096" stroke-width="2" stroke-linecap="round"/>
              <line x1="1" y1="1" x2="23" y2="23" stroke="#718096" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <p class="error-msg" v-if="error">{{ error }}</p>

        <button type="submit" class="btn-primary" :disabled="loading || !canSubmit">
          <span v-if="loading" class="spinner"></span>
          <span v-else>Entrar</span>
        </button>
      </form>

      
      <div class="divider">
        <span class="divider-line"></span>
        <span class="divider-text">o continúa con</span>
        <span class="divider-line"></span>
      </div>

      
      <div class="social-buttons">
        <button class="social-btn google" @click="signInWithGoogle">
          <svg width="20" height="20" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          <span>Google</span>
        </button>
        <button class="social-btn apple" @click="signInWithApple">
          <i class="fab fa-apple"></i>
          <span>Apple</span>
        </button>
      </div>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">O continúa rápido</span>
        <div class="divider-line"></div>
      </div>
 
      <button class="social-btn guest-btn" type="button" @click="continueAsGuest">
        <i class="fas fa-user-secret"></i> Continuar como invitado
      </button>

      <p class="register-hint">
        ¿No tienes cuenta?
        <button class="link-btn" type="button" @click="$router.push('/onboarding')">Regístrate gratis</button>
      </p>
    </div>
  </div>
</template>

<script>
import { useApi } from '@/composables/useApi.js'

export default {
  name: 'LoginView',
  setup() {
    return { api: useApi() }
  },
  data() {
    return {
      form: { email: '', password: '' },
      focusedEmail: false,
      focusedPass: false,
      showPass: false,
      loading: false,
      error: '',
    }
  },
  computed: {
    canSubmit() {
      return this.form.email.includes('@') && this.form.password.length >= 6
    },
  },
  methods: {
    async submit() {
      this.error = ''
      this.loading = true
      try {
        const result = await this.api.login({
          email: this.form.email,
          password: this.form.password,
        })
        
        localStorage.setItem('moveo_name', result.name || '')
        localStorage.setItem('moveo_user_id', result.user_id || '')
        localStorage.setItem('user_authenticated', 'true')
        this.$router.push('/home')
      } catch (e) {
        this.error = e.message || 'Error al iniciar sesión'
      } finally {
        this.loading = false
      }
    },

    signInWithGoogle() {
      
      this.loading = true;
      setTimeout(() => {
        localStorage.setItem('moveo_name', 'Usuario Google');
        localStorage.setItem('user_authenticated', 'true');
        this.$router.push('/home');
        this.loading = false;
      }, 1200);
    },

    signInWithApple() {
      
      this.loading = true;
      setTimeout(() => {
        localStorage.setItem('moveo_name', 'Usuario Apple');
        localStorage.setItem('user_authenticated', 'true');
        this.$router.push('/home');
        this.loading = false;
      }, 1200);
    },

    continueAsGuest() {
      localStorage.setItem('moveo_name', 'Invitado')
      localStorage.setItem('user_authenticated', 'true')
      this.$router.push('/home')
    },
  },
}
</script>

<style scoped>
.login-page {
  min-height: 100dvh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(160deg, #e8f4ff 0%, #f0f7ff 50%, #fff 100%);
  padding: 24px 20px;
  position: relative;
  overflow: hidden;
}


.bubbles { position: absolute; inset: 0; pointer-events: none; }
.bubble {
  position: absolute; border-radius: 50%;
  background: radial-gradient(circle, rgba(74,144,226,0.13), transparent 70%);
}
.b1 { width: 320px; height: 320px; top: -80px; right: -100px; animation: drift 8s ease-in-out infinite; }
.b2 { width: 200px; height: 200px; bottom: 80px;  left: -60px;  animation: drift 11s ease-in-out infinite reverse; }
.b3 { width: 140px; height: 140px; top: 40%;     right: -40px; animation: drift 7s ease-in-out infinite 2s; }
@keyframes drift { 0%,100%{transform:translate(0,0)} 50%{transform:translate(12px,-16px)} }

.login-card {
  position: relative; z-index: 1;
  background: white;
  border-radius: 28px;
  padding: 40px 28px 32px;
  width: 100%; max-width: 400px;
  box-shadow: 0 8px 40px rgba(74,144,226,0.15);
  display: flex; flex-direction: column; align-items: center;
  gap: 12px;
}

.logo { height: 40px; width: auto; margin-bottom: 4px; }

.title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 24px; color: #2d3748;
  margin: 0; text-align: center;
}
.subtitle {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px; color: #718096;
  margin: 0; text-align: center;
}

.form {
  width: 100%;
  display: flex; flex-direction: column; gap: 16px;
  margin-top: 8px;
}



.error-msg {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 13px; color: #e53e3e;
  background: #fff5f5; border-radius: 10px;
  padding: 10px 14px; margin: 0;
  border-left: 3px solid #e53e3e;
}

.btn-primary {
  width: 100%;
  display: flex; align-items: center; justify-content: center;
  min-height: 56px;
}

.spinner {
  width: 20px; height: 20px;
  border: 3px solid rgba(255,255,255,0.4);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.register-hint {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px; color: #718096;
  margin-top: 4px; text-align: center;
}
.link-btn {
  background: none; border: none; padding: 0;
  color: #4a90e2; font-weight: 700;
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px; cursor: pointer;
}
.link-btn:hover { text-decoration: underline; }

.eye-btn {
  position: absolute;
  right: 14px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none;
  cursor: pointer; padding: 4px;
  display: flex; align-items: center;
}


.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  margin: 4px 0;
}
.divider-line {
  flex: 1;
  height: 1px;
  background: #e2ecf8;
}
.divider-text {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 13px;
  color: #a0aec0;
  white-space: nowrap;
}


.social-buttons {
  display: flex;
  gap: 10px;
  width: 100%;
}
.social-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 16px;
  border-radius: 14px;
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s ease;
  border: 2px solid #e2ecf8;
  background: white;
  color: #2d3748;
}
.social-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}
.social-btn.google:hover {
  border-color: #4285F4;
  background: #f8faff;
}
.social-btn:hover { background: #f8fbff; border-color: #c8ddf7; }

.guest-btn {
  margin-top: 4px;
  background: #f1f5f9;
  border-color: #e2e8f0;
}
.guest-btn:hover {
  background: #e2e8f0;
  border-color: #cbd5e1;
}
.social-btn.apple {
  background: #000;
  color: white;
  border-color: #000;
}
.social-btn.apple:hover {
  background: #1a1a1a;
}
.social-btn.apple i {
  font-size: 20px;
}
</style>
