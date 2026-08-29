<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/logo.ico" sizes="any">
        {{-- <link rel="icon" href="/favicon.svg" type="image/svg+xml"> --}}
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <meta name="description" content="Annapolis Security Agency, Inc. (ASAI) — transforming security for a better business." />

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

        <script src="/particles.min.js"></script>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'ASAI — Annapolis Security Agency, Inc.') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <!-- Loading screen -->
        <div id="app-loader">
            <img src="/images/logo.png" alt="ASAI Logo" id="loader-logo" />
            <div class="loader-bar"><div class="loader-bar-fill"></div></div>
        </div>

        <style>
            #app-loader {
                position: fixed;
                inset: 0;
                background: #0d1117;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 2.5rem;
                z-index: 99999;
                transition: opacity 0.6s ease, visibility 0.6s ease;
            }
            #app-loader.hidden {
                opacity: 0;
                visibility: hidden;
            }
            #loader-logo {
                width: min(400px, 70vw);
                height: auto;
                animation: loaderPulse 2s ease-in-out infinite;
            }
            .loader-bar {
                width: min(320px, 60vw);
                height: 3px;
                background: rgba(255,255,255,0.1);
                border-radius: 99px;
                overflow: hidden;
            }
            .loader-bar-fill {
                height: 100%;
                background: linear-gradient(90deg, #1d219d, #b8860b);
                border-radius: 99px;
                animation: loaderFill 1.4s cubic-bezier(0.4,0,0.2,1) forwards;
            }
            @keyframes loaderPulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.8; transform: scale(1.04); }
            }
            @keyframes loaderFill {
                0% { width: 0%; }
                100% { width: 100%; }
            }
        </style>

        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    var loader = document.getElementById('app-loader');
                    if (loader) loader.classList.add('hidden');
                }, 1500);
            });
        </script>

        <x-inertia::app />
    </body>
</html>
