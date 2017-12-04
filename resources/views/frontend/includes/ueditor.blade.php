<script type="text/javascript">
var toolbars = [["fullscreen","source","undo","redo","insertunorderedlist",  
        "insertorderedlist","cleardoc","selectall","searchreplace","preview","justifyleft","justifycenter","justifyright","justifyjustify",  
        "touppercase","tolowercase","indent","removeformat","formatmatch","autotypeset","customstyle","paragraph","rowspacingbottom","rowspacingtop","lineheight","fontsize","charts"]];

var ue = UE.getEditor('editor',{    
	toolbars: toolbars,
	autoWidth: true,
	initialFrameWidth: '100%'
});
ue.ready(function() {
    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
});
</script>