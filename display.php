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
        <script type="text/javascript" src="transform.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type='text/javascript'>

var scaler=5;
ships=new Array();
<?php

$tournament=4;
if (isset($_GET['tournament']) and is_numeric($_GET['tournament'])) {
    $tournament=$_GET['tournament'];
}
$series=0;

if (isset($_GET['series']) and is_numeric($_GET['series'])) {
    $series=$_GET['series'];
}


$statsurl="https://public-crest.eveonline.com/tournaments/".$tournament."/series/".$series."/matches/0/";

$statsdata=file_get_contents($statsurl);

$decodedstats=json_decode($statsdata);

$initialframeurl=$decodedstats->firstReplayFrame->href;
$staticurl=$decodedstats->staticSceneData->href;
$lastframeurl=$decodedstats->lastReplayFrame->href;

echo "var lastframeurl='".$lastframeurl."';\n";
$staticdata=file_get_contents($staticurl);

$decoded=json_decode($staticdata);


$ships=array();
foreach ($decoded->ships as $ship) {
    $id=$ship->item->href;
    $type=$ship->type->name;
    $pilot=$ship->character->name;
    $typeid=end(explode('/', trim($ship->type->href, "/")));
    $ships[$id]=array("id"=>$id,"type"=>$type,"pilot"=>$pilot,"typeid"=>$typeid);
}

$initialframeurl="https://public-crest.eveonline.com/tournaments/".$tournament."/series/".$series."/matches/0/realtime/0/";
$initialframe=file_get_contents($initialframeurl);
$decodedframe=json_decode($initialframe);

$redships=array();
$blueships=array();

foreach ($decodedframe->redTeamShipData as $red) {
    $redships[]=$red->itemRef->href;
    $ships[$red->itemRef->href]['x']=$red->physicsData->x;
    $ships[$red->itemRef->href]['y']=$red->physicsData->y;
    $ships[$red->itemRef->href]['z']=$red->physicsData->z;
    $ships[$red->itemRef->href]['armor']=$red->armor;
    $ships[$red->itemRef->href]['shield']=$red->shield;
    $ships[$red->itemRef->href]['structure']=$red->structure;
    $ships[$red->itemRef->href]['team']='red';
    $ships[$red->itemRef->href]['color']='0xff0000';
}

foreach ($decodedframe->blueTeamShipData as $blue) {
    $blueships[]=$blue->itemRef->href;
    $ships[$blue->itemRef->href]['x']=$blue->physicsData->x;
    $ships[$blue->itemRef->href]['y']=$blue->physicsData->y;
    $ships[$blue->itemRef->href]['z']=$blue->physicsData->z;
    $ships[$blue->itemRef->href]['armor']=$blue->armor;
    $ships[$blue->itemRef->href]['shield']=$blue->shield;
    $ships[$blue->itemRef->href]['structure']=$blue->structure;
    $ships[$blue->itemRef->href]['team']='blue';
    $ships[$blue->itemRef->href]['color']='0x0000ff';
}

foreach ($ships as $ship) {
    echo "ships['".$ship['id']."']=new Array();\n";
    echo "ships['".$ship['id']."']['id']='".$ship['id']."';\n";
    echo "ships['".$ship['id']."']['type']='".$ship['type']."';\n";
    echo "ships['".$ship['id']."']['pilot']='".$ship['pilot']."';\n";
    echo "ships['".$ship['id']."']['x']='".$ship['x']."';\n";
    echo "ships['".$ship['id']."']['y']='".$ship['y']."';\n";
    echo "ships['".$ship['id']."']['z']='".$ship['z']."';\n";
    echo "ships['".$ship['id']."']['team']='".$ship['team']."';\n";
    echo "ships['".$ship['id']."']['armor']='".$ship['armor']."';\n";
    echo "ships['".$ship['id']."']['shield']='".$ship['shield']."';\n";
    echo "ships['".$ship['id']."']['structure']='".$ship['structure']."';\n";
    echo "ships['".$ship['id']."']['color']=".$ship['color'].";\n";
    echo "ships['".$ship['id']."']['typeid']=".$ship['typeid'].";\n";
    echo "ships['".$ship['id']."']['destroyed']=0;\n";

}



