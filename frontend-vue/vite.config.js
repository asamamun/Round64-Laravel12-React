import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5173, // Using a different port than the React frontend
    strictPort: false, // Allow fallback to another port if 5173 is busy
    hmr: {
      overlay: false // Disable HMR overlay to prevent infinite rendering issues
    },
    watch: {
      usePolling: true, // Use polling instead of file system events
      interval: 1000 // Poll every second
    }
  }
})