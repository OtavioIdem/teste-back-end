<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'Library Admin') }}</title>
    @vite(['resources/js/app.jsx','resources/css/app.css'])
  </head>
  <body class="bg-gray-50">
    <div id="app"></div>
  </body>
</html>
