<template>
  <div class="session">
    
    <div class="session-header">
      <button class="exit-btn" @click="confirmExit"><i class="fas fa-times"></i></button>
      <div class="progress-wrap">
        <div class="progress-track">
          <div class="progress-fill" :style="{ width: (current / total * 100) + '%' }"></div>
          <div class="progress-car" :style="{ left: (current / total * 100) + '%' }" v-show="(current / total * 100) > 10">
            <span class="car-indicator"><i class="fas fa-car-side"></i></span>
          </div>
        </div>
        <span class="progress-label">{{ current }}/{{ total }}</span>
      </div>
      <div class="timer-badge" :class="{ urgent: timeLeft <= 10 }">
        ⏱ {{ timeLeft }}s
      </div>
    </div>
 
    
    <transition name="q-fade" mode="out-in">
      <div class="question-wrap" :key="current">
        <div class="topic-chip">{{ currentQ.topic_label }}</div>
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
      <button
        class="btn-next"
        v-if="answered"
        @click="next"
      >
        {{ isLast ? 'Ver resultados' : 'Siguiente →' }}
      </button>
    </div>
  </div>
</template>
 
<script>
import { useProgress } from '@/composables/useprogress.js'
import { useSounds } from '@/composables/usesounds.js'
import allQuestions from '@/data/questions.json'
 
const TIMER = 30
 
export default {
  name: 'testsession',
  setup() {
    return { progress: useProgress(), sounds: useSounds() }
  },
  data() {
    return {
      questions:  [],
      current:    0,
      selected:   null,
      answered:   false,
      answers:    [],
      letters:    ['A', 'B', 'C'],
      timeLeft:   TIMER,
      timer:      null,
      topic:      null,
      mode:       'random',
    }
  },
  computed: {
    total()    { return this.questions.length },
    currentQ() { return this.questions[this.current] || {} },
    isLast()   { return this.current === this.total - 1 },
  },
  created() {
    this.mode  = this.$route.query.mode  || 'random'
    this.topic = this.$route.query.topic || null
    this.loadQuestions()
    this.startTimer()
  },
  beforeUnmount() {
    clearInterval(this.timer)
  },
  methods: {
    loadQuestions() {
      let pool = [...allQuestions]
      if (this.mode === 'topic' && this.topic) {
        pool = pool.filter(q => q.topic === this.topic)
      }
      
      pool = pool.sort(() => Math.random() - 0.5).slice(0, 10)
      this.questions = pool
    },
 
    startTimer() {
      clearInterval(this.timer)
      this.timeLeft = TIMER
      this.timer = setInterval(() => {
        if (this.timeLeft > 0) {
          this.timeLeft--
        } else {
          
          this.answer(-1)
        }
      }, 1000)
    },
 
    answer(idx) {
      if (this.answered) return
      clearInterval(this.timer)
      this.selected = idx
      this.answered = true
      const correct = idx === this.currentQ.correct
      this.answers.push({ qId: this.currentQ.id, correct, selected: idx })
      if (correct) this.sounds.playCorrect()
      else         this.sounds.playWrong()
    },
 
    next() {
      if (this.isLast) {
        this.finish()
        return
      }
      this.current++
      this.selected = null
      this.answered = false
      this.startTimer()
    },
 
    finish() {
      const { score, streak } = this.progress.saveTestResult({
        topic:   this.mode === 'topic' ? this.topic : 'random',
        answers: this.answers,
        questions: this.questions,
      })
      this.$router.push({
        path: '/test/results',
        query: {
          score,
          total:   this.total,
          streak,
          topic:   this.topic || 'random',
          answers: JSON.stringify(this.answers),
          qids:    JSON.stringify(this.questions.map(q => q.id)),
        },
      })
    },
 
    confirmExit() {
      if (confirm('¿Salir del test? Se perderá el progreso.')) {
        clearInterval(this.timer)
        this.$router.push('/test')
      }
    },
 
    optionClass(i) {
      if (!this.answered) return ''
      if (i === this.currentQ.correct)                     return 'correct'
      if (i === this.selected && i !== this.currentQ.correct) return 'wrong'
      return 'dimmed'
    },
  },
}
</script>
 
<style scoped>
.session {
  min-height: 100dvh;
  display: flex; flex-direction: column;
  background: #f0f7ff;
}
 

