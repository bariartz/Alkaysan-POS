<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-icon" sizes="118x118" href="{{"/images/icon-alkaysan.png?" . time() }}">
        <title inertia>{{ config('app.name', 'Alkaysan') }}</title>
        <link rel="icon" type="image/png" href="{{"/images/icon-alkaysan.png?" . time() }}" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="manifest" href="{{ "/manifest.json?" . time() }}" />
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
    </head>
    <body>
        @inertia

        <x-translations></x-translations>
        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(() => {
                    console.log('Service worker registered');
                });
            });
        }
        </script>
    </body>
</html>
