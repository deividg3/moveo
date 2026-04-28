<template>
  <div class="app-wrapper">

    
    <div class="desktop-showcase">
      <div class="showcase-content">
        <div class="floating-badge">BETA</div>
        <img src="@/assets/logoblue.png" alt="Moveo Logo" class="showcase-logo" />
        <h1 class="showcase-title">Aparca<br/><span class="highlight">los libros</span></h1>
        <p class="showcase-subtitle">
          Los tiempos han cambiado. <br> La manera de sacarte el carnet también.
        </p>
        
        <div class="showcase-features">
          <div class="feature">
            <div class="feature-icon bg-orange"><i class="fas fa-fire"></i></div>
            <div class="feature-text">
              <h4>Mantén tu Racha</h4>
              <p>Estudia un poco cada día</p>
            </div>
          </div>
          <div class="feature">
            <div class="feature-icon bg-green"><i class="fas fa-chart-line"></i></div>
            <div class="feature-text">
              <h4>Sigue tu Progreso</h4>
              <p>Domina todo el temario</p>
            </div>
          </div>
        </div>
        
        <div class="scan-wrapper">
          <div class="scan-text">
            <span>¿Duolingo para el carnet? Sí, gracias.</span>
          </div>
          <div class="qr-placeholder">
            <p>Prueba la app en tu móvil o en este iPhone que te hemos prestado (a la derecha)</p>
          </div>
        </div>
      </div>
    </div>

    
    <div class="mobile-app-container">
      <div class="mobile-app-content">
        
        <nav class="top-nav" v-if="!$route.meta.hideNav">
          <router-link to="/stats" class="nav-user">
            <div class="avatar-ring">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="8" r="4" fill="currentColor"/>
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
          </router-link>
    
          <div class="nav-logo">
            <router-link to="/home" style="display: block;">
              <img src="@/assets/logoblue.png" alt="Logo" class="logo-img" />
            </router-link>
          </div>
    
          <div class="nav-streak">
            <router-link to="/streak-lost" style="text-decoration: none; color: inherit;">
              <div class="streak-pill">
                <span class="streak-fire"><i class="fas fa-fire"></i></span>
                <span class="streak-count">{{ streak }}</span>
              </div>
            </router-link>
          </div>
    
        </nav>
    
        
        <main class="main-content">
          <router-view v-slot="{ Component, route }">
            <transition name="fade" mode="out-in">
              <component :is="Component" :key="route.path" />
            </transition>
          </router-view>
        </main>
      </div>
    </div>

    
    <CookieBanner />

  </div>
</template>
 
<script>
import { useProgress } from '@/composables/useProgress.js'
import allQuestions from '@/data/questions.json'
import CookieBanner from '@/components/CookieBanner.vue'

export default {
  name: 'App',
  components: { CookieBanner },
  data() {
    const p = useProgress()
    const state = p.getState()
    
    const seenCount = Object.values(state.questionStats).filter(s => s.seen > 0).length
    const progress = allQuestions.length > 0
      ? Math.round((seenCount / allQuestions.length) * 100)
      : 0
    return {
      streak: state.streak,
      progress,
    }
  },
  computed: {
    firstName() {
      const raw = (localStorage.getItem('moveo_name') || '').split(' ')[0] || ''
      if (!raw) return null
      
      return raw.charAt(0).toUpperCase() + raw.slice(1).toLowerCase()
    },
  },
}
</script>
 
<style>

@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&display=swap');
 

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
:root {
  --blue:       #4a90e2;
  --blue-light: #d8eaff;
  --blue-dark:  #2c6fc7;
  --orange:     #ff6b35;
  --green:      #58cc02;
  --bg:         #f0f7ff;
  --white:      #ffffff;
  --text:       #2d3748;
  --text-muted: #718096;
  --nav-h:      64px;
  -webkit-tap-highlight-color: transparent;
}
 
html, body {
  height: 100%;
}
 
body {
  background: var(--bg);
  font-family: 'Nunito Sans', sans-serif;
  color: var(--text);
  overscroll-behavior: none;
  -webkit-font-smoothing: antialiased;
}


.fade-enter-active,
.fade-leave-active { transition: opacity 0.22s ease; }
.fade-enter-from,
.fade-leave-to     { opacity: 0; }


.app-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  overflow: hidden;
}

.desktop-showcase {
  display: none;
}

.mobile-app-container {
  width: 100%;
  height: 100%;
  min-height: 100vh;
  min-height: 100dvh;
  display: flex;
  flex-direction: column;
}

.mobile-app-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100%;
  position: relative;
  overflow-y: auto;
  overflow-x: hidden;
  background: var(--bg);
}


