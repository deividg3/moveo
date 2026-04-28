<template>
  <div class="loading-overlay" :class="{ 'full-page': fullPage }">
    <div class="loading-content">
      
      <div class="car-animation">
        <div class="road">
          <div class="road-line"></div>
          <div class="road-line"></div>
          <div class="road-line"></div>
        </div>
        <div class="car-emoji"><i class="fas fa-car-side"></i></div>
        <div class="dust dust-1"></div>
        <div class="dust dust-2"></div>
        <div class="dust dust-3"></div>
      </div>
      <p class="loading-text">{{ text }}</p>
      <div class="loading-dots">
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'loadingspinner',
  props: {
    text: {
      type: String,
      default: 'Cargando',
    },
    fullPage: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<style scoped>
.loading-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}
.loading-overlay.full-page {
  position: fixed;
  inset: 0;
  background: rgba(240, 247, 255, 0.92);
  backdrop-filter: blur(6px);
  z-index: 9998;
}

.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}


.car-animation {
  position: relative;
  width: 120px;
  height: 60px;
}

.road {
  position: absolute;
  bottom: 8px;
  left: 0;
  right: 0;
  height: 4px;
  background: #c8ddf7;
  border-radius: 2px;
  overflow: hidden;
  display: flex;
  gap: 8px;
}

.road-line {
  width: 20px;
  height: 2px;
  background: #a0b8d8;
  border-radius: 1px;
  margin-top: 1px;
  animation: road-scroll 0.8s linear infinite;
}
.road-line:nth-child(2) { animation-delay: 0.27s; }
.road-line:nth-child(3) { animation-delay: 0.54s; }

.car-emoji {
  font-size: 36px;
  position: absolute;
  bottom: 6px;
  left: 50%;
  transform: translateX(-50%);
  animation: car-bounce 0.6s ease-in-out infinite;
  filter: drop-shadow(0 3px 6px rgba(0,0,0,0.15));
}


.dust {
  position: absolute;
  width: 6px;
  height: 6px;
  background: #c8ddf7;
  border-radius: 50%;
  bottom: 12px;
  left: 24px;
  opacity: 0;
  animation: dust-puff 0.8s ease-out infinite;
}
.dust-2 {
  left: 18px;
  animation-delay: 0.2s;
  width: 4px;
  height: 4px;
}
.dust-3 {
  left: 14px;
  animation-delay: 0.4s;
  width: 3px;
  height: 3px;
}

.loading-text {
  font-family: 'Nunito', sans-serif;
  font-weight: 800;
  font-size: 16px;
  color: #2d3748;
  margin: 0;
}

.loading-dots {
  display: flex;
  gap: 6px;
}
.dot {
  width: 8px;
  height: 8px;
  background: var(--blue, #4a90e2);
  border-radius: 50%;
  animation: dot-pulse 1.2s ease-in-out infinite;
}
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }


@keyframes car-bounce {
  0%, 100% { transform: translateX(-50%) translateY(0); }
  50% { transform: translateX(-50%) translateY(-4px); }
}

@keyframes road-scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-30px); }
}

@keyframes dust-puff {
  0% { opacity: 0.6; transform: translate(0, 0) scale(1); }
  100% { opacity: 0; transform: translate(-20px, -8px) scale(0.3); }
}

@keyframes dot-pulse {
  0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
  40% { transform: scale(1); opacity: 1; }
}
</style>
