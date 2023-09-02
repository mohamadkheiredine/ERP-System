$(function(){
		const canvas = document.getElementById('WAREHOUSEDRAWING');
        const ctx = canvas.getContext('2d');

        let cuboid = {
            width: $('#W_WAREHOUSE_WIDTH').val(),
            height: $('#W_WAREHOUSE_HEIGHT').val(),
            depth: $('#W_WAREHOUSE_LENGTH').val(),
            x: canvas.width / 2,
            y: canvas.height / 2,
            rotationX: 0,
            rotationY: 0
        };
        const faces = [
            { vertices: [0, 1, 5, 4], color: 'red' },    // Front face
            { vertices: [1, 2, 6, 5], color: 'green' },  // Right face
            { vertices: [2, 3, 7, 6], color: 'blue' },   // Back face
            { vertices: [3, 0, 4, 7], color: 'orange' }, // Left face
            { vertices: [0, 1, 2, 3], color: 'purple' }, // Top face
            { vertices: [4, 5, 6, 7], color: 'yellow' }  // Bottom face
        ];
	
	 warehouses_module.DisplayWarehouseSettingsTab();
	 $(".WarehouseDimensions").on("click",function(){
		 $("#TAB").val("warehouse_dimension");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $(".WarehouseZones").on("click",function(){
		 $("#TAB").val("warehouse_zones");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $(".WarehouseEmployees").on("click",function(){
		 $("#TAB").val("warehouse_employees");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $(".WarehouseLoad").on("click",function(){
		 $("#TAB").val("warehouse_load");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $("#WAREHOUSEDIMENSIONS").on("click","#BTN_DRAW_IMAGE",function(){
		 cuboid = {
		            width: $('#W_WAREHOUSE_WIDTH').val(),
		            height: $('#W_WAREHOUSE_HEIGHT').val(),
		            depth: $('#W_WAREHOUSE_LENGTH').val(),
		            x: canvas.width / 2,
		            y: canvas.height / 2,
		            rotationX: 0,
		            rotationY: 0
		        };
		 console.log(cuboid);
		drawCuboid(400,400,cuboid,faces,ctx);
	        animate(cuboid);
	 });
	 $("button[name=btn_save_employee]").on("click",warehouses_module.AddWarehouseEmployee);
	 $("#m_wizard_warehouse_zones").on("click","#BTN_CREATE_ZONE",warehouses_module.AddNewWarehouseZone);
	 $("#m_wizard_warehouse_zones").on("click","a[id*=DELETE_ZONE_]",warehouses_module.DeleteWarehouseZone);
	 $("#m_wizard_warehouse_employees").on("click","a[id*=DELETE_EMPLOYEE_]",warehouses_module.DeleteWarehouseEmployee);
	// $("#SAVE_SETTINGS").on("click",);
	 var canvas_width = $('#CanvasPage').width();
	 $('canvas').width(canvas_width);
	 $('select').select2();
 })
 
 
  function drawCuboid(width ,height ,cuboId , Faces , canvastx) {
        	if(canvastx != undefined)
			canvastx.clearRect(0, 0, width, height);

            Faces.forEach((face, idx) => {
            	canvastx.beginPath();
                face.vertices.forEach((vertexIdx, vIdx) => {
                    const vertex = getCuboidVertex(cuboId, vertexIdx);
                    const rotatedVertex = rotateVertex(vertex, cuboId.rotationX, cuboId.rotationY);
                    const projectedVertex = projectVertex(rotatedVertex , cuboId);
                    if (vIdx === 0) {
                    	canvastx.moveTo(projectedVertex.x, projectedVertex.y);
                    } else {
                    	canvastx.lineTo(projectedVertex.x, projectedVertex.y);
                    }
                });
                canvastx.closePath();
                canvastx.fillStyle = face.color;
                canvastx.fill();
            });
        }

        function getCuboidVertex(cuboId, vertexIdx) {
            const x = cuboId.x + (vertexIdx & 1 ? cuboId.width : 0) * (vertexIdx & 2 ? -1 : 1);
            const y = cuboId.y + (vertexIdx & 2 ? cuboId.height : 0) * (vertexIdx > 1 ? -1 : 1);
            const z = vertexIdx > 3 ? cuboId.depth : 0;
            return { x, y, z };
        }

        function rotateVertex(vertex, rotationX, rotationY) {
            // Perform 3D rotations here (e.g., using matrices)
            // For simplicity, we'll skip 3D rotations in this basic example
            return vertex;
        }

        function projectVertex(vertex , cuboId) {
            // Apply perspective projection (3D to 2D)
            const scale = 200; // Adjust the scale to fit your canvas size
            const x = vertex.x * scale / (vertex.z + scale) + cuboId.x;
            const y = vertex.y * scale / (vertex.z + scale) + cuboId.y;
            return { x, y };
        }

        function animate(cuboId) {
        	cuboId.rotationX += 0.01;
        	cuboId.rotationY += 0.01;
            drawCuboid();
            requestAnimationFrame(animate);
        }
