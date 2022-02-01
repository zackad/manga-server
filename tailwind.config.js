module.exports = {
  content: ['./assets/**/*.{js,jsx,ts,tsx}', './templates/**/*.twig'],
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [require('@tailwindcss/line-clamp')],
}
