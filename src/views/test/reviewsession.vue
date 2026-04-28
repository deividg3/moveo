<template>
  <div class="session">
    <div class="session-header">
      <button class="exit-btn" @click="$router.push('/test')"><i class="fas fa-times"></i></button>
      <div class="progress-wrap">
        <div class="review-chip"><i class="fas fa-redo"></i> Repaso de errores</div>
        <span class="progress-label">{{ current + 1 }}/{{ total }}</span>
      </div>
    </div>
 
    <div v-if="!questions.length" class="empty-state">
      <span style="font-size:52px"><i class="fas fa-star"></i></span>
      <h2>¡Sin errores pendientes!</h2>
      <p>Has repasado todo. Sigue haciendo tests para acumular más.</p>
      <button class="btn-primary" @click="$router.push('/test')">Hacer test</button>
    </div>
 
    <template v-else>
      <transition name="q-fade" mode="out-in">
        <div class="question-wrap" :key="current">
          <div class="topic-chip">{{ currentQ.topic_label }}</div>
          <div class="wrong-badge">
            <i class="fas fa-exclamation-triangle"></i> Fallada {{ wrongInfo.wrong }} veces
          </div>
          <p class="question-text">{{ currentQ.question }}</p>
 
          <div class="options">
            <button
              v-for="(opt, i) in currentQ.options"
              :key="i"
              class="option-btn"
              :class="optionClass(i)"
              :disabled="answered"
              @click="answer(i)"
            >
              <span class="option-letter">{{ letters[i] }}</span>
              <span class="option-text">{{ opt }}</span>
              <span class="option-icon" v-if="answered">
                <template v-if="i === currentQ.correct"><i class="fas fa-check"></i></template>
                <template v-else-if="i === selected && i !== currentQ.correct"><i class="fas fa-times"></i></template>
              </span>
            </button>
          </div>
 
          <transition name="slide-up">
            <div class="explanation" v-if="answered">
              <span class="expl-icon">{{ selected === currentQ.correct ? '<i class="fas fa-star"></i>' : '<i class="fas fa-lightbulb"></i>' }}</span>
              <p>{{ currentQ.explanation }}</p>
            </div>
          </transition>
        </div>
      </transition>
 
      <div class="session-footer">
        <button class="btn-next" v-if="answered" @click="next">
          {{ isLast ? 'Finalizar repaso' : 'Siguiente →' }}
        </button>
      </div>
    </template>
  </div>
</template>
 
<script>
import { useProgress } from '@/composables/useProgress.js'
import allQuestions from '@/data/questions.json'
 
export default {
  name: 'ReviewSession',
  setup() { return { progress: useProgress() } },
  data() {
    const questions = this.progress.getWrongQuestions(allQuestions, 10)
    const state     = this.progress.getState()
    return {
      questions,
      current:  0,
      selected: null,
      answered: false,
      letters:  ['A', 'B', 'C'],
      answers:  [],
      state,
    }
  },
  computed: {
    total()    { return this.questions.length },
    currentQ() { return this.questions[this.current] || {} },
    isLast()   { return this.current === this.total - 1 },
    wrongInfo() {
      const s = this.state.questionStats[this.currentQ.id]
      return s || { wrong: 0, seen: 0 }
    },
  },
  methods: {
    answer(idx) {
      if (this.answered) return
      this.selected = idx
      this.answered = true
      const correct = idx === this.currentQ.correct
      this.answers.push({ qId: this.currentQ.id, correct, selected: idx })
    },
    next() {
      if (this.isLast) {
        this.progress.saveTestResult({
          topic:     'review',
          answers:   this.answers,
          questions: this.questions,
        })
        this.$router.push('/home')
        return
      }
      this.current++
      this.selected = null
      this.answered = false
    },
    optionClass(i) {
      if (!this.answered) return ''
      if (i === this.currentQ.correct)                      return 'correct'
      if (i === this.selected && i !== this.currentQ.correct) return 'wrong'
      return 'dimmed'
    },
  },
}
</script>
 
