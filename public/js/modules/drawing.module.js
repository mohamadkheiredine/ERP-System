/**
 * 
 */


drawing_module = {
		drawCube : function(width,height) {
			const canvas = document.getElementById('myCanvas');
			  const ctx = canvas.getContext('2d');

			  const centerX = width / 2;
			  const centerY = height / 2;
			  const cubeSize = 100;

			  // Define the vertices of the cube
			  const vertices = [
			    { x: centerX - cubeSize / 2, y: centerY - cubeSize / 2 },
			    { x: centerX + cubeSize / 2, y: centerY - cubeSize / 2 },
			    { x: centerX + cubeSize / 2, y: centerY + cubeSize / 2 },
			    { x: centerX - cubeSize / 2, y: centerY + cubeSize / 2 },
			    { x: centerX - cubeSize / 2 - 30, y: centerY - cubeSize / 2 - 30 },
			    { x: centerX + cubeSize / 2 - 30, y: centerY - cubeSize / 2 - 30 },
			    { x: centerX + cubeSize / 2 - 30, y: centerY + cubeSize / 2 - 30 },
			    { x: centerX - cubeSize / 2 - 30, y: centerY + cubeSize / 2 - 30 },
			  ];

			  // Define the faces of the cube using indices of the vertices
			  const faces = [
			    [0, 1, 5, 4], // Front face
			    [1, 2, 6, 5], // Right face
			    [2, 3, 7, 6], // Back face
			    [3, 0, 4, 7], // Left face
			    [0, 1, 2, 3], // Top face
			    [4, 5, 6, 7], // Bottom face
			  ];

			  // Define colors for each face
			  const colors = [
			    'red', 'green', 'blue', 'orange', 'purple', 'yellow'
			  ];

			  // Draw the cube
			  faces.forEach((faceIndices, idx) => {
			    ctx.beginPath();
			    faceIndices.forEach((vertexIndex, vIdx) => {
			      const vertex = vertices[vertexIndex];
			      if (vIdx === 0) {
			        ctx.moveTo(vertex.x, vertex.y);
			      } else {
			        ctx.lineTo(vertex.x, vertex.y);
			      }
			    });
			    ctx.closePath();
			    ctx.fillStyle = colors[idx];
			    ctx.fill();
			  });
		}
};