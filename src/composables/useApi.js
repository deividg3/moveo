



const BASE = '/backend'

export function useApi() {

  async function register({ name, email, password, notifications }) {
    const res = await fetch(`${BASE}/register.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, email, password, notifications }),
    })
    let json;
    try {
      json = await res.json()
    } catch (e) {
      throw new Error('Error de conexión con el servidor backend. (¿Está LocalWP activo?)')
    }
    if (!res.ok) throw new Error(json.error || 'Error al registrar')
    return json
  }

  async function login({ email, password }) {
    const res = await fetch(`${BASE}/login.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    })
    let json;
    try {
      json = await res.json()
    } catch (e) {
      throw new Error('Error de conexión con el servidor backend. (¿Está LocalWP activo?)')
    }
    if (!res.ok) throw new Error(json.error || 'Error al iniciar sesión')
    return json
  }

  return { register, login }
}
