<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Tournament Display</title>
        <meta charset="utf-8">

        <style type="text/css">
            body {
                background-color: #000000;
                margin: 0px;
                overflow: hidden;
            }
        </style>
        <script src="Three.js"></script>
    </head>
    <body>
<div style="position:absolute;display:block;color:white">Click, hold and drag to rotate. 
hover on a ship to see the name. </div>

<script>
ships=new Array();
<?php

$url="http://public-crest.eveonline.com/tournaments/3/series/1/matches/0/static/";

$staticdata=file_get_contents($url);

$decoded=json_decode($staticdata);


$ships=array();
foreach ($decoded->ships as $ship) {
    $id=$ship->item->href;
    $type=$ship->type->name;
    $pilot=$ship->character->name;
    $ships[$id]=array("id"=>$id,"type"=>$type,"pilot"=>$pilot);
}

$initialframeurl="http://public-crest.eveonline.com/tournaments/3/series/1/matches/0/realtime/0/";
$initialframe=file_get_contents($initialframeurl);
$decodedframe=json_decode($initialframe);

foreach ($decodedframe->redTeamShipData as $red) {
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

    echo "ships['".$ship[id]."']=new Array();\n";
    echo "ships['".$ship[id]."']['id']='".$ship['id']."';\n";
    echo "ships['".$ship[id]."']['type']='".$ship['type']."';\n";
    echo "ships['".$ship[id]."']['pilot']='".$ship['pilot']."';\n";
    echo "ships['".$ship[id]."']['x']='".$ship['x']."';\n";
    echo "ships['".$ship[id]."']['y']='".$ship['y']."';\n";
    echo "ships['".$ship[id]."']['z']='".$ship['z']."';\n";
    echo "ships['".$ship[id]."']['team']='".$ship['team']."';\n";
    echo "ships['".$ship[id]."']['armor']='".$ship['armor']."';\n";
    echo "ships['".$ship[id]."']['shield']='".$ship['shield']."';\n";
    echo "ships['".$ship[id]."']['structure']='".$ship['structure']."';\n";
    echo "ships['".$ship[id]."']['color']=".$ship['color'].";\n";



}



?>
target = new THREE.Vector3( 0, 0, 0 );
arc=0;
radius=200;
var isMouseDown = false, onMouseDownPosition, theta = 45,
onMouseDownTheta = 45, phi = 60, onMouseDownPhi = 60,    isShiftDown = false;
var mouse = { x: 0, y: 0 }, INTERSECTED,position= { x: 0, y: 0 };


            var camera, scene, renderer,projector, particles = [];

            // let's get going! 
            init();

            function init() {

                // Camera params : 
                // field of view, aspect ratio for render output, near and far clipping plane. 
                camera = new THREE.PerspectiveCamera(80, window.innerWidth / window.innerHeight, 1, 20000 );
    
                // move the camera backwards so we can see stuff! 
                // default position is 0,0,0. 
                camera.position.z = 200;
                                camera.lookAt( target );
                // the scene contains all the 3D object data
                scene = new THREE.Scene();
                
                // camera needs to go in the scene 
                scene.add(camera);
    
                // and the CanvasRenderer figures out what the 
                // stuff in the scene looks like and draws it!
                renderer = new THREE.CanvasRenderer();
                renderer.setSize( window.innerWidth, window.innerHeight );
    
                // the renderer's canvas domElement is added to the body
                document.body.appendChild( renderer.domElement );
                projector = new THREE.Projector();
                makeParticles(); 
                document.addEventListener( 'mousemove', onDocumentMouseMove, false );
                document.addEventListener( 'mousedown', onDocumentMouseDown, false );
                document.addEventListener( 'mouseup', onDocumentMouseUp, false );
                document.addEventListener( 'mousewheel', onDocumentMouseWheel, false );
                document.addEventListener( 'DOMMouseScroll', onDocumentMouseWheel, false );
                onMouseDownPosition = new THREE.Vector2();
                // add the mouse move listener
                // render 30 times a second (should also look 
                // at requestAnimationFrame) 
                setInterval(update,1000/30); 
                renderer.domElement.oncontextmenu= function(){ return false;} 
            }

            // the main update function, called 30 times a second

            function update() {
                        

                var vector = new THREE.Vector3( mouse.x, mouse.y, 1 );
                projector.unprojectVector( vector, camera );
                var ray = new THREE.Ray( camera.position, vector.subSelf( camera.position ).normalize() );

                var intersects = ray.intersectObjects( scene.children );

                if ( intersects.length > 0 ) {
                        if ( INTERSECTED != intersects[ 0 ].object ) {
                             INTERSECTED = intersects[ 0 ].object;
                          document.getElementById("test").style.left=(position.x+30)+"px"; 
                          document.getElementById("test").style.top=(position.y-5)+"px";
        document.getElementById("test").innerHTML=INTERSECTED.name+'<br>'+INTERSECTED.type+'<br>'+INTERSECTED.team;
                          document.getElementById("test").style.display="block";
                          intersectrotation=false;
                        }
                }
                else
                {
                        document.getElementById("test").style.display="none";
                          intersectrotation=true;
                }

                camera.lookAt( target );
                renderer.render( scene, camera );

            }

            
            function makeParticles() { 
                
                var particle, material; 
                var geometry = new THREE.SphereGeometry( 5,16,16);
                for ( var ship in ships ) {
                    color=ships[ship]['color'];
                    var particle = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: color } ) );
                    particle.position.x = ships[ship]['x']/100;
                    particle.position.y = ships[ship]['y']/100;
                    particle.position.z = ships[ship]['y']/100;
                    particle.name=ships[ship]['pilot'];
                    particle.team=ships[ship]['team'];
                    particle.type=ships[ship]['type'];
                    particle.id=ship;
                    scene.add( particle );
                    particles.push(particle); 
                }
                
