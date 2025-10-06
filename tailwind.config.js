/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Instrument Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        // Background colors
        'revit-bg': 'rgb(255, 252, 250)', // #fffcfa
        'revit-bg-alt': 'rgb(229, 226, 223)', // #e5e2df

        // Text colors
        'revit-text': 'rgb(51, 50, 49)', // #333231
        'revit-heading': 'rgb(26, 26, 25)', // #1a1a19
        'revit-link': 'rgb(0, 0, 0)', // #000000

        // Button colors
        'revit-btn-bg': 'rgb(51, 50, 49)', // #333231
        'revit-btn-hover': 'rgb(26, 26, 25)', // #1a1a19
        'revit-btn-text': 'rgb(255, 255, 255)', // #ffffff

        // Navigation colors
        'revit-nav-bg': 'rgb(255, 255, 255)', // #ffffff
        'revit-nav-text': 'rgb(51, 50, 49)', // #333231
        'revit-nav-hover': 'rgb(0, 0, 0)', // #000000

        // Accent colors
        'revit-accent': 'rgb(204, 75, 10)', // #cc4b0a - orange accent

        // Override default colors to match revitsport
        'gray': {
          50: 'rgb(255, 252, 250)', // revit-bg
          100: 'rgb(229, 226, 223)', // revit-bg-alt
          900: 'rgb(51, 50, 49)', // revit-text
        },
        'black': 'rgb(26, 26, 25)', // revit-heading
        'white': 'rgb(255, 255, 255)',
      },
    },
  },
  plugins: [],
}