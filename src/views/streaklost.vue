<template>
  <div class="not-found">
    <div class="nf-content">
      <img src="@/assets/penguin-sad.png" alt="Pingüino triste" class="nf-image" />
      <h1 class="nf-title" style="font-size: 48px; letter-spacing: -1px;">"L" DE LERDO</h1>
      <h2 class="nf-subtitle">¡Has perdido tu racha!</h2>
      <p class="nf-desc">
        Muy pronto tendrás protectores de racha para que los únicos 0 que veas sean los de tu cuenta bancaria.
      </p>
      <button class="btn-primary" @click="$router.push('/home')">
        <i class="fas fa-undo"></i> Empezar desde cero
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StreakLost',
}
</script>

<style scoped>
.not-found {
  min-height: 100dvh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #FFFFFF;
  padding: 40px 24px;
  text-align: center;
}

.nf-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  max-width: 340px;
}

.nf-image {
  width: 140px;
  height: auto;
  margin-bottom: 8px;
}

.nf-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 72px;
  color: var(--blue, #4a90e2);
  margin: 0;
  line-height: 1;
  letter-spacing: -3px;
}

.nf-subtitle {
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: 22px;
  color: #2d3748;
  margin: 0;
}

.nf-desc {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px;
  color: #718096;
  line-height: 1.6;
  margin: 0 0 12px;
}

.btn-primary {
  width: 100%;
  max-width: 300px;
  background: var(--blue, #4a90e2);
  color: white;
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 16px;
  letter-spacing: 1px;
  text-transform: uppercase;
  border: none;
  border-radius: 999px;
  padding: 16px 24px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  box-shadow: 0 4px 0 var(--blue-dark, #2c6fc7), 0 8px 24px rgba(74, 144, 226, 0.25);
  transition: transform 0.12s, box-shadow 0.12s;
}
.btn-primary:active {
  transform: translateY(3px);
  box-shadow: 0 1px 0 var(--blue-dark, #2c6fc7);
}


</style>
