/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'moveo-blue': '#1E63A7',
        'moveo-dark': '#0A0A0A',
      },
    },
  },
  plugins: [],
}