?>
</script>

        <script type="text/javascript">
 var camera,scene;
 var frame=1;
 var follow=0;
 var url="https://public-crest.eveonline.com/tournaments/<?php echo $tournament ?>/series/<?php echo $series ?>/matches/0/realtime/";
            function onDocumentLoad()
            {
                ccpwgl.setResourcePath('res', 'https://web.ccpgamescdn.com/ccpwgl/res/');


                var canvas = document.getElementById('mainCanvas');
                ccpwgl.initialize(canvas);
              var scene = ccpwgl.loadScene('res:/dx9/scene/universe/a01_cube.red');
//                var sceneColor = new Uint8Array(4);
//                sceneColor[0] = 0.0;
//                sceneColor[1] = 0.0;
//                sceneColor[2] = 0.0;
//                sceneColor[3] = 0.0;
//                scene = ccpwgl.createScene(sceneColor);


     		camera = new TestCamera(canvas);
                camera.minDistance = 10;
                camera.maxDistance = 100000;
                camera.fov = 30;
                camera.distance = 5000;
                camera.nearPlane = 1;
                camera.farPlane = 10000000;
                camera.minPitch = -0.5;
                camera.maxPitch = 0.65;
                ccpwgl.setCamera(camera);

//                wreck=scene.loadObject('res:/dx9/model/shipwrecks/medium_wreck_gallente.red', undefined);
//                var wreckTransform=wreck.getTransform();
//                wreckTransform[0] = wreckTransform[5] = wreckTransform[10] = wreckTransform[15] = 1.0;
//                wreckTransform[12] = 400000;
//                wreckTransform[13] = 400000;
//                wreckTransform[14] = 400000;
//                wreck.setTransform(wreckTransform);

                for ( var ship in ships ) {

                    window.ships[ship]['ship'] = scene.loadShip(
                    graphicIDs[typeIDs[ships[ship]['typeid']]['graphicID']]['graphicFile'], undefined);
                    window.ships[ship]['ship'].loadBoosters(
                        boosterPaths[graphicIDs[typeIDs[ships[ship]['typeid']]['graphicID']]['race']]);
                    var shipTransform = ships[ship]['ship'].getTransform();

                    shipTransform[0] = shipTransform[5] = shipTransform[10] = shipTransform[15] = 1.0;
                    shipTransform[12] = ships[ship]['x']/scaler;
                    shipTransform[13] = ships[ship]['y']/scaler;
                    shipTransform[14] = ships[ship]['z']/scaler;
                    window.ships[ship]['ship'].setTransform(shipTransform);
                } 

                ccpwgl.enablePostprocessing(true);

        		ccpwgl.onPreRender = function () 
        		{ 
        		
        		
        		};
        		

            }
            
            function moveCamera(id)
            {
                camera.poi[0]=ships[id]['x']/scaler;
                camera.poi[1]=ships[id]['y']/scaler;
                camera.poi[2]=ships[id]['z']/scaler;
                ccpwgl.setCamera(camera);
                follow=id;

            }

            function stopFollow()
            {
                follow=0;
            }


            function frameAdvance()
            {
                if (url+frame+"/" != lastframeurl)
                {
                    frame=frame+1;
                    $.getJSON(url+frame+"/",function(data) {renderFrame(data)});
                }
                else
                {
                    alert("complete");
                }
            }
            
            function renderFrame(data)
            {
                for (var ship in data['blueTeamShipData'])
                {
                    id=data['blueTeamShipData'][ship]['itemRef']['href'];
                    if ('physicsData' in data['blueTeamShipData'][ship])
                    {   
                        vx = data['blueTeamShipData'][ship]['physicsData']['x']-ships[id]['x'];
                        vy = data['blueTeamShipData'][ship]['physicsData']['y']-ships[id]['y'];
                        vz = data['blueTeamShipData'][ship]['physicsData']['z']-ships[id]['z'];
                        anglex=Math.atan(vy/vx);
                        angley=Math.atan(vx/vz)+Math.PI;
                        anglez=Math.atan(vx/vz)+Math.PI;
                        var shipTransform = ships[id]['ship'].getTransform();
                        mat4.identity(shipTransform);
                        shipTransform[12] = data['blueTeamShipData'][ship]['physicsData']['x']/scaler;
                        shipTransform[13] = data['blueTeamShipData'][ship]['physicsData']['y']/scaler;
                        shipTransform[14] = data['blueTeamShipData'][ship]['physicsData']['z']/scaler;
                        mat4.rotateX(shipTransform,anglex);
                        mat4.rotateY(shipTransform,angley);
                        mat4.rotateZ(shipTransform,anglez);
                        ships[id]['x']=data['blueTeamShipData'][ship]['physicsData']['x'];
                        ships[id]['y']=data['blueTeamShipData'][ship]['physicsData']['y'];
                        ships[id]['z']=data['blueTeamShipData'][ship]['physicsData']['z'];
                        ships[id]['ship'].setTransform(shipTransform);
                    } else {
                        if (ships[id]['destroyed']==0) {
                            var shipTransform = ships[id]['ship'].getTransform();
                            shipTransform[0] = shipTransform[5] = shipTransform[10] = shipTransform[15] = 1.0;
                            shipTransform[12] = 400000;
                            shipTransform[13] = 400000;
                            shipTransform[14] = 400000;
                            ships[id]['ship'].setTransform(shipTransform);
//                          wreck=scene.loadShip('res:/dx9/model/worldobject/debris/debris.red', undefined);
//                          var wreckTransform=wreck.getTransform();
//                          wreckTransform[0] = wreckTransform[5] = wreckTransform[10] = wreckTransform[15] = 1.0;
//                          wreckTransform[12] = ships[id]['x']/scaler;
//                          wreckTransform[13] = ships[id]['y']/scaler;
//                          wreckTransform[14] = ships[id]['z']/scaler;
//                          wreck.setTransform(wreckTransform);
                            ships[id]['destroyed']=1;
                            alert( ships[id]['pilot']+" destroyed");
                        }
                    }
                    divid=id.split("/");
                    divid.pop();
                    divid=divid.pop();
  $( "#shield-"+divid ).progressbar({ value: Math.floor(data['blueTeamShipData'][ship]['shield']*100) });
  $( "#armor-"+divid ).progressbar({ value: Math.floor(data['blueTeamShipData'][ship]['armor']*100) });
  $( "#structure-"+divid ).progressbar({ value: Math.floor(data['blueTeamShipData'][ship]['structure']*100) });
                }
                for (var ship in data['redTeamShipData'])
                {
                    if ('physicsData' in data['redTeamShipData'][ship])
                    {
                        id=data['redTeamShipData'][ship]['itemRef']['href'];
                        vx = data['redTeamShipData'][ship]['physicsData']['x']-ships[id]['x'];
                        vy = data['redTeamShipData'][ship]['physicsData']['y']-ships[id]['y'];
                        vz = data['redTeamShipData'][ship]['physicsData']['z']-ships[id]['z'];
                        anglex=Math.atan(vy/vx);
                        angley=Math.atan(vx/vz)+Math.PI;
                        anglez=Math.atan(vx/vz)+Math.PI;
                        var shipTransform = ships[id]['ship'].getTransform();
                        mat4.identity(shipTransform);
                        shipTransform[12] = data['redTeamShipData'][ship]['physicsData']['x']/scaler;
                        shipTransform[13] = data['redTeamShipData'][ship]['physicsData']['y']/scaler;
                        shipTransform[14] = data['redTeamShipData'][ship]['physicsData']['z']/scaler;
                        mat4.rotateX(shipTransform,anglex);
                        mat4.rotateY(shipTransform,angley);
                        mat4.rotateZ(shipTransform,anglez);
                        ships[id]['x']=data['redTeamShipData'][ship]['physicsData']['x'];
                        ships[id]['y']=data['redTeamShipData'][ship]['physicsData']['y'];
                        ships[id]['z']=data['redTeamShipData'][ship]['physicsData']['z'];
                        ships[id]['ship'].setTransform(shipTransform);
                    } else {
                        if (ships[id]['destroyed']==0) {
                            var shipTransform = ships[id]['ship'].getTransform();
                            shipTransform[0] = shipTransform[5] = shipTransform[10] = shipTransform[15] = 1.0;
                            shipTransform[12] = 400000;
                            shipTransform[13] = 400000;
                            shipTransform[14] = 400000;
                            ships[id]['ship'].setTransform(shipTransform);
//                            wreck=scene.loadObject('res:/dx9/model/worldobject/debris/debris.red', undefined);
//                            var wreckTransform=wreck.getTransform();
//                            wreckTransform[0] = wreckTransform[5] = wreckTransform[10] = wreckTransform[15] = 1.0;
//                           wreckTransform[12] = ships[id]['x']/scaler;
//                            wreckTransform[13] = ships[id]['y']/scaler;
//                            wreckTransform[14] = ships[id]['z']/scaler;
                            ships[id]['destroyed']=1;
                            alert( ships[id]['pilot']+" destroyed");
                        }
                    }
                    divid=id.split("/");
                    divid.pop();
                    divid=divid.pop();
  $( "#shield-"+divid ).progressbar({ value: Math.floor(data['redTeamShipData'][ship]['shield']*100) });
  $( "#armor-"+divid ).progressbar({ value: Math.floor(data['redTeamShipData'][ship]['armor']*100) });
  $( "#structure-"+divid ).progressbar({ value: Math.floor(data['redTeamShipData'][ship]['structure']*100) });
                }
                if (follow)
                {
                    moveCamera(follow);
                }
            }

            onload = onDocumentLoad;

        </script>

