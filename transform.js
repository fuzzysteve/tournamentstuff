function getTransformationMatrix(ox, oy, oz, rx, ry, rz, s, d, f, n, ar, exz)
{
  // Pre-computes trigonometric values
  var cx = Math.cos(rx), sx = Math.sin(rx);
  var cy = Math.cos(ry), sy = Math.sin(ry);
  var cz = Math.cos(rz), sz = Math.sin(rz);
 
  // Tests if d is too small, hence making perspective projection not possible
  if (d <= -n)
  {
    // Transformation matrix without projection
    return new Float32Array([
      (cy*cz*s)/ar,cy*s*sz,-s*sy,0,
      (s*(cz*sx*sy-cx*sz))/ar,s*(sx*sy*sz+cx*cz),cy*s*sx,0,
      (s*(sx*sz+cx*cz*sy))/ar,s*(cx*sy*sz-cz*sx),cx*cy*s,0,
      (s*(cz*((-oy*sx-cx*oz)*sy-cy*ox)-(oz*sx-cx*oy)*sz))/ar,
      s*(((-oy*sx-cx*oz)*sy-cy*ox)*sz+cz*(oz*sx-cx*oy)),
      s*(ox*sy+cy*(-oy*sx-cx*oz)),1   
    ]);
  }
  else
  {
    // Pre-computes values determined with wxMaxima
    var A=d;
    var B=(n+f+2*d)/(f-n);
    var C=-(d*(2*n+2*f)+2*f*n+2*d*d)/(f-n);
     
    // Tests if X and Z must be exchanged
    if(!exz)
    {
      // Full transformation matrix
      return new Float32Array([
        (cy*cz*s*A)/ar,cy*s*sz*A,-s*sy*B,-s*sy,
        (s*(cz*sx*sy-cx*sz)*A)/ar,s*(sx*sy*sz+cx*cz)*A,cy*s*sx*B,cy*s*sx,
        (s*(sx*sz+cx*cz*sy)*A)/ar,s*(cx*sy*sz-cz*sx)*A,cx*cy*s*B,cx*cy*s,
        (s*(cz*((-oy*sx-cx*oz)*sy-cy*ox)-(oz*sx-cx*oy)*sz)*A)/ar,
        s*(((-oy*sx-cx*oz)*sy-cy*ox)*sz+cz*(oz*sx-cx*oy))*A,
        C+(s*(ox*sy+cy*(-oy*sx-cx*oz))+d)*B,s*(ox*sy+cy*(-oy*sx-cx*oz))+d
      ]);
    }
    else
    {
      // Full transformation matrix with XZ exchange
      return new Float32Array([
        -s*sy*B,cy*s*sz*A,(cy*cz*s*A)/ar,-s*sy,
        cy*s*sx*B,s*(sx*sy*sz+cx*cz)*A,(s*(cz*sx*sy-cx*sz)*A)/ar,cy*s*sx,
        cx*cy*s*B,s*(cx*sy*sz-cz*sx)*A,(s*(sx*sz+cx*cz*sy)*A)/ar,cx*cy*s,
        C+(s*(ox*sy+cy*(-oy*sx-cx*oz))+d)*B,s*(((-oy*sx-cx*oz)*sy-cy*ox)*sz+cz*(oz*sx-cx*oy))*A,
        (s*(cz*((-oy*sx-cx*oz)*sy-cy*ox)-(oz*sx-cx*oy)*sz)*A)/ar,s*(ox*sy+cy*(-oy*sx-cx*oz))+d
      ]);
    }
  }
}
