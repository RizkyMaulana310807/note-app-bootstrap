/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php', // Menyesuaikan dengan struktur proyekmu
    './assets/**/*.js', // Jika ada file JS yang menggunakan Tailwind
    './assets/css/**/*.css', // Jika ada file CSS lain yang menggunakan Tailwind
  ],

  theme: {
    extend: {},
  },
  plugins: [],
}

