<template>
  <div class="step done-step">
    <div class="confetti-wrap">
      <span v-for="i in 18" :key="i" class="confetti-piece" :style="confettiStyle(i)"></span>
    </div>
 
    <div class="done-icon">
      <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
        <circle cx="40" cy="40" r="38" fill="#d8eaff" stroke="#4a90e2" stroke-width="3"/>
        <path d="M22 40l12 12 24-24" stroke="#4a90e2" stroke-width="5"
              stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
 
    <h2 class="done-title">¡Todo listo, {{ firstName }}!</h2>
    <p class="done-sub">Tu cuenta está creada. Ya puedes empezar a aprender y construir tu racha.</p>
 
    <div class="stats-row">
      <div class="stat-pill"><i class="fas fa-bullseye"></i> {{ goalsCount }} objetivo{{ goalsCount !== 1 ? 's' : '' }}</div>
      <div class="stat-pill"><i class="fas fa-fire"></i> Activa tu racha</div>
    </div>
 
    <button class="btn-primary" @click="goHome">¡Vamos allá!</button>
  </div>
</template>
 
<script>
export default {
  name: 'step06done',
  props: { formData: Object },
  computed: {
    firstName() { return (this.formData.name || '').split(' ')[0] },
    goalsCount() { return (this.formData.goals || []).length },
  },
  methods: {
    goHome() {
      console.log("Navegando al Home con Hash...");
  
  this.$router.push({ name: 'home' }).catch(err => {
    console.error("Error de navegación:", err);
    
    window.location.hash = '/home';
  });
    },
    confettiStyle(i) {
      const colors = ['#4a90e2','#ff6b35','#58cc02','#f4a828','#a855f7','#ec4899']
      return {
        '--x':     `${(i * 37 + 15) % 100}%`,
        '--delay': `${(i * 0.18) % 1.5}s`,
        '--color': colors[i % colors.length],
        '--size':  `${6 + (i % 4) * 3}px`,
      }
    },
  },
}
</script>
 
<style scoped>
.done-step {
  flex: 1;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 60px 28px 52px;
  min-height: 100dvh;
  position: relative; text-align: center; overflow: hidden;
}
 
.confetti-wrap { position: absolute; inset: 0; pointer-events: none; }
.confetti-piece {
  position: absolute;
  top: -20px; left: var(--x);
  width: var(--size); height: var(--size);
  background: var(--color); border-radius: 2px;
  animation: fall 2.5s var(--delay) ease-in both;
}
@keyframes fall {
  0%   { transform: translateY(0) rotate(0deg); opacity: 1; }
  100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
}
 
.done-icon {
  margin-bottom: 24px;
  animation: ob-pop 0.5s cubic-bezier(.4,0,.2,1) both;
}
 
.done-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 30px; color: #2d3748;
  margin-bottom: 14px;
  animation: ob-fadeUp 0.4s ease 0.2s both;
}
.done-sub {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 16px; color: #718096;
  max-width: 280px; line-height: 1.6;
  margin-bottom: 32px;
  animation: ob-fadeUp 0.4s ease 0.3s both;
}
 
.stats-row {
  display: flex; gap: 12px;
  margin-bottom: 40px;
  animation: ob-fadeUp 0.4s ease 0.4s both;
}
.stat-pill {
  background: #f0f7ff;
  border: 2px solid #c8ddf7; border-radius: 999px;
  padding: 8px 18px;
  font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 14px; color: #4a90e2;
}
</style>