<template>
  <div class="results">
    
    <div class="score-hero" :class="scoreClass">
      <div class="score-ring">
        <svg viewBox="0 0 120 120" class="ring-svg">
          <circle cx="60" cy="60" r="52" fill="none" stroke="#e2ecf8" stroke-width="10"/>
          <circle
            cx="60" cy="60" r="52" fill="none"
            :stroke="ringColor" stroke-width="10"
            stroke-linecap="round"
            :stroke-dasharray="circumference"
            :stroke-dashoffset="dashOffset"
            transform="rotate(-90 60 60)"
            class="ring-progress"
          />
        </svg>
        <div class="score-center">
          <span class="score-num">{{ score }}</span>
          <span class="score-denom">/{{ total }}</span>
        </div>
      </div>
      <h2 class="score-label">{{ scoreLabel }}</h2>
      <p class="score-sub">{{ scoreSub }}</p>
    </div>
 
    
    <div class="review-section">
      <p class="review-title">Repaso rápido</p>
      <div
        class="review-row"
        v-for="(item, i) in reviewItems"
        :key="i"
        :class="item.correct ? 'rev-correct' : 'rev-wrong'"
      >
        <span class="rev-icon">{{ item.correct ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>' }}</span>
        <div class="rev-content">
          <p class="rev-q">{{ item.question }}</p>
          <p class="rev-ans" v-if="!item.correct">
            Tu respuesta: <em>{{ item.yourAnswer }}</em><br/>
            Correcta: <strong>{{ item.correctAnswer }}</strong>
          </p>
        </div>
      </div>
    </div>
 
    
    <div class="actions">
      <button class="btn-primary" @click="$router.push('/test')">
        Nuevo test
      </button>
      <button class="btn-ghost" @click="$router.push('/home')">
        Volver al inicio
      </button>
    </div>
  </div>
</template>
 
<script>
import allQuestions from '@/data/questions.json'
 
export default {
  name: 'TestResults',
  data() {
    const score   = Number(this.$route.query.score)
    const total   = Number(this.$route.query.total)
    const answers = JSON.parse(this.$route.query.answers || '[]')
    const qids    = JSON.parse(this.$route.query.qids    || '[]')
 
    const reviewItems = qids.map((id, i) => {
      const q   = allQuestions.find(q => q.id === id)
      const ans = answers[i]
      return {
        question:      q?.question || '',
        correct:       ans?.correct,
        yourAnswer:    ans?.selected >= 0 ? q?.options[ans.selected] : 'Sin respuesta',
        correctAnswer: q?.options[q.correct],
      }
    })
 
    return { score, total, reviewItems }
  },
  computed: {
    pct()          { return Math.round(this.score / this.total * 100) },
    scoreClass()   { return this.pct >= 70 ? 'hero-good' : this.pct >= 50 ? 'hero-mid' : 'hero-bad' },
    ringColor()    { return this.pct >= 70 ? '#58cc02' : this.pct >= 50 ? '#f4a828' : '#ff6b6b' },
    scoreLabel()   {
      if (this.pct >= 90) return '¡Perfecto! <i class="fas fa-trophy"></i>'
      if (this.pct >= 70) return '¡Muy bien! <i class="fas fa-star"></i>'
      if (this.pct >= 50) return 'Puedes mejorar <i class="fas fa-dumbbell"></i>'
      return 'A seguir practicando <i class="fas fa-book"></i>'
    },
    scoreSub() {
      if (this.pct >= 70) return `${this.score} aciertos de ${this.total}. ¡Sigue así!`
      return `${this.score} aciertos de ${this.total}. Repasa los temas fallados.`
    },
    circumference() { return 2 * Math.PI * 52 },
    dashOffset()    { return this.circumference * (1 - this.pct / 100) },
  },
}
</script>
 
<style scoped>
.results {
  min-height: 100dvh;
  display: flex; flex-direction: column;
  padding-bottom: 40px;
  background: #f0f7ff;
}
 

.score-hero {
  display: flex; flex-direction: column; align-items: center;
  padding: 40px 20px 32px;
  background: white;
  box-shadow: 0 4px 20px rgba(74,144,226,0.10);
}
.score-ring { position: relative; width: 120px; height: 120px; margin-bottom: 16px; }
.ring-svg   { width: 100%; height: 100%; }
.ring-progress { transition: stroke-dashoffset 0.8s cubic-bezier(.4,0,.2,1); }
.score-center {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
}
.score-num   { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 36px; color: #2d3748; }
.score-denom { font-family: 'Nunito', sans-serif; font-size: 18px; color: #718096; }
 
.score-label {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 24px; color: #2d3748; margin-bottom: 6px;
}
.score-sub { font-size: 14px; color: #718096; text-align: center; }
 

.review-section {
  padding: 20px; flex: 1;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.review-title {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 16px; color: #2d3748; margin-bottom: 12px;
}
.review-row {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 12px 14px; border-radius: 14px;
  margin-bottom: 8px; border-left: 4px solid transparent;
}
.rev-correct { background: #f0fff0; border-left-color: #58cc02; }
.rev-wrong   { background: #fff5f5; border-left-color: #ff6b6b; }
.rev-icon    { font-size: 16px; flex-shrink: 0; margin-top: 2px; }
.rev-q       { font-family: 'Nunito', sans-serif; font-weight: 700; font-size: 13px; color: #2d3748; }
.rev-ans     { font-size: 12px; color: #718096; margin-top: 4px; line-height: 1.5; }
.rev-ans em  { color: #ff6b6b; }
.rev-ans strong { color: #58cc02; }
 

.actions {
  padding: 0 20px;
  max-width: 480px; margin: 0 auto; width: 100%;
  display: flex; flex-direction: column; gap: 10px;
}
.btn-primary {
  width: 100%; background: var(--blue); color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 16px; letter-spacing: 1px; text-transform: uppercase;
  border: none; border-radius: 999px; padding: 18px;
  cursor: pointer;
  box-shadow: 0 4px 0 var(--blue-dark), 0 8px 24px rgba(74,144,226,0.25);
  transition: transform 0.12s;
}
.btn-primary:active { transform: translateY(3px); }
.btn-ghost {
  background: none; border: none; color: var(--blue);
  font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 14px;
  cursor: pointer; padding: 10px; text-align: center;
}
</style>