@media (min-width: 1024px) {
  body {
    background: linear-gradient(135deg, #f5f9ff 0%, #e2efff 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden; 
  }
  
  .app-wrapper {
    max-width: 1200px;
    height: 100vh;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 4rem;
    gap: 10%;
  }

  
  .desktop-showcase {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
    animation: fadeIn 0.8s ease-out forwards;
  }
  
  .showcase-content {
    max-width: 520px;
  }

  .floating-badge {
    display: inline-block;
    background: rgba(74, 144, 226, 0.1);
    color: var(--blue);
    font-weight: 800;
    font-family: 'Nunito', sans-serif;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 14px;
    margin-bottom: 24px;
    align-self: flex-start;
    border: 1px solid rgba(74, 144, 226, 0.2);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.05);
  }

  .showcase-logo {
    height: 140px; 
    width: auto;
    margin-bottom: 24px;
    display: block;
    margin-left: -24px; 
  }

  .showcase-title {
    font-family: 'Nunito', sans-serif;
    font-weight: 900;
    font-size: 3.5rem;
    line-height: 1.1;
    color: var(--text);
    margin-bottom: 20px;
    letter-spacing: -1px;
  }

  .showcase-title .highlight {
    background: linear-gradient(90deg, var(--blue) 0%, #00d2ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .showcase-subtitle {
    font-size: 1.125rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 40px;
  }

  .showcase-features {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 48px;
  }

  .feature {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--white);
    padding: 16px 20px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .feature:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
  }

  .feature-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
  }
  .bg-orange { background: rgba(255, 107, 53, 0.1); }
  .bg-green { background: rgba(88, 204, 2, 0.1); }

  .feature-text h4 {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--text);
    margin-bottom: 2px;
  }
  .feature-text p {
    font-size: 0.9rem;
    color: var(--text-muted);
  }

  .scan-wrapper {
    background: var(--white);
    padding: 24px;
    border-radius: 24px;
    display: inline-flex;
    flex-direction: column;
    gap: 12px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
  }
  .scan-text {
    font-weight: 700;
    font-family: 'Nunito', sans-serif;
    font-size: 15px;
    color: var(--blue);
  }
  .qr-placeholder {
    font-size: 13px;
    color: var(--text-muted);
    background: var(--bg);
    padding: 12px 16px;
    border-radius: 12px;
    text-align: center;
    font-weight: 600;
  }

  
  .mobile-app-container {
    width: 375px; 
    height: 812px;
    min-height: auto;
    background: var(--bg);
    border-radius: 50px;
    
    border: 14px solid #000000;
    box-shadow: 
      0 0 0 2px #444, 
      0 40px 80px -12px rgba(0, 0, 0, 0.3), 
      0 20px 40px -16px rgba(74, 144, 226, 0.2);
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  
  .mobile-app-container::before {
    content: '';
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 30px;
    background: #000000;
    border-radius: 0 0 20px 20px;
    z-index: 9999;
  }
  
  .mobile-app-content {
    border-radius: 36px;
    overflow-y: hidden; 
  }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-20px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes slideUp {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
 
<style scoped>

.top-nav {
  position: sticky;
  top: 0;
  z-index: 100;
  height: var(--nav-h);
  background: var(--white);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  box-shadow: 0 2px 12px rgba(74, 144, 226, 0.10);
  overflow: hidden;
}
@media (min-width: 1024px) {
  .top-nav {
    padding-top: 34px; 
    height: calc(var(--nav-h) + 34px);
  }
  .nav-user, .nav-streak {
    display: none;
  }
  .logo-img {
    height: 40px; 
  }
}
 

 
.nav-user {
  text-decoration: none;
  color: inherit;
  z-index: 1;
}
.avatar-ring {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: var(--blue-light);
  border: 2.5px solid var(--blue);
  display: flex; align-items: center; justify-content: center;
  color: var(--blue-dark);
  transition: transform 0.18s, box-shadow 0.18s;
}
.avatar-ring:hover {
  transform: scale(1.08);
  box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.18);
}
 
.nav-logo {
  position: absolute;
  left: 50%; transform: translateX(-50%);
  z-index: 1;
}
.logo-img { height: 48px; width: auto; display: block; } 

.nav-streak { z-index: 1; }
.streak-pill {
  display: flex; align-items: center; gap: 5px;
  background: var(--orange);
  color: white;
  font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 15px;
  padding: 6px 13px 6px 10px;
  border-radius: 999px;
  box-shadow: 0 3px 10px rgba(255, 107, 53, 0.30);
}
.streak-fire { font-size: 17px; }
 
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}
</style>