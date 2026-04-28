<template>
  <div class="step form-step">
    <div class="icon-circle"><i class="fas fa-bell"></i></div>
    <h2 class="step-title">¿Te avisamos?</h2>
    <p class="step-sub">
      Si quieres aprobar rápido debes entrar todos los días.<br />
      Podrás cambiar esto en cualquier momento desde los ajustes.
    </p>

    <div class="notif-preview">
      <div class="notif-bar">
        <div class="notif-icon-box"></div>
        <div class="notif-lines">
          <div class="notif-line short"></div>
          <div class="notif-line long"></div>
        </div>
      </div>
    </div>

    <button class="btn-primary" @click="requestPermission">Activar alertas</button>
    <button class="btn-skip" @click="skip">Ahora no</button>
  </div>
</template>

<script>
export default {
  name: 'Step05Notifications',
  props: { formData: Object },
  methods: {
    async requestPermission() {
      try {
        const permission = await Notification.requestPermission()
        this.$emit('next', { notifications: permission === 'granted' })
      } catch {
        this.$emit('next', { notifications: false })
      }
    },
    skip() {
      this.$emit('next', { notifications: false })
    },
  },
}
</script>

<style scoped>
.notif-preview {
  width: 100%;
  max-width: 340px;
  margin-bottom: 32px;
  animation: ob-fadeUp 0.35s ease 0.1s both;
}
.notif-bar {
  background: white;
  border-radius: 16px;
  padding: 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 4px 20px rgba(74, 144, 226, 0.15);
  border: 2px solid #e8f0fb;
}
.notif-icon-box {
  width: 40px; height: 40px;
  background: #4a90e2;
  border-radius: 10px;
  flex-shrink: 0;
}
.notif-lines {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.notif-line {
  height: 8px;
  border-radius: 4px;
  background: #c8ddf7;
}
.notif-line.short { width: 50%; }
.notif-line.long  { width: 100%; }

.btn-skip {
  background: none;
  border: none;
  color: #718096;
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  padding: 10px;
  margin-top: 4px;
}
.btn-skip:hover { color: #4a90e2; }
</style>