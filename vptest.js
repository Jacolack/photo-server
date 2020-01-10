function loadExif() {
	var img = document.getElementById("mainImg");
	var c = document.getElementById("mainCanvas");
	var ctx = c.getContext("2d");
	ctx.save();
	ctx.translate(c.width/2, c.height/2);
	ctx.rotate(90*Math.PI/180);
	var hRatio = c.width  / img.width    ;
	var vRatio =  c.height / img.height  ;
	var ratio  = Math.min ( hRatio, vRatio );
	var centerShift_x = ( c.width - img.width*ratio ) / 2;
	var centerShift_y = ( c.height - img.height*ratio ) / 2;  
	ctx.drawImage(img, 0,0, img.width, img.height,
		-(img.width*ratio/2), -(img.height*ratio/2), img.width*ratio, img.height*ratio);  
	ctx.restore();
}
