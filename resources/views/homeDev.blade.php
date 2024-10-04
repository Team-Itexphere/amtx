@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <!-- Bootstrap CSS via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <style>
        /* Custom styles for the page */
        .textbody {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }

        .coming-soon-text {
            font-size: 36px;
            text-align: center;
        }
    </style>
    <!-- Logo -->
    <div class="textbody">
    <img src="{{ asset('img') }}/{{ config('app.logo', 'Laravel') }}" alt="Logo" class="logo">

    <!-- Coming Soon Text -->
    <div class="coming-soon-text">
        Coming Soon
    </div>
    </div>
<footer><h6 class="text-center mt-4">Product by <a href="https://gasguru.co" target="_blank" style="color: #2a3539;">GasGuru</a><h6></footer>
@endsection
