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
        <h1 class="admin-title">Usuarios</h1>
        <span class="user-count">{{ filtered.length }} de {{ users.length }}</span>
      </div>
 
      
      <div class="search-wrap">
        <input
          v-model="search"
          type="text"
          placeholder="Buscar por nombre o email…"
          class="search-input"
        />
      </div>
 
      
      <div class="users-table">
        <div class="table-head">
          <span>#</span>
          <span>Nombre</span>
          <span>Email</span>
          <span>Objetivos</span>
          <span>Nacimiento</span>
          <span>Racha</span>
          <span>Registro</span>
        </div>
        <div class="table-row" v-for="u in filtered" :key="u.id">
          <span class="user-id">{{ u.id }}</span>
          <span class="user-name">
            <span class="avatar-emoji">{{ avatarEmoji(u.avatar) }}</span>
            {{ u.name }}
          </span>
          <span>{{ u.email }}</span>
          <span>{{ u.goals || '—' }}</span>
          <span>{{ u.birthdate || '—' }}</span>
          <span class="streak-val"><i class="fas fa-fire"></i> {{ u.streak || 0 }}</span>
          <span>{{ formatDate(u.created_at) }}</span>
        </div>
        <div class="empty-row" v-if="!filtered.length">
          <p>No se encontraron usuarios.</p>
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
const AVATARS = ['🐧','🦊','🐸','🦋','🐼','🦁','🐙','🦄','🐯','🤖','👾','🦅']
 
export default {
  name: 'AdminUsers',
  data() {
    return { users: [], search: '' }
  },
  async created() {
    await this.loadUsers()
  },
  computed: {
    filtered() {
      if (!this.search.trim()) return this.users
      const q = this.search.toLowerCase()
      return this.users.filter(u =>
        u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
      )
    },
  },
  methods: {
    async loadUsers() {
      try {
        const token = sessionStorage.getItem('admin_token')
        const res  = await fetch('/backend/get_users.php', {
          headers: { 'Authorization': `Bearer ${token}` }
        })
        const data = await res.json()
        this.users = data.users || []
      } catch (e) { console.error(e) }
    },
    logout() {
      sessionStorage.removeItem('admin_token')
      this.$router.push('/admin/login')
    },
    formatDate(d) {
      if (!d) return '—'
      return new Date(d).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: '2-digit' })
    },
    avatarEmoji(idx) { return AVATARS[idx] || '🐧' },
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
  display: flex; align-items: center; gap: 8px; padding: 12px 14px; border-radius: 12px;
  color: #718096; font-weight: 700; font-size: 14px; text-decoration: none; transition: all 0.15s;
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
.admin-title { font-family: 'Nunito', sans-serif; font-weight: 900; font-size: 26px; color: #2d3748; }
.user-count { font-size: 13px; color: #718096; }
 
.search-wrap { margin-bottom: 16px; }
.search-input {
  width: 100%; max-width: 360px; padding: 12px 16px;
  border: 2px solid #e2ecf8; border-radius: 12px;
  font-family: 'Nunito Sans', sans-serif; font-size: 14px; color: #2d3748;
  outline: none; background: white;
  transition: border-color 0.2s;
}
.search-input:focus { border-color: #4a90e2; }
 
.users-table { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
.table-head, .table-row {
  display: grid;
  grid-template-columns: 0.5fr 2fr 3fr 2fr 1.5fr 1fr 1.5fr;
  padding: 12px 20px; gap: 10px; align-items: center;
}
.table-head {
  background: #f7f9fc; font-family: 'Nunito', sans-serif; font-weight: 800;
  font-size: 11px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;
}
.table-row { border-top: 1px solid #f0f4f8; font-size: 13px; color: #2d3748; transition: background 0.15s; }
.table-row:hover { background: #f7fbff; }
.user-id { color: #a0aec0; font-size: 12px; }
.user-name { display: flex; align-items: center; gap: 6px; font-weight: 700; }
.avatar-emoji { font-size: 16px; }
.streak-val { font-weight: 700; }
.empty-row { padding: 32px; text-align: center; font-size: 14px; color: #a0aec0; }
 
@media (max-width: 900px) {
  .table-head span:nth-child(5),
  .table-row  span:nth-child(5) { display: none; }
  .table-head, .table-row { grid-template-columns: 0.5fr 2fr 3fr 2fr 1fr 1.5fr; }
}
@media (max-width: 640px) {
  .sidebar { display: none; }
  .admin-main { padding: 20px 16px; }
}
</style>