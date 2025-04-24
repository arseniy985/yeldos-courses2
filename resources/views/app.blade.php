<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Yeldos Courses - образовательная платформа для онлайн-обучения">
        <meta name="keywords" content="курсы, образование, обучение, онлайн, yeldos courses">
        <meta name="author" content="Yeldos Courses">
        <meta name="theme-color" content="#6366F1">
        <meta name="color-scheme" content="light">

        <title inertia>{{ config('app.name', 'Yeldos Courses') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5' stroke='%236366F1' stroke-width='2' fill='none'/%3E%3C/svg%3E">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Дополнительные стили для принудительного применения светлой темы -->
        <style>
            :root {
                color-scheme: light;
            }
            html, body {
                background-color: #FAFAFA !important;
                color: #312E81 !important;
            }
            @media (prefers-color-scheme: dark) {
                html, body {
                    background-color: #FAFAFA !important;
                    color: #312E81 !important;
                }
            }
        </style>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
