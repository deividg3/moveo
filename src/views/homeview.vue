<template>
  <div class="home">
    
    <div class="greeting">
      <h1 class="greeting-title">¡Hola, {{ displayName }}! <i class="fas fa-hand-paper"></i></h1>
      <p class="greeting-sub">{{ motivationText }}</p>
    </div>
 
    
    <div class="review-alert" v-if="reviewDue" @click="goReview">
      <span class="review-icon"><i class="fas fa-sync-alt"></i></span>
      <div>
        <strong>¡Tienes un repaso pendiente!</strong>
        <p>Refuerza las preguntas que más te cuestan.</p>
      </div>
      <i class="fas fa-chevron-right" style="color: #f4a828;"></i>
    </div>
 
    
    <div class="quick-stats">
      <div class="stat-card">
        <span class="stat-icon"><i class="fas fa-check-circle"></i></span>
        <span class="stat-value">{{ state.testsCompleted }}</span>
        <span class="stat-label">Tests hechos</span>
      </div>
      <div class="stat-card">
        <span class="stat-icon"><i class="fas fa-fire"></i></span>
        <span class="stat-value">{{ state.streak }}</span>
        <span class="stat-label">Días seguidos</span>
      </div>
      <div class="stat-card">
        <span class="stat-icon"><i class="fas fa-bullseye"></i></span>
        <span class="stat-value">{{ globalPct !== null ? globalPct + '%' : '—' }}</span>
        <span class="stat-label">Acierto global</span>
      </div>
    </div>
 
    
    <button class="btn-primary big" @click="$router.push('/test')">
      <i class="fas fa-play"></i> Empezar test
    </button>
 
    
    <div class="section-header">
      <div class="section-title">Progreso por temas</div>
      <div class="view-toggle">
        <button
          class="toggle-btn"
          :class="{ active: viewMode === 'list' }"
          @click="setViewMode('list')"
          title="Vista lista"
        >
          <i class="fas fa-list"></i>
        </button>
        <button
          class="toggle-btn"
          :class="{ active: viewMode === 'grid' }"
          @click="setViewMode('grid')"
          title="Vista retícula"
        >
          <i class="fas fa-th"></i>
        </button>
        <button
          class="toggle-btn"
          :class="{ active: viewMode === 'cards' }"
          @click="setViewMode('cards')"
          title="Vista tarjetas"
        >
          <i class="fas fa-id-card"></i>
        </button>
      </div>
    </div>

    
    <div class="topics-list" v-if="viewMode === 'list'">
      <div
        class="topic-row"
        v-for="t in topicProgress"
        :key="t.id"
        @click="startTopicTest(t.id)"
      >
        <div class="topic-info">
          <span class="topic-name">{{ t.label }}</span>
          <span class="topic-pct" :class="pctClass(t.pct)">
            {{ t.pct !== null ? t.pct + '%' : 'Sin datos' }}
          </span>
        </div>
        <div class="topic-bar-bg">
          <div
            class="topic-bar-fill"
            :class="pctClass(t.pct)"
            :style="{ width: (t.pct || 0) + '%' }"
          ></div>
        </div>
        <div class="topic-meta">{{ t.seen }}/{{ t.total }} preguntas vistas</div>
      </div>
    </div>

    
    <div class="topics-grid" v-if="viewMode === 'grid'">
      <div
        class="grid-item"
        v-for="t in topicProgress"
        :key="t.id"
        @click="startTopicTest(t.id)"
      >
        <div class="grid-pct-ring">
          <svg viewBox="0 0 40 40" class="ring-svg">
            <circle cx="20" cy="20" r="16" fill="none" stroke="#e2ecf8" stroke-width="3"/>
            <circle
              cx="20" cy="20" r="16" fill="none"
              :stroke="pctColor(t.pct)" stroke-width="3"
              stroke-linecap="round"
              :stroke-dasharray="100.53"
              :stroke-dashoffset="100.53 * (1 - (t.pct || 0) / 100)"
              transform="rotate(-90 20 20)"
            />
          </svg>
          <span class="grid-pct-num">{{ t.pct !== null ? t.pct : '—' }}</span>
        </div>
        <span class="grid-label">{{ t.label }}</span>
        <span class="grid-meta">{{ t.seen }}/{{ t.total }}</span>
      </div>
    </div>

    
    <div class="topics-cards" v-if="viewMode === 'cards'">
      <div
        class="card-item"
        v-for="t in topicProgress"
        :key="t.id"
        @click="startTopicTest(t.id)"
      >
        <div class="card-top">
          <span class="card-label">{{ t.label }}</span>
          <span class="card-pct" :class="pctClass(t.pct)">
            {{ t.pct !== null ? t.pct + '%' : '—' }}
          </span>
        </div>
        <div class="card-bar-bg">
          <div
            class="card-bar-fill"
            :class="pctClass(t.pct)"
            :style="{ width: (t.pct || 0) + '%' }"
          ></div>
        </div>
        <div class="card-bottom">
          <span><i class="fas fa-eye"></i> {{ t.seen }} vistas</span>
          <span><i class="fas fa-layer-group"></i> {{ t.total }} total</span>
        </div>
      </div>
    </div>
 
    
    <button class="btn-ghost" @click="$router.push('/stats')">
      <i class="fas fa-chart-bar"></i> Ver estadísticas detalladas →
    </button>
  </div>
