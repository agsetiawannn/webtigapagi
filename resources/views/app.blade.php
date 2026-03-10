<!DOCTYPE html>
<html lang="id" style="background-color: #000;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tigapagi - Creative Agency</title>
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="antialiased bg-black" style="background-color: #000;">
    <div id="app"></div>
</body>
</html>
