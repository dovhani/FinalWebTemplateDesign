// @ts-check
import { defineConfig } from 'astro/config';

import tailwindcss from '@tailwindcss/vite';

// https://astro.build/config
export default defineConfig({
  build: {
    inlineStylesheets: 'always',
  },
  image: {
    domains: ['res.cloudinary.com'],
  },
  vite: {
    plugins: [tailwindcss()],
  },
});