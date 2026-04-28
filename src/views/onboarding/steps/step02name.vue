<template>
  <div class="step form-step">
    <div class="icon-circle"><i class="fas fa-user"></i></div>
    <h2 class="step-title">¿Cómo te llamas?</h2>
    <p class="step-sub">Así sabremos cómo dirigirnos a ti.</p>
 
    <div class="input-wrap" :class="{ focused: nameFocused, filled: localName }">
      <label class="float-label">Tu nombre completo</label>
      <input
        v-model="localName"
        type="text"
        autocomplete="name"
        @focus="nameFocused = true"
        @blur="nameFocused = false"
        @keyup.enter="submit"
      />
    </div>
 
    <button class="btn-primary" :disabled="!localName.trim()" @click="submit">
      Continuar
    </button>
  </div>
</template>
 
<script>
export default {
  name: 'step02name',
  props: { formData: Object },
  data() {
    return { localName: this.formData.name || '', nameFocused: false }
  },
  methods: {
    submit() {
      if (this.localName.trim()) this.$emit('next', { name: this.localName.trim() })
    },
  },
}
</script>