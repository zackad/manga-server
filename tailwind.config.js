module.exports = {
  purge: false,
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true,
  },
  theme: {
    extend: {
      spacing: {
        80: '20rem',
      },
      maxHeight: (theme) => theme('spacing'),
      minHeight: (theme) => theme('spacing'),
      height: (theme) => theme('spacing'),
      minWidth: (theme) => theme('spacing'),
    },
  },
  variants: {},
  plugins: [],
}