<style scoped>
.session {
  min-height: 100dvh; display: flex; flex-direction: column; background: #f0f7ff;
}
.session-header {
  display: flex; align-items: center; gap: 12px;
  padding: 16px 20px; background: white;
  box-shadow: 0 2px 10px rgba(74,144,226,0.08);
}
.exit-btn {
  width: 36px; height: 36px; border-radius: 50%;
  border: none; background: #f0f7ff; color: #718096;
  font-size: 14px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.progress-wrap { flex: 1; display: flex; align-items: center; justify-content: space-between; }
.review-chip {
  background: #fff8e6; color: #f4a828;
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 13px; padding: 4px 12px; border-radius: 999px;
  border: 1.5px solid #f4a828;
}
.progress-label { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px; color: #718096; }
 
.question-wrap {
  flex: 1; padding: 24px 20px 16px;
  display: flex; flex-direction: column; gap: 14px;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.topic-chip {
  background: var(--blue-light); color: var(--blue-dark);
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 12px; padding: 4px 12px; border-radius: 999px;
  width: fit-content; text-transform: uppercase;
}
.wrong-badge {
  background: #fff8e6; color: #c07c00;
  font-family: 'Nunito', sans-serif; font-weight: 700;
  font-size: 12px; padding: 4px 12px; border-radius: 999px;
  width: fit-content; border: 1.5px solid #f4a828;
}
.question-text {
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 20px; color: #2d3748; line-height: 1.4;
}
.options { display: flex; flex-direction: column; gap: 10px; }
.option-btn {
  display: flex; align-items: center; gap: 12px;
  padding: 16px; border-radius: 18px;
  border: 2.5px solid #c8ddf7; background: white;
  cursor: pointer; text-align: left;
  transition: all 0.18s;
  box-shadow: 0 2px 8px rgba(74,144,226,0.06);
}
.option-btn:not(:disabled):hover { border-color: var(--blue); transform: translateY(-1px); }
.option-btn:disabled { cursor: default; }
.option-letter {
  width: 32px; height: 32px; border-radius: 50%;
  background: #f0f7ff; color: var(--blue);
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 14px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.option-text { flex: 1; font-family: 'Nunito Sans', sans-serif; font-weight: 700; font-size: 15px; color: #2d3748; }
.option-icon { font-size: 18px; flex-shrink: 0; }
.option-btn.correct { border-color: var(--green); background: #f0fff0; }
.option-btn.correct .option-letter { background: var(--green); color: white; }
.option-btn.wrong   { border-color: #ff6b6b; background: #fff0f0; }
.option-btn.wrong   .option-letter { background: #ff6b6b; color: white; }
.option-btn.dimmed  { opacity: 0.4; }
 
.explanation {
  display: flex; gap: 12px; align-items: flex-start;
  background: white; border-radius: 16px; padding: 14px;
  border-left: 4px solid var(--blue);
}
.expl-icon { font-size: 20px; flex-shrink: 0; }
.explanation p { font-size: 14px; color: #4a5568; line-height: 1.5; }
 
.session-footer { padding: 16px 20px 32px; max-width: 480px; margin: 0 auto; width: 100%; }
.btn-next {
  width: 100%; background: var(--blue); color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 16px; letter-spacing: 1px; text-transform: uppercase;
  border: none; border-radius: 999px; padding: 18px;
  cursor: pointer;
  box-shadow: 0 4px 0 var(--blue-dark), 0 8px 24px rgba(74,144,226,0.25);
  transition: transform 0.12s;
}
.btn-next:active { transform: translateY(3px); }
 
.empty-state {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 12px; padding: 40px 24px; text-align: center;
}
.empty-state h2 { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 22px; color: #2d3748; }
.empty-state p  { font-size: 14px; color: #718096; }
.btn-primary {
  background: var(--blue); color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 16px; letter-spacing: 1px; text-transform: uppercase;
  border: none; border-radius: 999px; padding: 16px 32px;
  cursor: pointer; margin-top: 8px;
}
 
.q-fade-enter-active, .q-fade-leave-active { transition: all 0.25s ease; }
.q-fade-enter-from { opacity: 0; transform: translateX(30px); }
.q-fade-leave-to   { opacity: 0; transform: translateX(-30px); }
.slide-up-enter-active { transition: all 0.3s cubic-bezier(.4,0,.2,1); }
.slide-up-enter-from   { opacity: 0; transform: translateY(12px); }
</style>