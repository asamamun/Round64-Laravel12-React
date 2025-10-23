import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173, // Using a different port than the React frontend
    strictPort: false // Allow fallback to another port if 5175 is busy
  }
})