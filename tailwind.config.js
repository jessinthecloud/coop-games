module.exports = {
  // so Tailwind can tree-shake unused styles in production builds
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    maxWidth: {
      '1/4': '25%',
      '1/2': '50%',
      '3/4': '75%',
      'xxs': '18rem',
    },
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
