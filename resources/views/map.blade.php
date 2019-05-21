<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="{{url('/css/app.css')}}">

        <script>
            window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' =>  auth()->user(),
        ]) !!};
        </script>

    </head>
    <body>
        <div id="app">

                        <example-component></example-component>

        </div>


        @push('scripts')
            <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
        @endpush

        @stack('scripts')
    </body>
</html>
