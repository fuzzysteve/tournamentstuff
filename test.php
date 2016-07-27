<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>WebGL Tournament Test</title>

        <script type="text/javascript" src="src/external/glMatrix-0.9.5.min.js"></script>
        <script type="text/javascript" src="src/ccpwgl_int.js"></script>
        <script type="text/javascript" src="src/test/TestCamera2.js"></script>
        <script type="text/javascript" src="src/ccpwgl.js"></script>
        <script type="text/javascript" src="staticData/typeIDs.js"></script>
        <script type="text/javascript" src="staticData/graphicIDs.js"></script>
        <script type="text/javascript" src="staticData/boosterTypes.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
           <script> 
            function renderFrame()
            {
                 $( "#shield-1" ).progressbar({ value: 90 });
            }

        </script>

<style>

html, body {width:100%;height:100%;margin:0}
.max {width:100%;height:100%;margin:0}
</style>
    </head>
    <body>
    <button onclick="renderFrame()">test</button>
    <table><tr><td style="width=30px"><div id="#shield-1"></div></td></tr>
    </table>
    <script>
$(function() { 
    $( "#shield-1" ).progressbar({ value: 100 });});
 </script>
</body>
</html>
