<template>
  <div class="step form-step">
    <div class="icon-circle"><i class="fas fa-envelope"></i></div>
    <h2 class="step-title">Tu correo electrónico</h2>
    <p class="step-sub">Lo usarás para entrar en tu cuenta.</p>
 
    <div class="input-wrap" :class="{ focused, filled: local }">
      <label class="float-label">Correo electrónico</label>
      <input
        v-model="local"
        type="email"
        autocomplete="email"
        inputmode="email"
        @focus="focused = true"
        @blur="focused = false"
        @keyup.enter="submit"
      />
    </div>
 
    <button class="btn-primary" :disabled="!valid" @click="submit">Continuar</button>
  </div>
</template>
 
<script>
export default {
  name: 'Step03Email',
  props: { formData: Object },
  data() { return { local: this.formData.email || '', focused: false } },
  computed: {
    valid() { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.local) },
  },
  methods: {
    submit() { if (this.valid) this.$emit('next', { email: this.local.trim() }) },
  },
}
</script>