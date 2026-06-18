{{-- Tailwind via CDN + police Outfit + thème (palette de la charte) + texture grain.
     Bleu nuit = uniquement pour le texte. Aucun build npm requis. --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Outfit', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                },
                colors: {
                    // Fond blanc + neutres clairs
                    sable: { DEFAULT: '#FFFFFF', 50: '#F8FAFB', 100: '#FFFFFF', 200: '#E7EBEE', 300: '#D5DBE0' },
                    // 30% - Bleu lagon (secondaire : navigation, surfaces, confiance)
                    lagon: { DEFAULT: '#1E8A9A', 50: '#ECF7F8', 100: '#CFEAEC', 500: '#1E8A9A', 600: '#176E7C', 700: '#125863' },
                    // 10% - Terracotta (accent : boutons d'action, favoris, notifications)
                    terracotta: { DEFAULT: '#C75B39', 50: '#FBEDE6', 500: '#C75B39', 600: '#AE4B2D', 700: '#8E3C24' },
                    // 10% - Bleu nuit profond (UNIQUEMENT pour le texte)
                    nuit: { DEFAULT: '#13283B', 600: '#1B3a52', 700: '#0F2030' },
                },
                boxShadow: {
                    soft: '0 1px 2px rgba(19,40,59,.04), 0 8px 24px -12px rgba(19,40,59,.18)',
                    lift: '0 8px 30px -10px rgba(19,40,59,.25)',
                },
                borderRadius: {
                    '2xl': '1rem',
                    '3xl': '1.5rem',
                },
            },
        },
    };
</script>
<style>
    body {
        background-color: #FFFFFF;;
        font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    /* Texture grain subtile sur le fond */
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        opacity: 0.05;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
    }
</style>