</template>
 
<script>
import { useProgress } from '@/composables/useprogress.js'
import questions from '@/data/questions.json'
 

function capitalizeName(name) {
  if (!name) return ''
  return name
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

export default {
  name: 'homeview',
  setup() {
    const progress = useProgress()
    return { progress }
  },
  data() {
    const p = useProgress()
    const state = p.getState()

    
    const savedView = localStorage.getItem('moveo_view_mode') || 'list'

    return {
      state,
      topicProgress: p.getTopicProgress(questions),
      reviewDue: p.isReviewDue(),
      viewMode: savedView,
    }
  },
  computed: {
    displayName() {
      const name = localStorage.getItem('moveo_name') || ''
      return capitalizeName(name.split(' ')[0]) || 'tú'
    },
    globalPct() {
      const all = Object.values(this.state.questionStats)
      if (!all.length) return null
      const correct = all.reduce((s, q) => s + q.correct, 0)
      const total   = all.reduce((s, q) => s + q.seen, 0)
      return total > 0 ? Math.round((correct / total) * 100) : null
    },
    motivationText() {
      const pct = this.globalPct
      if (pct === null) return 'Haz tu primer test para empezar el camino.'
      if (pct >= 80) return '¡Vas muy bien! Mantén el ritmo.'
      if (pct >= 60) return 'Buen progreso. Sigue practicando.'
      return 'Cada fallo es un paso adelante. ¡Tú puedes!'
    },
  },
  methods: {
    pctClass(pct) {
      if (pct === null) return 'neutral'
      if (pct >= 80) return 'good'
      if (pct >= 60) return 'mid'
      return 'bad'
    },
    pctColor(pct) {
      if (pct === null) return '#c8ddf7'
      if (pct >= 80) return '#58cc02'
      if (pct >= 60) return '#f4a828'
      return '#ff6b6b'
    },
    goReview() {
      this.$router.push('/test/review')
    },
    startTopicTest(topicId) {
      this.$router.push({ path: '/test/session', query: { topic: topicId } })
    },
    setViewMode(mode) {
      this.viewMode = mode
      
      localStorage.setItem('moveo_view_mode', mode)
    },
  },
}
</script>
 
<style scoped>
.home {
  padding: 24px 20px 40px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  max-width: 480px;
  margin: 0 auto;
  width: 100%;
}
 

.greeting-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900;
  font-size: 24px;
  color: #2d3748;
}
.greeting-sub {
  font-size: 14px;
  color: #718096;
  margin-top: 4px;
}
 

