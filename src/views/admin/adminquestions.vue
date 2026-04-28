<template>
  <div class="admin">
    <nav class="sidebar">
      <div class="sidebar-logo">
        <img src="@/assets/logoblue.png" alt="Logo" class="logo-img" />
        <span class="admin-badge">ADMIN</span>
      </div>
      <div class="sidebar-menu">
        <router-link class="menu-item" to="/admin/dashboard" active-class="active"><i class="fas fa-chart-bar"></i> Dashboard</router-link>
        <router-link class="menu-item" to="/admin/users"     active-class="active"><i class="fas fa-users"></i> Usuarios</router-link>
        <router-link class="menu-item" to="/admin/questions" active-class="active"><i class="fas fa-question"></i> Preguntas</router-link>
      </div>
      <button class="logout-btn" @click="logout">⬅ Salir</button>
    </nav>
 
    <div class="admin-main">
      <div class="admin-header">
        <h1 class="admin-title">Preguntas</h1>
        <span class="q-count">{{ filtered.length }} de {{ questions.length }}</span>
      </div>
 
      
      <div class="filters">
        <input v-model="search" type="text" placeholder="Buscar pregunta…" class="search-input" />
        <select v-model="topicFilter" class="topic-select">
          <option value="">Todos los temas</option>
          <option v-for="t in topics" :key="t.id" :value="t.id">{{ t.label }}</option>
        </select>
      </div>
 
      
      <div class="q-list">
        <div class="q-card" v-for="q in filtered" :key="q.id">
          <div class="q-meta">
            <span class="q-id">#{{ q.id }}</span>
            <span class="q-topic">{{ q.topic_label }}</span>
          </div>
          <p class="q-text">{{ q.question }}</p>
          <div class="q-options">
            <span
              v-for="(opt, i) in q.options"
              :key="i"
              class="q-opt"
              :class="{ correct: i === q.correct }"
            >
              {{ ['A','B','C'][i] }}. {{ opt }}
              <span v-if="i === q.correct" class="correct-tag"><i class="fas fa-check"></i> Correcta</span>
            </span>
          </div>
          <p class="q-expl"><i class="fas fa-lightbulb"></i> {{ q.explanation }}</p>
        </div>
      </div>
 
      <p class="empty-note" v-if="!filtered.length">No hay preguntas con ese filtro.</p>
    </div>
  </div>
</template>
 
<script>
import allQuestions from '@/data/questions.json'
 
export default {
  name: 'adminquestions',
  data() {
    const topicMap = {}
    allQuestions.forEach(q => { topicMap[q.topic] = q.topic_label })
    const topics = Object.entries(topicMap).map(([id, label]) => ({ id, label }))
    return { questions: allQuestions, topics, search: '', topicFilter: '' }
  },
  computed: {
    filtered() {
      return this.questions.filter(q => {
        const matchSearch = !this.search || q.question.toLowerCase().includes(this.search.toLowerCase())
        const matchTopic  = !this.topicFilter || q.topic === this.topicFilter
        return matchSearch && matchTopic
      })
    },
  },
  methods: {
    logout() {
      sessionStorage.removeItem('admin_token')
      this.$router.push('/admin/login')
    },
  },
}
</script>
 
<style scoped>
.admin { min-height: 100dvh; display: flex; background: #f7f9fc; }
.sidebar {
  width: 220px; background: #1a1f2e; flex-shrink: 0;
  display: flex; flex-direction: column; padding: 24px 16px;
  position: sticky; top: 0; height: 100dvh;
}
.sidebar-logo { display: flex; align-items: center; gap: 8px; margin-bottom: 32px; }
.logo-img { height: 28px; filter: brightness(0) invert(1); }
.admin-badge {
  background: #4a90e2; color: white; font-family: 'Nunito', sans-serif;
  font-weight: 900; font-size: 10px; letter-spacing: 1.5px; padding: 2px 7px; border-radius: 5px;
}
.sidebar-menu { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.menu-item {
  display: flex; align-items: center; gap: 8px; padding: 12px 14px;
  border-radius: 12px; color: #718096; font-weight: 700; font-size: 14px;
  text-decoration: none; transition: all 0.15s;
}
.menu-item:hover  { background: rgba(74,144,226,0.1); color: #4a90e2; }
.menu-item.active { background: rgba(74,144,226,0.15); color: #4a90e2; }
.logout-btn {
  background: none; border: none; color: #718096; font-family: 'Nunito', sans-serif;
  font-weight: 700; font-size: 13px; cursor: pointer; padding: 12px 14px;
  text-align: left; border-radius: 12px; transition: all 0.15s;
}
.logout-btn:hover { color: #ff6b6b; background: rgba(255,107,107,0.08); }
 
.admin-main { flex: 1; padding: 32px; overflow-y: auto; }
.admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.admin-title  { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 26px; color: #2d3748; }
.q-count { font-size: 13px; color: #718096; }
 
.filters { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.search-input {
  flex: 1; min-width: 200px; padding: 11px 16px;
  border: 2px solid #e2ecf8; border-radius: 12px;
  font-family: 'Nunito Sans', sans-serif; font-size: 14px; color: #2d3748;
  outline: none; background: white;
}
.search-input:focus { border-color: #4a90e2; }
.topic-select {
  padding: 11px 16px; border: 2px solid #e2ecf8; border-radius: 12px;
  font-family: 'Nunito Sans', sans-serif; font-size: 14px; color: #2d3748;
  outline: none; background: white; cursor: pointer; -webkit-appearance: none;
}
.topic-select:focus { border-color: #4a90e2; }
 
.q-list { display: flex; flex-direction: column; gap: 14px; }
.q-card {
  background: white; border-radius: 16px; padding: 18px 20px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.q-meta { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; }
.q-id   { font-size: 12px; color: #a0aec0; font-weight: 700; }
.q-topic {
  background: #e8f4ff; color: #4a90e2; border-radius: 999px;
  padding: 2px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
}
.q-text { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 15px; color: #2d3748; margin-bottom: 12px; }
.q-options { display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px; }
.q-opt {
  font-size: 13px; color: #4a5568; padding: 8px 12px;
  background: #f7f9fc; border-radius: 8px;
  display: flex; align-items: center; gap: 8px;
}
.q-opt.correct { background: #f0fff0; color: #2d7a2d; font-weight: 700; }
.correct-tag { font-size: 11px; color: #58cc02; font-weight: 800; margin-left: auto; }
.q-expl { font-size: 12px; color: #718096; border-left: 3px solid #c8ddf7; padding-left: 10px; line-height: 1.5; }
 
.empty-note { font-size: 13px; color: #a0aec0; padding: 24px 0; text-align: center; }
@media (max-width: 640px) { .sidebar { display: none; } .admin-main { padding: 20px 16px; } }
</style>