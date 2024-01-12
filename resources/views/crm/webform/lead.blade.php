<!doctype html>
<html lang="<?php  app()->getLocale(); ?>">
<head>
    <link rel="shortcut icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app_trans('default.webform') }}</title>

    @include('layouts.includes.header')
</head>
<body>
    <main id="app">
        <lead-web-form></lead-web-form>
    </main>

    @include('layouts.includes.footer')

    <script>
        window.settings = <?php echo json_encode($settings ?? '') ?>
    </script>
    <script>
        if(!window.localStorage.getItem('app-language')){
            // initital language added
            window.localStorage.setItem('app-language',"en");
        };
        window.localStorage.setItem('base_url', '<?php echo request()->root(); ?>');
    </script>
    <script>
        window.localStorage.setItem('app-languages',
            JSON.stringify(
                @php
                    echo
                        json_encode(
                            include resource_path()
                                . DIRECTORY_SEPARATOR . 'lang'
                                . DIRECTORY_SEPARATOR .
                                (
                                    Cookie::has('user_lang') ? Cookie::get('user_lang') : ($settings['language'] ?? 'en')
                                )
                                . DIRECTORY_SEPARATOR . 'default.php'
                        )
                @endphp
            )
            );
        window.localStorage.setItem('base_url', '<?php echo request()->root(); ?>');
        window.addEventListener('load', function() {
            document.querySelector('.root-preloader').remove();
        });
    </script>
</body>
</html>
