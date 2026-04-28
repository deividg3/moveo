<template>
  <div class="selector">
    <div class="selector-header">
      <button class="back-btn" @click="$router.push('/home')">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
          <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <h1 class="header-title">Elige un test</h1>
    </div>
 
    
    <div class="mode-section">
      <p class="mode-label"><i class="fas fa-dice" style="margin-right: 6px;"></i> Test aleatorio</p>
      <div class="mode-card featured" @click="startRandom">
        <div class="mode-card-content">
          <span class="mode-icon"><i class="fas fa-bullseye"></i></span>
          <div>
            <strong>Test completo</strong>
            <p>10 preguntas de todos los temas mezcladas</p>
          </div>
        </div>
        <div class="mode-badge">Recomendado</div>
      </div>
    </div>
 
    
    <div class="mode-section">
      <p class="mode-label"><i class="fas fa-book" style="margin-right: 6px;"></i> Por tema</p>
      <div
        class="mode-card"
        v-for="t in topics"
        :key="t.id"
        @click="startTopic(t.id)"
      >
        <div class="mode-card-content">
          <span class="mode-icon" v-html="t.icon"></span>
          <div>
            <strong>{{ t.label }}</strong>
            <p>{{ t.count }} preguntas disponibles</p>
          </div>
        </div>
        <div class="topic-pct" :class="pctClass(t.pct)">
          {{ t.pct !== null ? t.pct + '%' : '—' }}
        </div>
      </div>
    </div>
 
    
    <div class="mode-section" v-if="hasWrong">
      <p class="mode-label"><i class="fas fa-redo" style="margin-right: 6px;"></i> Repaso</p>
      <div class="mode-card review-card" @click="$router.push('/test/review')">
        <div class="mode-card-content">
          <span class="mode-icon"><i class="fas fa-history"></i></span>
          <div>
            <strong>Repaso de errores</strong>
            <p>Las preguntas que más te cuestan</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
import { useProgress } from '@/composables/useprogress.js'
import questions from '@/data/questions.json'
 
export default {
  name: 'testselector',
  setup() {
    return { progress: useProgress() }
  },
  data() {
    const p = useProgress()
    const state = p.getState()
    const topicMap = {}
    questions.forEach(q => {
      if (!topicMap[q.topic]) topicMap[q.topic] = { label: q.topic_label, count: 0 }
      topicMap[q.topic].count++
    })
 
    const icons = {
      señales: '<i class="fas fa-traffic-light"></i>', 
      normas: '<i class="fas fa-clipboard-list"></i>', 
      vehiculo: '<i class="fas fa-car"></i>',
      seguridad: '<i class="fas fa-shield-alt"></i>', 
      preferencia: '<i class="fas fa-star"></i>', 
      medioambiente: '<i class="fas fa-leaf"></i>',
    }
 
    const topicProgress = p.getTopicProgress(questions)
 
    const topics = Object.entries(topicMap).map(([id, t]) => {
      const tp = topicProgress.find(x => x.id === id)
      return { id, label: t.label, count: t.count, icon: icons[id] || '<i class="fas fa-bookmark"></i>', pct: tp?.pct ?? null }
    })
 
    const wrongQs = p.getWrongQuestions(questions)
 
    return {
      topics,
      hasWrong: wrongQs.length > 0,
      state,
    }
  },
  methods: {
    startRandom() {
      this.$router.push({ path: '/test/session', query: { mode: 'random' } })
    },
    startTopic(topicId) {
      this.$router.push({ path: '/test/session', query: { mode: 'topic', topic: topicId } })
    },
    pctClass(pct) {
      if (pct === null) return 'neutral'
      if (pct >= 80) return 'good'
      if (pct >= 60) return 'mid'
      return 'bad'
    },
  },
}
</script>
 
<style scoped>
.selector {
  padding: 0 20px 40px;
  max-width: 480px; margin: 0 auto; width: 100%;
  display: flex; flex-direction: column; gap: 20px;
}
 
.selector-header {
  display: flex; align-items: center; gap: 12px;
  padding: 20px 0 4px;
}
.back-btn {
  width: 40px; height: 40px; border-radius: 50%;
  border: none; background: white; color: var(--blue);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 10px rgba(74,144,226,0.15);
  cursor: pointer; flex-shrink: 0;
}
.header-title {
  font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 22px; color: #2d3748;
}
 
.mode-label {
  font-family: 'Nunito', sans-serif;
  font-weight: 800; font-size: 13px;
  color: #718096; text-transform: uppercase;
  letter-spacing: 0.5px; margin-bottom: 10px;
}
.mode-section { display: flex; flex-direction: column; gap: 0; }
 
.mode-card {
  background: white;
  border-radius: 18px; border: 2px solid #e8f0fb;
  padding: 16px; cursor: pointer;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 10px;
  transition: transform 0.15s, box-shadow 0.15s;
  box-shadow: 0 2px 10px rgba(74,144,226,0.07);
}
.mode-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(74,144,226,0.14); }
.mode-card.featured {
  border-color: var(--blue);
  background: linear-gradient(135deg, #f0f7ff, white);
}
.mode-card.review-card { border-color: #f4a828; background: #fffbf0; }
 
.mode-card-content {
  display: flex; align-items: center; gap: 14px;
}
.mode-icon { font-size: 28px; flex-shrink: 0; }
.mode-card-content strong {
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 15px; color: #2d3748; display: block;
}
.mode-card-content p { font-size: 12px; color: #718096; margin-top: 2px; }
 
.mode-badge {
  background: var(--blue); color: white;
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 11px; padding: 4px 10px; border-radius: 999px;
  flex-shrink: 0;
}
 
.topic-pct {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 16px; flex-shrink: 0;
}
.topic-pct.good    { color: #58cc02; }
.topic-pct.mid     { color: #f4a828; }
.topic-pct.bad     { color: #ff6b6b; }
.topic-pct.neutral { color: #c8ddf7; }
</style>