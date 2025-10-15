import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.jsx', 'resources/css/app.css'],
      refresh: true,
    }),
    react(),
  ],
  server: {
    port: Number(process.env.VITE_PORT || 5174),
    strictPort: true,
    host: true,
  },
})
