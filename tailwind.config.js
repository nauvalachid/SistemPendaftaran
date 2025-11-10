import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primaryblue: '#001A6E', 
                hover: '#00124C',
                deskripsi: '#03346E',
                card : '#EFE4D2'
            }
        },
    },

    safelist: [
    // Kelas untuk Status 'Pending'
    'bg-yellow-100',
    'text-yellow-800',

    // Kelas untuk Status 'Diterima'
    'bg-green-100',
    'text-green-800',

    // Kelas untuk Status 'Ditolak'
    'bg-red-100',
    'text-red-800',
    
    // Kelas default jika status tidak ditemukan
    'bg-gray-100',
    'text-gray-800',
    ],

    plugins: [forms],
};
