/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./resources/views/**/*.blade.php'],
    theme: {
        extend: {
            colors: {
                latar: '#FAF7F0',
                permukaan: '#FFFFFF',
                garis: '#E7E0D4',
                tinta: '#2A2620',
                lembut: '#6E675C',
                aksi: '#1F4E5A',
                sejuk: '#CFEBFF',
                pasir: '#FFDDB0',
                peach: '#FFBE91',
                aman: '#2F7A4F',
                waspada: '#B07A1E',
                kritis: '#B3352B',
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
            },
            borderRadius: {
                sm: '4px',
                DEFAULT: '6px',
                md: '6px',
                lg: '8px',
                xl: '8px',
                '2xl': '8px',
                '3xl': '8px',
            },
        },
    },
    plugins: [],
};
