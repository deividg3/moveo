<template>
  <div class="stats-view">
    <h1 class="page-title">Mis estadísticas</h1>
 
    
    <div class="prob-card" v-if="probability">
      <div class="prob-header">
        <span class="prob-icon"><i class="fas fa-graduation-cap"></i></span>
        <div>
          <strong>Probabilidad de aprobar</strong>
          <p>Basada en tus últimos 10 días ({{ recentTests }} tests)</p>
        </div>
      </div>
      <div class="prob-value" :class="probClass">{{ probability.prob }}%</div>
      <div class="prob-bar-bg">
        <div class="prob-bar-fill" :class="probClass" :style="{ width: probability.prob + '%' }"></div>
      </div>
      <p class="prob-note">{{ probNote }}</p>
    </div>
    <div class="prob-card no-data" v-else>
      <span class="prob-icon"><i class="fas fa-chart-bar"></i></span>
      <p>Necesitas al menos <strong>50 tests en 10 días</strong> para calcular tu probabilidad de aprobar.</p>
      <p class="no-data-sub">Llevas {{ recentTests }} tests en los últimos 10 días.</p>
    </div>
 
    
    <div class="section-title">Aciertos últimos 10 días</div>
    <div class="chart-card">
      <div class="chart-bars">
        <div
          class="chart-col"
          v-for="day in last10Days"
          :key="day.date"
        >
          <span class="bar-pct">{{ day.pct !== null ? day.pct + '%' : '' }}</span>
          <div class="bar-track">
            <div
              class="bar-fill"
              :class="barClass(day.pct)"
              :style="{ height: (day.pct || 0) + '%' }"
            ></div>
          </div>
          <span class="bar-label">{{ day.label }}</span>
          <span class="bar-tests">{{ day.tests > 0 ? day.tests + '<i class="fas fa-check"></i>' : '' }}</span>
        </div>
      </div>
    </div>
 
    
    <div class="section-title">Resumen global</div>
    <div class="totals-grid">
      <div class="total-card" v-for="t in totals" :key="t.label">
        <span class="total-icon">{{ t.icon }}</span>
        <span class="total-value">{{ t.value }}</span>
        <span class="total-label">{{ t.label }}</span>
      </div>
    </div>
 
    
    <div class="section-title">Por tema</div>
    <div class="topics-table">
      <div class="topic-row-stat" v-for="t in topicProgress" :key="t.id">
        <span class="tr-label">{{ t.label }}</span>
        <div class="tr-bar-bg">
          <div class="tr-bar-fill" :class="pctClass(t.pct)" :style="{ width: (t.pct || 0) + '%' }"></div>
        </div>
        <span class="tr-pct" :class="pctClass(t.pct)">
          {{ t.pct !== null ? t.pct + '%' : '—' }}
        </span>
      </div>
    </div>
 
    
    <div class="section-title">Preguntas más difíciles</div>
    <div class="hard-list">
      <div class="hard-row" v-for="q in hardQuestions" :key="q.id">
        <div class="hard-q">{{ q.question }}</div>
        <div class="hard-stats">
          <span class="hard-wrong">{{ q.wrong }} <i class="fas fa-times"></i></span>
          <span class="hard-pct">{{ q.pct }}% acierto</span>
        </div>
      </div>
      <p class="hard-empty" v-if="!hardQuestions.length">Aún no tienes historial de preguntas.</p>
    </div>

    
    <button class="logout-btn" @click="logout">Cerrar sesión</button>
  </div>
</template>
 
<script>
import { useProgress } from '@/composables/useProgress.js'
import allQuestions from '@/data/questions.json'
 