//                var ring = new THREE.Mesh(THREE.TorusGeometry( 50, 1, 100, 100 ), new THREE.MeshLambertMaterial( { color: 0xffffff } ) );
//                ring.position.x=0;
//                ring.position.y=0;
//                ring.position.z=0;
//                scene.add( ring); 
            }
            

            function particleRender( context ) {
                
                context.beginPath();
                context.arc( 0, 0, 1, 0,  Math.PI * 2, true );
                context.fill();
            };


            function onDocumentMouseUp( event ) {

                event.preventDefault();
  
                isMouseDown = false;
                onMouseDownPosition.x = event.clientX - onMouseDownPosition.x;
                onMouseDownPosition.y = event.clientY - onMouseDownPosition.y;


                if ( onMouseDownPosition.length() > 5 ) {

                    return;

                }

                update();

            }



            function onDocumentMouseDown( event ) {

                event.preventDefault();

                isMouseDown = true;

                onMouseDownTheta = theta;
                onMouseDownPhi = phi;
                onMouseDownPosition.x = event.clientX;
                onMouseDownPosition.y = event.clientY;


                var vector = new THREE.Vector3( mouse.x, mouse.y, 1 );
                projector.unprojectVector( vector, camera );
                var ray = new THREE.Ray( camera.position, vector.subSelf( camera.position ).normalize() );

                var intersects = ray.intersectObjects( scene.children );


            }

            function onDocumentMouseMove( event ) {

                event.preventDefault();
                mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
                mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;
                position.x=event.clientX;
                position.y=event.clientY;
                if ( isMouseDown ) {

                    theta = - ( ( event.clientX - onMouseDownPosition.x ) * 0.5 ) + onMouseDownTheta;
                    phi = ( ( event.clientY - onMouseDownPosition.y ) * 0.5 ) + onMouseDownPhi;

                    phi = Math.min( 180, Math.max( 0, phi ) );

                    camera.position.x = radius * Math.sin( theta * Math.PI / 360 ) * Math.cos( phi * Math.PI / 360 );
                    camera.position.y = radius * Math.sin( phi * Math.PI / 360 );
                    camera.position.z = radius * Math.cos( theta * Math.PI / 360 ) * Math.cos( phi * Math.PI / 360 );
                    camera.updateMatrix();
                    update();
                }

            }

         function onDocumentMouseWheel( event ) {

                if (event.detail)
                {
                           radius -= event.detail*10;
                }
                if (event.wheelDelta)
                {
                     radius -=event.wheelDelta; 
                }
                update();

        }            
        </script>
<div id="test" style="position:absolute;z-index:10;color:white;display:none"></div>
    </body>
</html>
