import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'
 
export default defineConfig({
  plugins: [vue()],
 
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
 
  // base: '/'  →  correcto para Apache en raíz del dominio
  // Si despliegas en un subdirectorio (ej: /app/) cámbialo a '/app/'
  base: '/',
 
  build: {
    // Directorio de salida — súbelo todo a tu servidor Apache
    outDir: 'dist',
 
    // Chunk más pequeños → mejor tiempo de carga en móvil
    chunkSizeWarningLimit: 500,
 
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes('node_modules/vue') || id.includes('node_modules/vue-router')) {
            return 'vue-core'
          }
        },
      },
    },
  },
 
  server: {
    proxy: {
      '/backend': {
        target: 'http://moveo.local',
        changeOrigin: true,
      },
    },
  },
})