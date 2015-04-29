<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $html_title or 'Laravel with Ace Admin template' }}</title>

    <meta name="description" content="{{ $html_description or 'Laravel with Ace Admin template' }}" />

    <!-- app.css is bootstrap.css (slightly modified by Ace template) compiled with less -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet">
    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('/css/ace-fonts.css') }}" />

    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('/css/ace.css') }}" class="ace-main-stylesheet" id="main-ace-style" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('/css/ace-part2.css') }}" class="ace-main-stylesheet" />
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset('/css/ace-ie.css') }}" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="{{ asset('/js/ace-extra.min.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>