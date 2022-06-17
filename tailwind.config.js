module.exports = {
    theme: {
        container: {
            padding: '2rem',
        },
        extend: {
            spacing:{
                '96': '34rem'
            }
        }
    },
    content: ['./src/**/*.{vue,js,ts}'],
    plugins: [require("@tailwindcss/typography"), require('daisyui')],
    daisyui: {
        styled: true,
        themes: true,
        base: true,
        utils: true,
        logs: true,
        rtl: false,
        prefix: "",
        darkTheme: "corporate",
    },
};
