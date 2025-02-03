/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
	theme: {
		extend: {
      animation: {
        "bounce-once": "bounce-once 0.5s ease-in-out",
      },
    },
  },
  plugins: [require("@tailwindcss/typography")],
}
