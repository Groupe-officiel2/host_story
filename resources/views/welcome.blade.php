<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Host Story</title>
        
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Host Story</h1>
            <p class="text-gray-600">Laravel {{ app()->version() }}</p>
        </div>
    </body>
</html>