.session-header {
  display: flex; align-items: center; gap: 12px;
  padding: 16px 20px;
  background: white;
  box-shadow: 0 2px 10px rgba(74,144,226,0.08);
}
.exit-btn {
  width: 36px; height: 36px; border-radius: 50%;
  border: none; background: #f0f7ff;
  color: #718096; font-size: 14px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.progress-wrap {
  flex: 1; display: flex; align-items: center; gap: 8px;
}
.progress-track {
  flex: 1; height: 8px; background: #e2ecf8;
  border-radius: 4px; overflow: visible; position: relative;
}
.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--blue), var(--green));
  border-radius: 4px;
  transition: width 0.4s cubic-bezier(.4,0,.2,1);
}
.progress-label { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px; color: #718096; }

.progress-car {
  position: absolute;
  top: -24px; 
  transform: translateX(-50%);
  transition: left 0.4s cubic-bezier(.4,0,.2,1);
  z-index: 2;
  color: var(--blue-dark);
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
}
.car-indicator {
  font-size: 24px; 
  display: block;
  animation: test-car-bounce 0.8s ease-in-out infinite alternate;
}
@keyframes test-car-bounce {
  0% { transform: translateY(0); }
  100% { transform: translateY(-1px); }
}
.timer-badge {
  background: #f0f7ff; border: 2px solid #c8ddf7;
  border-radius: 999px; padding: 4px 12px;
  font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 13px;
  color: var(--blue); flex-shrink: 0;
  transition: all 0.3s;
}
.timer-badge.urgent { background: #fff0f0; border-color: #ff6b6b; color: #ff6b6b; }
 

.question-wrap {
  flex: 1; padding: 24px 20px 16px;
  display: flex; flex-direction: column; gap: 16px;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.topic-chip {
  background: var(--blue-light); color: var(--blue-dark);
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 12px; padding: 4px 12px; border-radius: 999px;
  width: fit-content; text-transform: uppercase; letter-spacing: 0.5px;
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
  transition: all 0.18s cubic-bezier(.4,0,.2,1);
  box-shadow: 0 2px 8px rgba(74,144,226,0.06);
}
.option-btn:not(:disabled):hover {
  border-color: var(--blue);
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(74,144,226,0.14);
}
.option-btn:disabled { cursor: default; }
 
.option-letter {
  width: 32px; height: 32px; border-radius: 50%;
  background: #f0f7ff; color: var(--blue);
  font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 14px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.option-text {
  flex: 1; font-family: 'Nunito Sans', sans-serif;
  font-weight: 700; font-size: 15px; color: #2d3748;
}
.option-icon { font-size: 18px; flex-shrink: 0; }
 
.option-btn.correct {
  border-color: var(--green);
  background: #f0fff0;
}
.option-btn.correct .option-letter { background: var(--green); color: white; }
.option-btn.correct .option-icon   { color: var(--green); }
 
.option-btn.wrong {
  border-color: #ff6b6b;
  background: #fff0f0;
}
.option-btn.wrong .option-letter { background: #ff6b6b; color: white; }
.option-btn.wrong .option-icon   { color: #ff6b6b; }
 
.option-btn.dimmed { opacity: 0.4; }
 

.explanation {
  display: flex; gap: 12px; align-items: flex-start;
  background: white; border-radius: 16px;
  padding: 14px; border-left: 4px solid var(--blue);
}
.expl-icon { font-size: 20px; flex-shrink: 0; }
.explanation p { font-size: 14px; color: #4a5568; line-height: 1.5; }
 

.session-footer {
  padding: 16px 20px 32px;
  max-width: 480px; margin: 0 auto; width: 100%;
}
.btn-next {
  width: 100%;
  background: var(--blue); color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 16px; letter-spacing: 1px; text-transform: uppercase;
  border: none; border-radius: 999px; padding: 18px;
  cursor: pointer;
  box-shadow: 0 4px 0 var(--blue-dark), 0 8px 24px rgba(74,144,226,0.25);
  transition: transform 0.12s;
  animation: fadeUp 0.25s ease both;
}
.btn-next:active { transform: translateY(3px); box-shadow: 0 1px 0 var(--blue-dark); }
 

.q-fade-enter-active, .q-fade-leave-active { transition: all 0.25s ease; }
.q-fade-enter-from { opacity: 0; transform: translateX(30px); }
.q-fade-leave-to   { opacity: 0; transform: translateX(-30px); }
 
.slide-up-enter-active { transition: all 0.3s cubic-bezier(.4,0,.2,1); }
.slide-up-enter-from   { opacity: 0; transform: translateY(12px); }
 
@keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
</style>