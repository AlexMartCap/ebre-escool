<!DOCTYPE html>
<html lang="en">
@include('partials.htmlheader')
<body class="no-skin">

@include('partials.navbar')

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    @include('partials.sidebar')

    <div class="main-content">
        <div class="main-content-inner">
            @include('partials.breadcrumbs')

            <div class="page-content">
                @include('partials.settingsbox')
                @yield('main-content')
            </div>
            <!-- /page-content -->
        </div>
        <!-- /main-content-inner -->
    </div>
    <!-- /main-content -->

    @include('partials.footer')

</div>
<!-- /main-container -->

@include('partials.scripts')

</body>
</html>
