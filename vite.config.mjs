import { defineConfig } from 'vite';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import phpReloadPlugin from './vite-php-reload-plugin.js';

export default defineConfig({
  plugins: [phpReloadPlugin()],
  root: './',
  base: '/',
  build: {
    outDir: './public/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        app: './resources/js/app.js',
      },
    },
  },
  server: {
    origin: 'http://ratatata.test',
    hmr: {
      host: 'ratatata.test',
    },
    watch: {
      usePolling: true,
      include: ['**/*.php', '**/*.js', '**/*.css']
    },
  },
  css: {
    postcss: {
      plugins: [
        tailwindcss,
        autoprefixer,
      ],
    },
  },
});