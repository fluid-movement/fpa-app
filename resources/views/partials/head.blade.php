<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'FPA App' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=work-sans:400,500,600&display=swap" rel="stylesheet"/>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