export default {
  name: 'StatsView',
  setup() { return { progress: useProgress() } },
  data() {
    const p = useProgress()
    const last10Days  = p.getLast10DaysStats()
    const probability = p.getApprovalProbability()
    const state       = p.getState()
    const topicProgress = p.getTopicProgress(allQuestions)
 
    const recentTests = last10Days.reduce((s, d) => s + d.tests, 0)
 
    
    const hardQuestions = Object.entries(state.questionStats)
      .filter(([, s]) => s.wrong > 0)
      .map(([id, s]) => {
        const q = allQuestions.find(q => q.id === Number(id))
        return {
          id: Number(id),
          question: q?.question || '',
          wrong: s.wrong,
          pct: Math.round((s.correct / s.seen) * 100),
        }
      })
      .sort((a, b) => b.wrong - a.wrong)
      .slice(0, 5)
 
    const totalCorrect = Object.values(state.questionStats).reduce((s, q) => s + q.correct, 0)
    const totalSeen    = Object.values(state.questionStats).reduce((s, q) => s + q.seen, 0)
 
    const totals = [
      { icon: '<i class="fas fa-file-alt"></i>', value: state.testsCompleted, label: 'Tests hechos' },
      { icon: '<i class="fas fa-check-circle"></i>', value: totalCorrect, label: 'Aciertos' },
      { icon: '<i class="fas fa-times-circle"></i>', value: totalSeen - totalCorrect, label: 'Fallos' },
      { icon: '<i class="fas fa-fire"></i>', value: state.streak, label: 'Racha' },
      { icon: '<i class="fas fa-chart-bar"></i>', value: totalSeen > 0 ? Math.round(totalCorrect / totalSeen * 100) + '%' : '—', label: 'Acierto global' },
      { icon: '<i class="fas fa-book"></i>', value: Object.keys(state.questionStats).length, label: 'Preguntas vistas' },
    ]
 
    return { last10Days, probability, recentTests, topicProgress, hardQuestions, totals, state }
  },
  computed: {
    probClass() {
      if (!this.probability) return ''
      return this.probability.prob >= 70 ? 'good' : this.probability.prob >= 50 ? 'mid' : 'bad'
    },
    probNote() {
      if (!this.probability) return ''
      const p = this.probability.prob
      if (p >= 80) return '¡Estás listo! Considera presentarte pronto.'
      if (p >= 60) return 'Vas bien. Sigue practicando un poco más.'
      return 'Necesitas más práctica antes de presentarte.'
    },
  },
  methods: {
    logout() {
      localStorage.removeItem('user_authenticated')
      localStorage.removeItem('moveo_name')
      localStorage.removeItem('moveo_user_id')
      this.$router.push('/login')
    },
    pctClass(pct) {
      if (pct === null) return 'neutral'
      if (pct >= 80) return 'good'
      if (pct >= 60) return 'mid'
      return 'bad'
    },
    barClass(pct) {
      if (pct === null) return 'neutral'
      if (pct >= 70)   return 'good'
      if (pct >= 50)   return 'mid'
      return 'bad'
    },
  },
}
</script>
 
<style scoped>
.stats-view {
  padding: 24px 20px 48px;
  max-width: 480px; margin: 0 auto; width: 100%;
  display: flex; flex-direction: column; gap: 16px;
}
 
.page-title {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 24px; color: #2d3748;
}
 

