<template>
  <div class="step form-step">
    <div class="icon-circle"><i class="fas fa-lock"></i></div>
    <h2 class="step-title">Crea tu contraseña</h2>
    <p class="step-sub">Mínimo 8 caracteres. Hazla difícil de adivinar.</p>
 
    <div class="input-wrap" :class="{ focused, filled: local }">
      <label class="float-label">Contraseña</label>
      <input
        v-model="local"
        :type="show ? 'text' : 'password'"
        autocomplete="new-password"
        @focus="focused = true"
        @blur="focused = false"
        @keyup.enter="submit"
      />
      <button class="eye-btn" @click="show = !show" tabindex="-1" type="button">
        <svg v-if="!show" width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="#718096" stroke-width="2"/>
          <circle cx="12" cy="12" r="3" stroke="#718096" stroke-width="2"/>
        </svg>
        <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" stroke="#718096" stroke-width="2" stroke-linecap="round"/>
          <line x1="1" y1="1" x2="23" y2="23" stroke="#718096" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
 
    <div class="strength-bar" v-if="local">
      <div class="strength-track">
        <div class="strength-fill" :class="strengthClass" :style="{ width: strengthPct + '%' }"></div>
      </div>
      <span class="strength-label" :class="strengthClass">{{ strengthLabel }}</span>
    </div>
 
    <button class="btn-primary" :disabled="local.length < 8" @click="submit">Continuar</button>
  </div>
</template>
 
<script>
export default {
  name: 'Step04Password',
  props: { formData: Object },
  data() { return { local: this.formData.password || '', focused: false, show: false } },
  computed: {
    strength() {
      const p = this.local
      let s = 0
      if (p.length >= 8)           s++
      if (p.length >= 12)          s++
      if (/[A-Z]/.test(p))         s++
      if (/[0-9]/.test(p))         s++
      if (/[^A-Za-z0-9]/.test(p))  s++
      return s
    },
    strengthPct()   { return Math.min(100, this.strength * 20) },
    strengthClass() { return ['','weak','weak','medium','strong','strong'][this.strength] || 'weak' },
    strengthLabel() { return ['','Débil','Débil','Regular','Fuerte','Muy fuerte'][this.strength] || '' },
  },
  methods: {
    submit() { if (this.local.length >= 8) this.$emit('next', { password: this.local }) },
  },
}
</script>
 
<style scoped>
.eye-btn {
  position: absolute;
  right: 14px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none;
  cursor: pointer; padding: 4px;
  display: flex; align-items: center;
}
 
.strength-bar {
  width: 100%; max-width: 340px;
  display: flex; align-items: center; gap: 10px;
  margin-top: -18px; margin-bottom: 24px;
}
.strength-track {
  flex: 1; height: 6px;
  background: #e2ecf8; border-radius: 3px; overflow: hidden;
}
.strength-fill {
  height: 100%; border-radius: 3px;
  transition: width 0.3s, background 0.3s;
}
.strength-fill.weak   { background: #ff6b6b; }
.strength-fill.medium { background: #f4a828; }
.strength-fill.strong { background: #58cc02; }
.strength-label {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 12px; font-weight: 700; min-width: 60px;
}
.strength-label.weak   { color: #ff6b6b; }
.strength-label.medium { color: #f4a828; }
.strength-label.strong { color: #58cc02; }
</style>