<style>

html, body {width:100%;height:100%;margin:0}
.max {width:100%;height:100%;margin:0}

</style>
    </head>
    <body>
<div class="max">
        <div style="width:30%;height:100%;float:left">
        <div id="redships">
        <table style="width:100%">
        <tbody id='redships' style="background-color:#aa0000">
        <?php

        foreach ($redships as $id) {
            $divid=end(explode("/", trim($id, "/")));
            echo "<tr><th>";
            echo '<span onclick="moveCamera(\''.$id.'\');" class="ship">'.$ships[$id]['pilot'].'</span>';
            echo "</th><td>".$ships[$id]['type']."</td>";
            echo "<td width='30px'><div id='shield-".$divid."'></div></td>";
            echo "<td width='30px'><div id='armor-".$divid."'></td>";
            echo "<td width='30px'><div id='structure-".$divid."'></div></td></tr>\n";
        }
        ?>
        </tbody>
        <tbody id='blueships' style="background-color:#0066FF">
        <?php
        foreach ($blueships as $id) {
            $divid=end(explode("/", trim($id, "/")));
            echo "<tr><th>";
            echo '<span onclick="moveCamera(\''.$id.'\');" class="ship">'.$ships[$id]['pilot'].'</span>';
            echo "</th><td>".$ships[$id]['type']."</td>";
            echo "<td width='30px'><div id='shield-".$divid."'></div></td>";
            echo "<td width='30px'><div id='armor-".$divid."'></td>";
            echo "<td width='30px'><div id='structure-".$divid."'></div></td></tr>\n";
        }

        ?>
        </tbody>
        </table>
        <button id="advance" onclick="frameAdvance()">Frame Advance</button>
        <button id="advance" onclick="stopFollow()">Stop Following</button>
        </div>
        </div>
        <div style="width:70%;height:100%;float:right">
                <canvas id="mainCanvas" style="width:100%;height:100%"></canvas>
        </div>
 <script type='text/javascript'>
$(function() { 
<?php
foreach ($blueships as $id) {
    $divid=end(explode("/", trim($id, "/")));
    echo '$( "#shield-'.$divid.'" ).progressbar({ value: 100 });'."\n";
    echo '$( "#armor-'.$divid.'" ).progressbar({ value: 100 });'."\n";
    echo '$( "#structure-'.$divid.'" ).progressbar({ value: 100 });'."\n";
}
foreach ($redships as $id) {
    $divid=end(explode("/", trim($id, "/")));
    echo '$( "#shield-'.$divid.'" ).progressbar({ value: 100 });'."\n";
    echo '$( "#armor-'.$divid.'" ).progressbar({ value: 100 });'."\n";
    echo '$( "#structure-'.$divid.'" ).progressbar({ value: 100 });'."\n";
}
?>
});
 </script>
</body>
</html>
