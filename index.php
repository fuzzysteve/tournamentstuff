<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>WebGL Tournament</title>

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

<style>

html, body {width:100%;height:100%;margin:0}
.max {width:100%;height:100%;margin:0}

</style>

<script type="text/javascript">

function renderList(data)
{
    for (var series in  data['items'])
    {
        rteam=data['items'][series]['redTeam']['team']['teamName'];
        bteam=data['items'][series]['blueTeam']['team']['teamName'];
        matches=data['items'][series]['matches']['href'];
        loser=data['items'][series]['loser']['team']['teamName'];
        matchOne=matches.substr(56,matches.length).slice(0,-9)
        $("#Series").append('<li><a href="/tournament/display.php?tournament=4&series='+matchOne+'">'+rteam+' vs '+bteam+'</a></li>');
    }

}









NEOurl="https://public-crest.eveonline.com/tournaments/4/series/";


$.getJSON(NEOurl,function(data) {renderList(data)});


</script>

    </head>
    <body>
<ul id="Series">

</ui>
</body>
</html>
