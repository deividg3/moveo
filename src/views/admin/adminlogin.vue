<template>
  <div class="admin-login">
    <div class="login-card">
      <div class="login-logo">
        <img src="@/assets/logoblue.png" alt="Logo" class="logo-img" />
        <span class="admin-badge">ADMIN</span>
      </div>
      <h1 class="login-title">Panel de control</h1>
      <p class="login-sub">Acceso restringido</p>
 
      <div class="field" :class="{ focused: userFocused, filled: form.username }">
        <label class="float-label">Usuario administrador</label>
        <input
          v-model="form.username"
          type="text"
          autocomplete="username"
          @focus="userFocused = true"
          @blur="userFocused = false"
          @keyup.enter="submit"
        />
      </div>
 
      <div class="field" :class="{ focused: passFocused, filled: form.password }">
        <label class="float-label">Contraseña</label>
        <input
          v-model="form.password"
          :type="showPass ? 'text' : 'password'"
          autocomplete="current-password"
          @focus="passFocused = true"
          @blur="passFocused = false"
          @keyup.enter="submit"
        />
        <button class="eye-btn" @click="showPass = !showPass" type="button" tabindex="-1">
          {{ showPass ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>' }}
        </button>
      </div>
 
      <p class="error-msg" v-if="error">{{ error }}</p>
 
      <button class="btn-login" :disabled="loading" @click="submit">
        <span v-if="!loading">Entrar</span>
        <span v-else class="spinner"></span>
      </button>
    </div>
  </div>
</template>
 
<script>
export default {
  name: 'AdminLogin',
  data() {
    return {
      form:       { username: '', password: '' },
      userFocused: false,
      passFocused: false,
      showPass:   false,
      error:      '',
      loading:    false,
    }
  },
  methods: {
    async submit() {
      if (!this.form.username || !this.form.password) {
        this.error = 'Rellena todos los campos.'
        return
      }
      this.loading = true
      this.error   = ''
      try {
        const res = await fetch('/backend/admin_login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        })
        const data = await res.json()
        if (data.success) {
          sessionStorage.setItem('admin_token', data.token)
          this.$router.push('/admin/dashboard')
        } else {
          this.error = data.error || 'Credenciales incorrectas.'
        }
      } catch {
        this.error = 'Error de conexión con el servidor.'
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
 
<style scoped>
.admin-login {
  min-height: 100dvh; background: #1a1f2e;
  display: flex; align-items: center; justify-content: center;
  padding: 24px;
}
.login-card {
  background: #242938; border-radius: 24px;
  padding: 40px 32px; width: 100%; max-width: 380px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.4);
}
.login-logo {
  display: flex; align-items: center; gap: 10px; margin-bottom: 24px;
}
.logo-img { height: 32px; filter: brightness(0) invert(1); }
.admin-badge {
  background: #4a90e2; color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 11px; letter-spacing: 1.5px;
  padding: 3px 8px; border-radius: 6px;
}
.login-title {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 24px; color: white; margin-bottom: 4px;
}
.login-sub { font-size: 13px; color: #718096; margin-bottom: 28px; }
 

.field {
  position: relative; margin-bottom: 16px;
}
.field input {
  width: 100%; height: 56px;
  padding: 22px 18px 8px;
  background: #1a1f2e; border: 2px solid #2d3748;
  border-radius: 14px; color: white;
  font-family: 'Nunito Sans', sans-serif; font-size: 15px; font-weight: 700;
  outline: none;
  transition: border-color 0.2s;
}
.field input:focus,
.field.focused input { border-color: #4a90e2; }
.float-label {
  position: absolute; top: 50%; left: 18px;
  transform: translateY(-50%);
  font-size: 14px; color: #718096;
  pointer-events: none; transition: all 0.18s;
}
.field.focused .float-label,
.field.filled  .float-label {
  top: 12px; transform: none;
  font-size: 10px; font-weight: 700;
  color: #4a90e2; letter-spacing: 0.5px; text-transform: uppercase;
}
.eye-btn {
  position: absolute; right: 14px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none; cursor: pointer; font-size: 16px;
}
 
.error-msg {
  color: #ff6b6b; font-size: 13px;
  background: rgba(255,107,107,0.1); border-radius: 10px;
  padding: 10px 14px; margin-bottom: 14px;
}
 
.btn-login {
  width: 100%; padding: 16px;
  background: #4a90e2; color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 15px; letter-spacing: 1px; text-transform: uppercase;
  border: none; border-radius: 999px; cursor: pointer;
  box-shadow: 0 4px 0 #2c6fc7;
  transition: transform 0.12s, opacity 0.15s;
  margin-top: 8px;
  display: flex; align-items: center; justify-content: center;
}
.btn-login:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-login:active   { transform: translateY(3px); box-shadow: 0 1px 0 #2c6fc7; }
 
.spinner {
  width: 20px; height: 20px; border-radius: 50%;
  border: 3px solid rgba(255,255,255,0.3);
  border-top-color: white;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>