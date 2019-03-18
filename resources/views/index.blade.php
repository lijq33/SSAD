<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name = "csrf-token" content = "{{ csrf_token() }}">

        <title> CMS Website </title>
        @include('templates.layouts.css')
    </head>

    <body>
        <div id="fb-root"></div>
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>
        <div id="app">
            
            <main-app>
                {{-- datakey = {{config('services.google.recaptcha.site_key')}}> --}}
            </main-app>

        </div>
        @include('templates.layouts.js')
    </body>
</html>

