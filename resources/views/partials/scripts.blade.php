<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='{{ asset('/js/jquery.js') }}'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='{{ asset('/js/jquery1x.js') }}'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('/js/jquery.mobile.custom.js') }}'>"+"<"+"/script>");
</script>

<script src="{{ asset('/js/bootstrap.js') }}"></script>


<!-- page specific plugin scripts: TODO -->

<!-- ace scripts -->
<script src="{{ asset('/js/ace/elements.scroller.js') }}"></script>
<script src="{{ asset('/js/ace/elements.colorpicker.js') }}"></script>
<script src="{{ asset('/js/ace/elements.fileinput.js') }}"></script>
<script src="{{ asset('/js/ace/elements.typeahead.js') }}"></script>
<script src="{{ asset('/js/ace/elements.wysiwyg.js') }}"></script>
<script src="{{ asset('/js/ace/elements.spinner.js') }}"></script>
<script src="{{ asset('/js/ace/elements.treeview.js') }}"></script>
<script src="{{ asset('/js/ace/elements.wizard.js') }}"></script>
<script src="{{ asset('/js/ace/elements.aside.js') }}"></script>
<script src="{{ asset('/js/ace/ace.js') }}"></script>
<script src="{{ asset('/js/ace/ace.ajax-content.js') }}"></script>
<script src="{{ asset('/js/ace/ace.touch-drag.js') }}"></script>
<script src="{{ asset('/js/ace/ace.sidebar.js') }}"></script>
<script src="{{ asset('/js/ace/ace.sidebar-scroll-1.js') }}"></script>
<script src="{{ asset('/js/ace/ace.submenu-hover.js') }}"></script>
<script src="{{ asset('/js/ace/ace.widget-box.js') }}"></script>
<script src="{{ asset('/js/ace/ace.settings.js') }}"></script>
<script src="{{ asset('/js/ace/ace.settings-rtl.js') }}"></script>
<script src="{{ asset('/js/ace/ace.settings-skin.js') }}"></script>
<script src="{{ asset('/js/ace/ace.widget-on-reload.js') }}"></script>
<script src="{{ asset('/js/ace/ace.searchbox-autocomplete.js') }}"></script>

<!-- inline scripts related to this page: TODO -->

<!-- the following scripts are used in demo only for onpage help and you don't need them -->
{{-- <link rel="stylesheet" href="{{ asset('/js/css/ace.onpage-help.css" />
<link rel="stylesheet" href="../docs/assets/js/themes/sunburst.css" />

<script type="text/javascript"> ace.vars['base'] = '..'; </script>
<script src="{{ asset('/js/js/ace/elements.onpage-help.js"></script>
<script src="{{ asset('/js/js/ace/ace.onpage-help.js"></script>
<script src="../docs/assets/js/rainbow.js"></script>
<script src="../docs/assets/js/language/generic.js"></script>
<script src="../docs/assets/js/language/html.js"></script>
<script src="../docs/assets/js/language/css.js"></script>
<script src="../docs/assets/js/language/javascript.js"></script> --}}