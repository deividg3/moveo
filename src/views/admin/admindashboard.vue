<template>
  <div class="admin">
    
    <nav class="sidebar">
      <div class="sidebar-logo">
        <img src="@/assets/logoblue.png" alt="Logo" class="logo-img" />
        <span class="admin-badge">ADMIN</span>
      </div>
      <div class="sidebar-menu">
        <router-link class="menu-item" to="/admin/dashboard" active-class="active">
          <i class="fas fa-chart-bar"></i> Dashboard
        </router-link>
        <router-link class="menu-item" to="/admin/users" active-class="active">
          <i class="fas fa-users"></i> Usuarios
        </router-link>
        <router-link class="menu-item" to="/admin/questions" active-class="active">
          <i class="fas fa-question"></i> Preguntas
        </router-link>
      </div>
      <button class="logout-btn" @click="logout">⬅ Salir</button>
    </nav>
 
    
    <div class="admin-main">
      <div class="admin-header">
        <h1 class="admin-title">Dashboard</h1>
        <span class="admin-date">{{ todayStr }}</span>
      </div>
 
      
      <div class="kpi-grid" v-if="stats">
        <div class="kpi-card">
          <span class="kpi-icon"><i class="fas fa-users"></i></span>
          <span class="kpi-value">{{ stats.total_users }}</span>
          <span class="kpi-label">Usuarios totales</span>
        </div>
        <div class="kpi-card">
          <span class="kpi-icon">🆕</span>
          <span class="kpi-value">{{ stats.new_today }}</span>
          <span class="kpi-label">Registros hoy</span>
        </div>
        <div class="kpi-card">
          <span class="kpi-icon"><i class="fas fa-file-alt"></i></span>
          <span class="kpi-value">{{ stats.total_questions }}</span>
          <span class="kpi-label">Preguntas</span>
        </div>
        <div class="kpi-card">
          <span class="kpi-icon"><i class="fas fa-fire"></i></span>
          <span class="kpi-value">{{ stats.avg_streak }}</span>
          <span class="kpi-label">Racha media</span>
        </div>
      </div>
 
      
      <div class="section-title">Registros recientes</div>
      <div class="recent-table" v-if="recentUsers.length">
        <div class="table-head">
          <span>Nombre</span><span>Email</span><span>Objetivos</span><span>Fecha</span>
        </div>
        <div class="table-row" v-for="u in recentUsers" :key="u.id">
          <span>{{ u.name }}</span>
          <span>{{ u.email }}</span>
          <span>{{ u.goals || '—' }}</span>
          <span>{{ formatDate(u.created_at) }}</span>
        </div>
      </div>
      <p class="empty-note" v-else>Cargando datos...</p>
    </div>
  </div>
</template>
 
<script>
export default {
  name: 'admindashboard',
  data() {
    return {
      stats: null,
      recentUsers: [],
      todayStr: new Date().toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }),
    }
  },
  async created() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      try {
        const token = sessionStorage.getItem('admin_token')
        const res = await fetch('/backend/get_stats.php', {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        const data = await res.json()
        this.stats       = data.stats
        this.recentUsers = data.recent_users || []
      } catch (e) {
        console.error(e)
      }
    },
    logout() {
      sessionStorage.removeItem('admin_token')
      this.$router.push('/admin/login')
    },
    formatDate(d) {
      if (!d) return '—'
      return new Date(d).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' })
    },
  },
}
</script>
 
<style scoped>
.admin {
  min-height: 100dvh; display: flex;
  background: #f7f9fc; font-family: 'Nunito Sans', sans-serif;
}
 

.sidebar {
  width: 220px; background: #1a1f2e; flex-shrink: 0;
  display: flex; flex-direction: column; padding: 24px 16px;
  position: sticky; top: 0; height: 100dvh;
}
.sidebar-logo {
  display: flex; align-items: center; gap: 8px; margin-bottom: 32px;
}
.logo-img { height: 28px; filter: brightness(0) invert(1); }
.admin-badge {
  background: #4a90e2; color: white;
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 10px; letter-spacing: 1.5px;
  padding: 2px 7px; border-radius: 5px;
}
.sidebar-menu { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.menu-item {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 14px; border-radius: 12px;
  color: #718096; font-weight: 700; font-size: 14px;
  text-decoration: none;
  transition: all 0.15s;
}
.menu-item:hover { background: rgba(74,144,226,0.1); color: #4a90e2; }
.menu-item.active { background: rgba(74,144,226,0.15); color: #4a90e2; }
.logout-btn {
  background: none; border: none; color: #718096;
  font-family: 'Nunito', sans-serif; font-weight: 700; font-size: 13px;
  cursor: pointer; padding: 12px 14px; text-align: left;
  border-radius: 12px; transition: all 0.15s;
}
.logout-btn:hover { color: #ff6b6b; background: rgba(255,107,107,0.08); }
 

.admin-main { flex: 1; padding: 32px; overflow-y: auto; }
.admin-header {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 28px;
}
.admin-title {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 26px; color: #2d3748;
}
.admin-date { font-size: 13px; color: #718096; }
 

.kpi-grid {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
  margin-bottom: 32px;
}
.kpi-card {
  background: white; border-radius: 16px; padding: 20px;
  display: flex; flex-direction: column; align-items: center; gap: 6px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.kpi-icon  { font-size: 28px; }
.kpi-value { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 28px; color: #2d3748; }
.kpi-label { font-size: 12px; color: #718096; text-align: center; }
 

.section-title {
  font-family: 'Nunito', sans-serif; font-weight: 900;
  font-size: 15px; color: #2d3748; margin-bottom: 12px;
}
.recent-table { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
.table-head, .table-row {
  display: grid; grid-template-columns: 2fr 3fr 2fr 1.5fr;
  padding: 12px 20px; gap: 12px;
}
.table-head {
  background: #f7f9fc;
  font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;
}
.table-row {
  border-top: 1px solid #f0f4f8;
  font-size: 13px; color: #2d3748;
  transition: background 0.15s;
}
.table-row:hover { background: #f7fbff; }
 
.empty-note { font-size: 13px; color: #a0aec0; padding: 20px 0; }
 

@media (max-width: 640px) {
  .sidebar { display: none; }
  .admin-main { padding: 20px 16px; }
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .table-head span:nth-child(3),
  .table-row  span:nth-child(3) { display: none; }
  .table-head, .table-row { grid-template-columns: 2fr 3fr 1.5fr; }
}
</style>