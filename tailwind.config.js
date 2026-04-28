import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],

  safelist: [
    'bg-blue-500',
    'bg-green-500',
    'bg-purple-500',
    'bg-red-500',
    'bg-yellow-500',

    'bg-blue-100',
    'bg-green-100',
    'bg-purple-100',

    'text-blue-800',
    'text-green-800',
    'text-purple-800',
  ],

  theme: {
    extend: {},
  },

  plugins: [forms],
}