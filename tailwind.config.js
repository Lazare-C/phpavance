module.exports = {
  purge: [
    './templates/**/*.html.twig',
    './assets/**/*.{vue,jsx}',
  ],
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
