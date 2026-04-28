<template>
  <div class="step welcome">
    <div class="bubbles">
      <span class="bubble b1"></span>
      <span class="bubble b2"></span>
      <span class="bubble b3"></span>
    </div>
 
    <div class="logo-wrap">
      <img src="@/assets/logoblue.png" alt="Logo" class="logo" />
    </div>
 
    <div class="hero-text">
      <h1 class="hero-title">Aprende sin<br /><em>límites</em></h1>
      <p class="hero-sub">El método que se adapta a ti,<br />no al revés.</p>
    </div>
 
    <div class="cta-area">
      <button class="btn-primary" @click="$emit('next')">Empezar ahora</button>
      <p class="login-hint">¿Ya tienes cuenta? <button class="login-link" @click="$router.push('/home')">Entrar</button></p>
    </div>
  </div>
</template>
 
<script>
export default { name: 'step01welcome' }
</script>
 
<style scoped>
.welcome {
  flex: 1;
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 25px;
  padding: 60px 28px 52px;
  position: relative;
  min-height: 100vh;
}
 

.bubbles { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.bubble {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(74,144,226,0.13), transparent 70%);
}
.b1 { width: 320px; height: 320px; top: -80px; right: -100px; animation: drift 8s ease-in-out infinite; }
.b2 { width: 200px; height: 200px; bottom: 120px; left: -60px; animation: drift 11s ease-in-out infinite reverse; }
.b3 { width: 140px; height: 140px; top: 40%; right: -40px; animation: drift 7s ease-in-out infinite 2s; }
@keyframes drift { 0%,100%{transform:translate(0,0)} 50%{transform:translate(12px,-16px)} }
 
.logo-wrap { z-index: 1; }
.logo { height: 48px; width: auto; }
 
.hero-text { text-align: center; z-index: 1; }
.hero-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 42px;
  line-height: 1.1;
  color: #2d3748;
  margin-bottom: 16px;
}
.hero-title em {
  font-style: normal;
  color: var(--blue);
  position: relative;
}
.hero-title em::after {
  content: '';
  position: absolute;
  bottom: 2px; left: 0; right: 0;
  height: 6px;
  background: var(--blue-light);
  border-radius: 3px;
  z-index: -1;
}
.hero-sub {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 17px;
  color: var(--text-muted);
  line-height: 1.6;
}
 
.cta-area { width: 100%; display: flex; flex-direction: column; align-items: center; gap: 18px; z-index: 1; }
 

 
.login-hint {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px;
  color: var(--text-muted);
}
.login-hint a, .login-link { color: var(--blue); font-weight: 700; text-decoration: none; }
.login-link {
  background: none; border: none; padding: 0;
  color: var(--blue); font-weight: 700;
  font-family: 'Nunito Sans', sans-serif;
  font-size: 14px; cursor: pointer;
}
</style>