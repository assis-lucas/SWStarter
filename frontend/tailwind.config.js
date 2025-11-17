/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
      },
      colors: {
        primary: {
            50: '#effef6',
            100: '#d9ffec',
            200: '#b6fcd9',
            300: '#7df8bc',
            400: '#3deb98',
            500: '#14d378',
            600: '#0ab463',
            700: '#0c894e',
            800: '#0f6c40',
            900: '#0f5837',
            950: '#01321d',
        },
      }
    },
  },
  plugins: [],
}
