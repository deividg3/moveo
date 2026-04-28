<template>
  <transition name="cookie-slide">
    <div class="cookie-banner" v-if="visible" role="dialog" aria-label="Aviso de cookies">
      <div class="cookie-content">
        <div class="cookie-icon">
          <i class="fas fa-cookie-bite"></i>
        </div>
        <div class="cookie-text">
          <p class="cookie-title">🍪 Usamos cookies</p>
          <p class="cookie-desc">
            Utilizamos cookies para mejorar tu experiencia y recordar tus preferencias.
          </p>
        </div>
      </div>
      <div class="cookie-actions">
        <button class="cookie-btn accept" @click="accept">
          <i class="fas fa-check"></i> Aceptar
        </button>
        <button class="cookie-btn reject" @click="reject">
          Rechazar
        </button>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'CookieBanner',
  data() {
    return {
      visible: false,
    }
  },
  mounted() {
    
    const consent = this.getCookie('moveo_cookie_consent')
    if (!consent) {
      
      setTimeout(() => { this.visible = true }, 1000)
    }
  },
  methods: {
    accept() {
      this.setCookie('moveo_cookie_consent', 'accepted', 365)
      this.visible = false
    },
    reject() {
      this.setCookie('moveo_cookie_consent', 'rejected', 365)
      this.visible = false
    },
    
    setCookie(name, value, days) {
      const d = new Date()
      d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000))
      const expires = 'expires=' + d.toUTCString()
      document.cookie = name + '=' + value + ';' + expires + ';path=/;SameSite=Lax'
    },
    getCookie(name) {
      const nameEQ = name + '='
      const ca = document.cookie.split(';')
      for (let c of ca) {
        c = c.trim()
        if (c.indexOf(nameEQ) === 0) {
          return c.substring(nameEQ.length)
        }
      }
      return null
    },
  },
}
</script>

<style scoped>
.cookie-banner {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 9999;
  background: white;
  border-top: 1px solid #e2ecf8;
  box-shadow: 0 -8px 32px rgba(74, 144, 226, 0.15);
  padding: 18px 20px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  border-radius: 20px 20px 0 0;
}

.cookie-content {
  display: flex;
  align-items: flex-start;
  gap: 14px;
}

.cookie-icon {
  font-size: 28px;
  color: #f4a828;
  flex-shrink: 0;
  margin-top: 2px;
}

.cookie-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 16px;
  color: #2d3748;
  margin: 0 0 4px;
}

.cookie-desc {
  font-family: 'Nunito Sans', sans-serif;
  font-size: 13px;
  color: #718096;
  line-height: 1.5;
  margin: 0;
}

.cookie-actions {
  display: flex;
  gap: 10px;
}

.cookie-btn {
  flex: 1;
  padding: 12px 16px;
  border-radius: 14px;
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: 14px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.cookie-btn.accept {
  background: var(--blue, #4a90e2);
  color: white;
  box-shadow: 0 3px 0 var(--blue-dark, #2c6fc7), 0 4px 16px rgba(74, 144, 226, 0.25);
}
.cookie-btn.accept:active {
  transform: translateY(2px);
  box-shadow: 0 1px 0 var(--blue-dark, #2c6fc7);
}

.cookie-btn.reject {
  background: #f0f7ff;
  color: #718096;
  border: 2px solid #e2ecf8;
}
.cookie-btn.reject:hover {
  background: #e2ecf8;
}


.cookie-slide-enter-active {
  transition: all 0.4s cubic-bezier(.4,0,.2,1);
}
.cookie-slide-leave-active {
  transition: all 0.3s cubic-bezier(.4,0,.2,1);
}
.cookie-slide-enter-from,
.cookie-slide-leave-to {
  transform: translateY(100%);
  opacity: 0;
}

@media (min-width: 1024px) {
  .cookie-banner {
    max-width: 420px;
    left: auto;
    right: 24px;
    bottom: 24px;
    border-radius: 20px;
  }
}
</style>