.review-alert {
  display: flex; align-items: center; gap: 12px;
  background: #fff8e6;
  border: 2px solid #f4a828;
  border-radius: 18px;
  padding: 14px 16px;
  cursor: pointer;
  transition: transform 0.15s;
}
.review-alert:hover { transform: translateY(-2px); }
.review-icon { font-size: 22px; flex-shrink: 0; color: #f4a828; }
.review-alert div { flex: 1; }
.review-alert strong { font-family: 'Nunito', sans-serif; font-size: 14px; color: #2d3748; }
.review-alert p { font-size: 12px; color: #718096; margin-top: 2px; }
 

.quick-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}
.stat-card {
  background: white;
  border-radius: 18px;
  padding: 14px 10px;
  display: flex; flex-direction: column; align-items: center; gap: 4px;
  box-shadow: 0 2px 12px rgba(74,144,226,0.08);
  transition: transform 0.15s;
}
.stat-card:hover { transform: translateY(-2px); }
.stat-icon  { font-size: 20px; color: var(--blue, #4a90e2); }
.stat-value {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 22px; color: #2d3748;
}
.stat-label { font-size: 11px; color: #718096; text-align: center; }
 

.btn-primary {
  width: 100%;
  background: var(--blue);
  color: white;
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 16px;
  letter-spacing: 1px; text-transform: uppercase;
  border: none; border-radius: 999px;
  padding: 18px 24px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 10px;
  box-shadow: 0 4px 0 var(--blue-dark), 0 8px 24px rgba(74,144,226,0.25);
  transition: transform 0.12s, box-shadow 0.12s;
}
.btn-primary:active { transform: translateY(3px); box-shadow: 0 1px 0 var(--blue-dark); }
.btn-primary:hover  { background: #3a7fd0; }


.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
 
.section-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 16px; color: #2d3748;
}

.view-toggle {
  display: flex;
  gap: 4px;
  background: #e8f0fb;
  border-radius: 12px;
  padding: 3px;
}
.toggle-btn {
  width: 34px; height: 34px;
  border: none;
  border-radius: 10px;
  background: transparent;
  color: #718096;
  font-size: 14px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s ease;
}
.toggle-btn.active {
  background: white;
  color: var(--blue, #4a90e2);
  box-shadow: 0 2px 8px rgba(74,144,226,0.15);
}
.toggle-btn:hover:not(.active) {
  color: #4a5568;
}


.topics-list { display: flex; flex-direction: column; gap: 12px; }
.topic-row {
  background: white;
  border-radius: 16px;
  padding: 14px 16px;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(74,144,226,0.07);
  transition: transform 0.15s;
}
.topic-row:hover { transform: translateY(-2px); }
.topic-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.topic-name { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 14px; color: #2d3748; }
.topic-pct  { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px; }
.topic-pct.good    { color: #58cc02; }
.topic-pct.mid     { color: #f4a828; }
.topic-pct.bad     { color: #ff6b6b; }
.topic-pct.neutral { color: #718096; }
 
.topic-bar-bg { height: 6px; background: #e2ecf8; border-radius: 3px; overflow: hidden; margin-bottom: 6px; }
.topic-bar-fill {
  height: 100%; border-radius: 3px;
  transition: width 0.5s cubic-bezier(.4,0,.2,1);
}
.topic-bar-fill.good    { background: #58cc02; }
.topic-bar-fill.mid     { background: #f4a828; }
.topic-bar-fill.bad     { background: #ff6b6b; }
.topic-bar-fill.neutral { background: #c8ddf7; }
 
.topic-meta { font-size: 11px; color: #a0aec0; }


.topics-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}
.grid-item {
  background: white;
  border-radius: 18px;
  padding: 18px 14px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(74,144,226,0.07);
  transition: transform 0.15s, box-shadow 0.15s;
}
.grid-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(74,144,226,0.14);
}

.grid-pct-ring {
  position: relative;
  width: 48px; height: 48px;
}
.grid-pct-ring .ring-svg {
  width: 100%; height: 100%;
}
.grid-pct-num {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 12px; color: #2d3748;
}

.grid-label {
  font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 13px; color: #2d3748;
  text-align: center;
  line-height: 1.2;
}
.grid-meta {
  font-size: 11px; color: #a0aec0;
}


.topics-cards {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.card-item {
  background: white;
  border-radius: 18px;
  padding: 16px;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(74,144,226,0.07);
  transition: transform 0.15s;
  border-left: 4px solid var(--blue, #4a90e2);
}
.card-item:hover { transform: translateY(-2px); }

.card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}
.card-label {
  font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 15px; color: #2d3748;
}
.card-pct {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 18px;
}
.card-pct.good    { color: #58cc02; }
.card-pct.mid     { color: #f4a828; }
.card-pct.bad     { color: #ff6b6b; }
.card-pct.neutral { color: #c8ddf7; }

.card-bar-bg {
  height: 6px; background: #e2ecf8; border-radius: 3px;
  overflow: hidden; margin-bottom: 10px;
}
.card-bar-fill {
  height: 100%; border-radius: 3px;
  transition: width 0.5s cubic-bezier(.4,0,.2,1);
}
.card-bar-fill.good    { background: #58cc02; }
.card-bar-fill.mid     { background: #f4a828; }
.card-bar-fill.bad     { background: #ff6b6b; }
.card-bar-fill.neutral { background: #c8ddf7; }

.card-bottom {
  display: flex;
  gap: 16px;
  font-size: 12px;
  color: #a0aec0;
}
.card-bottom i {
  margin-right: 4px;
  font-size: 11px;
}
 

.btn-ghost {
  background: none; border: none;
  color: var(--blue); font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 14px;
  cursor: pointer; padding: 8px 0; text-align: center;
  width: 100%;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: color 0.2s;
}
.btn-ghost:hover { color: #2c6fc7; }
</style>