.prob-card {
  background: white; border-radius: 20px; padding: 20px;
  box-shadow: 0 2px 14px rgba(74,144,226,0.10);
}
.prob-card.no-data {
  display: flex; flex-direction: column; gap: 8px;
  align-items: center; text-align: center;
}
.prob-header {
  display: flex; gap: 12px; align-items: center; margin-bottom: 16px;
}
.prob-icon { font-size: 30px; flex-shrink: 0; }
.prob-header strong { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 15px; }
.prob-header p { font-size: 12px; color: #718096; margin-top: 2px; }
.prob-value {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 48px; text-align: center; margin-bottom: 12px;
}
.prob-value.good { color: #58cc02; }
.prob-value.mid  { color: #f4a828; }
.prob-value.bad  { color: #ff6b6b; }
.prob-bar-bg { height: 10px; background: #e2ecf8; border-radius: 5px; overflow: hidden; margin-bottom: 10px; }
.prob-bar-fill { height: 100%; border-radius: 5px; transition: width 0.8s cubic-bezier(.4,0,.2,1); }
.prob-bar-fill.good { background: #58cc02; }
.prob-bar-fill.mid  { background: #f4a828; }
.prob-bar-fill.bad  { background: #ff6b6b; }
.prob-note { font-size: 13px; color: #718096; text-align: center; }
.no-data-sub { font-size: 12px; color: #a0aec0; }
 

.section-title {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 15px; color: #2d3748;
}
.chart-card {
  background: white; border-radius: 20px; padding: 16px 12px 10px;
  box-shadow: 0 2px 14px rgba(74,144,226,0.08);
}
.chart-bars {
  display: flex; gap: 4px; align-items: flex-end;
  height: 120px;
}
.chart-col {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; gap: 2px; height: 100%;
}
.bar-pct { font-size: 9px; color: #718096; font-weight: 700; height: 14px; }
.bar-track {
  flex: 1; width: 100%; background: #f0f7ff;
  border-radius: 4px; overflow: hidden;
  display: flex; align-items: flex-end;
}
.bar-fill {
  width: 100%; border-radius: 4px;
  transition: height 0.6s cubic-bezier(.4,0,.2,1);
  min-height: 2px;
}
.bar-fill.good    { background: #58cc02; }
.bar-fill.mid     { background: #f4a828; }
.bar-fill.bad     { background: #ff6b6b; }
.bar-fill.neutral { background: #c8ddf7; }
.bar-label { font-size: 9px; color: #a0aec0; font-weight: 600; text-align: center; }
.bar-tests { font-size: 9px; color: var(--blue); font-weight: 700; height: 12px; }
 

.totals-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
}
.total-card {
  background: white; border-radius: 16px; padding: 14px 10px;
  display: flex; flex-direction: column; align-items: center; gap: 4px;
  box-shadow: 0 2px 10px rgba(74,144,226,0.07);
}
.total-icon  { font-size: 20px; }
.total-value { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 20px; color: #2d3748; }
.total-label { font-size: 11px; color: #718096; text-align: center; }
 

.topics-table { display: flex; flex-direction: column; gap: 10px; }
.topic-row-stat {
  display: flex; align-items: center; gap: 10px;
  background: white; border-radius: 14px; padding: 12px 14px;
  box-shadow: 0 2px 8px rgba(74,144,226,0.06);
}
.tr-label { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px; color: #2d3748; width: 130px; flex-shrink: 0; }
.tr-bar-bg { flex: 1; height: 8px; background: #e2ecf8; border-radius: 4px; overflow: hidden; }
.tr-bar-fill { height: 100%; border-radius: 4px; transition: width 0.6s cubic-bezier(.4,0,.2,1); }
.tr-bar-fill.good    { background: #58cc02; }
.tr-bar-fill.mid     { background: #f4a828; }
.tr-bar-fill.bad     { background: #ff6b6b; }
.tr-bar-fill.neutral { background: #c8ddf7; }
.tr-pct { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px; width: 38px; text-align: right; }
.tr-pct.good    { color: #58cc02; }
.tr-pct.mid     { color: #f4a828; }
.tr-pct.bad     { color: #ff6b6b; }
.tr-pct.neutral { color: #a0aec0; }
 

.hard-list { display: flex; flex-direction: column; gap: 10px; }
.hard-row {
  background: white; border-radius: 14px; padding: 14px;
  box-shadow: 0 2px 8px rgba(74,144,226,0.06);
  border-left: 4px solid #ff6b6b;
}
.hard-q { font-family: 'Nunito', sans-serif; font-weight: 700; font-size: 13px; color: #2d3748; margin-bottom: 6px; }
.hard-stats { display: flex; gap: 12px; }
.hard-wrong { font-size: 12px; color: #ff6b6b; font-weight: 700; }
.hard-pct   { font-size: 12px; color: #718096; }
.hard-empty { font-size: 13px; color: #a0aec0; text-align: center; padding: 16px; }


.logout-btn {
  margin-top: 10px;
  width: 100%;
  padding: 16px;
  background: white; border: 2px solid #fee2e2;
  color: #ef4444; font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 15px;
  border-radius: 16px; cursor: pointer;
  transition: all 0.2s;
}
.logout-btn:hover { background: #fee2e2; }
</style>