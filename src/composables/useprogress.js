











 
const KEY = 'moveo_progress'
 
function today() {
  return new Date().toISOString().slice(0, 10)
}
 
function load() {
  try {
    return JSON.parse(localStorage.getItem(KEY)) || defaultState()
  } catch {
    return defaultState()
  }
}
 
function defaultState() {
  return {
    userId: null,
    streak: 0,
    lastTestDate: null,
    testsCompleted: 0,
    testHistory: [],
    questionStats: {},
  }
}
 
function save(state) {
  localStorage.setItem(KEY, JSON.stringify(state))
}
 
export function useProgress() {
 
  
  function saveTestResult({ topic, answers, questions }) {
    const state = load()
    const score = answers.filter(a => a.correct).length
 
    
    answers.forEach(a => {
      if (!state.questionStats[a.qId]) {
        state.questionStats[a.qId] = { seen: 0, correct: 0, wrong: 0 }
      }
      state.questionStats[a.qId].seen++
      if (a.correct) state.questionStats[a.qId].correct++
      else           state.questionStats[a.qId].wrong++
    })
 
    
    state.testHistory.push({
      date:    today(),
      topic,
      score,
      total:   answers.length,
      answers: answers.map(a => ({ qId: a.qId, correct: a.correct })),
    })
 
    state.testsCompleted++
 
    
    const t = today()
    if (state.lastTestDate === t) {
      
    } else if (isYesterday(state.lastTestDate)) {
      state.streak++
    } else if (state.lastTestDate !== t) {
      state.streak = 1
    }
    state.lastTestDate = t
 
    save(state)
    return { score, streak: state.streak }
  }
 
  
  function getWrongQuestions(allQuestions, limit = 10) {
    const state = load()
    
    const wrong = Object.entries(state.questionStats)
      .filter(([, s]) => s.wrong > 0)
      .map(([id, s]) => ({ id: Number(id), ratio: s.wrong / s.seen, ...s }))
      .sort((a, b) => b.ratio - a.ratio)
      .slice(0, limit)
      .map(w => allQuestions.find(q => q.id === w.id))
      .filter(Boolean)
 
    return wrong
  }
 
  
  function isReviewDue() {
    const state = load()
    const stats = state.questionStats
    const hasWrong = Object.values(stats).some(s => s.wrong > 0)
    return hasWrong && state.testsCompleted > 0 && state.testsCompleted % 5 === 0
  }
 
  
  function getLast10DaysStats() {
    const state = load()
    const days = []
    for (let i = 9; i >= 0; i--) {
      const d = new Date()
      d.setDate(d.getDate() - i)
      const dateStr = d.toISOString().slice(0, 10)
      const tests = state.testHistory.filter(t => t.date === dateStr)
      const correct = tests.reduce((s, t) => s + t.score, 0)
      const total   = tests.reduce((s, t) => s + t.total, 0)
      days.push({
        date: dateStr,
        label: d.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' }),
        tests: tests.length,
        correct,
        total,
        pct: total > 0 ? Math.round((correct / total) * 100) : null,
      })
    }
    return days
  }
 
  
  function getApprovalProbability() {
    const state = load()
    const cutoff = new Date()
    cutoff.setDate(cutoff.getDate() - 10)
    const cutoffStr = cutoff.toISOString().slice(0, 10)
 
    const recent = state.testHistory.filter(t => t.date >= cutoffStr)
    if (recent.length < 50) return null   
 
    const totalQ  = recent.reduce((s, t) => s + t.total, 0)
    const totalOK = recent.reduce((s, t) => s + t.score, 0)
    const pct     = Math.round((totalOK / totalQ) * 100)
 
    
    
    const p = totalOK / totalQ
    let prob = 0
    for (let k = 0; k <= 3; k++) {
      prob += binomialPMF(30, k, 1 - p)
    }
    prob = Math.round(prob * 100)
 
    return { pct, prob: Math.min(prob, 99) }
  }
 
  
  function getTopicProgress(allQuestions) {
    const state = load()
    const topics = {}
 
    allQuestions.forEach(q => {
      if (!topics[q.topic]) {
        topics[q.topic] = {
          label: q.topic_label,
          total: 0, seen: 0, correct: 0, wrong: 0,
        }
      }
      topics[q.topic].total++
      const s = state.questionStats[q.id]
      if (s) {
        topics[q.topic].seen    += s.seen > 0 ? 1 : 0
        topics[q.topic].correct += s.correct
        topics[q.topic].wrong   += s.wrong
      }
    })
 
    return Object.entries(topics).map(([id, data]) => ({
      id, ...data,
      pct: data.seen > 0
        ? Math.round((data.correct / (data.correct + data.wrong)) * 100)
        : null,
    }))
  }
 
  function getState() { return load() }
 
  return {
    saveTestResult,
    getWrongQuestions,
    isReviewDue,
    getLast10DaysStats,
    getApprovalProbability,
    getTopicProgress,
    getState,
  }
}
 

function isYesterday(dateStr) {
  if (!dateStr) return false
  const yesterday = new Date()
  yesterday.setDate(yesterday.getDate() - 1)
  return dateStr === yesterday.toISOString().slice(0, 10)
}
 
function binomialPMF(n, k, p) {
  return binomialCoeff(n, k) * Math.pow(p, k) * Math.pow(1 - p, n - k)
}
 
function binomialCoeff(n, k) {
  if (k === 0 || k === n) return 1
  let result = 1
  for (let i = 0; i < k; i++) {
    result = result * (n - i) / (i + 1)
  }
  return result
}
 