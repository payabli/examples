import { defineConfig } from 'astro/config';
import react from '@astrojs/react';
import alpinejs from '@astrojs/alpinejs';
// https://astro.build/config
export default defineConfig({
  integrations: [react(), alpinejs()]
});
