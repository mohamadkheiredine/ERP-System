/**
 * 
 */


drawing_module = {
		drawCube : function(x, y, wx, wy, h, color , ctx) {

		    // LINE MODE
		    ctx.lineJoin = "round";
		    
		    // left face
		    ctx.beginPath();
		    ctx.moveTo(x, y);
		    ctx.lineTo(x - wx, y - wx * 0.5);
		    ctx.lineTo(x - wx, y - h - wx * 0.5);
		    ctx.lineTo(x, y - h * 1);
		    ctx.closePath();
		    ctx.fillStyle = color;
		    ctx.strokeStyle = "#7a7a51";
		    ctx.stroke();
		    ctx.fill();

		    // right face
		    ctx.beginPath();
		    ctx.moveTo(x, y);
		    ctx.lineTo(x + wy, y - wy * 0.5);
		    ctx.lineTo(x + wy, y - h - wy * 0.5);
		    ctx.lineTo(x, y - h * 1);
		    ctx.closePath();
		    ctx.fillStyle = color;
		    ctx.strokeStyle = "#676744";
		    ctx.stroke();
		    ctx.fill();

		    // center face
		    ctx.beginPath();
		    ctx.moveTo(x, y - h);
		    ctx.lineTo(x - wx, y - h - wx * 0.5);
		    ctx.lineTo(x - wx + wy, y - h - (wx * 0.5 + wy * 0.5));
		    ctx.lineTo(x + wy, y - h - wy * 0.5);
		    ctx.closePath();
		    ctx.fillStyle = color;
		    ctx.strokeStyle = "#8e8e5e";
		    ctx.stroke();
		    ctx.fill();
		},
		drawCylinder : function( x, y, w, h ,context) {
			  context.beginPath(); //to draw the top circle
			  for (var i = 0 * Math.PI; i < 2 * Math.PI; i += 0.001) {

			    xPos = (x + w / 2) - (w / 2 * Math.sin(i)) * 
			      Math.sin(0 * Math.PI) + (w / 2 * Math.cos(i)) * 
			      Math.cos(0 * Math.PI);

			    yPos = (y + h / 8) + (h / 8 * Math.cos(i)) * 
			      Math.sin(0 * Math.PI) + (h / 8 * 
			      Math.sin(i)) * Math.cos(0 * Math.PI);

			    if (i == 0) {
			      context.moveTo(xPos, yPos);

			    } 
			    else
			    {
			      context.lineTo(xPos, yPos);
			    }
			  }
			  context.moveTo(x, y + h / 8);
			  context.lineTo(x, y + h - h / 8);

			  for (var i = 0 * Math.PI; i < Math.PI; i += 0.001) {
			    xPos = (x + w / 2) - (w / 2 * Math.sin(i)) * Math.sin(0 * Math.PI) + (w / 2 * Math.cos(i)) * Math.cos(0 * Math.PI);
			    yPos = (y + h - h / 8) + (h / 8 * Math.cos(i)) * Math.sin(0 * Math.PI) + (h / 8 * Math.sin(i)) * Math.cos(0 * Math.PI);

			    if (i == 0) {
			      context.moveTo(xPos, yPos);

			    } 
			    else 
			    {
			      context.lineTo(xPos, yPos);
			    }
			  }
			  context.moveTo(x + w, y + h / 8);
			  context.lineTo(x + w, y + h - h / 8);            
		      context.fillStyle = '#8ED6FF';
		      context.strokeStyle = 'blue';
			  context.stroke();
			  context.fill();
			}
};