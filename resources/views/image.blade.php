<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Image Editor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            width: 100%;
            position: relative;
        }

        .frame {
            position: relative;
            width: 600px; /* Fixed frame width */
            height: 400px; /* Fixed frame height */
            border: 2px solid #007bff;
            margin-bottom: 20px;
            background-color: #fff;
            overflow: hidden;
        }

        #canvas {
            width: 100%;
            height: 100%;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        .controls {
            margin-top: 20px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .controls label {
            font-weight: bold;
        }

        .controls input[type="range"] {
            width: 150px;
        }

        .controls input[type="number"] {
            width: 60px;
        }

        .controls button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .controls button:hover {
            background-color: #0056b3;
        }

        .crop-box {
            border: 2px dashed rgba(0, 0, 0, 0.7);
            position: absolute;
            pointer-events: none;
            background-color: rgba(0, 0, 0, 0.2);
            cursor: crosshair;
        }

        .instructions {
            font-size: 14px;
            color: #555;
            margin: 20px 0;
            text-align: center;
        }

        .undo-button {
            background-color: #dc3545;
        }

        .undo-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <input type="file" id="upload" style="display: none;" />
        <label for="upload" class="controls">
            <button>
                <i class="fas fa-upload"></i> Upload Image
            </button>
        </label>

        <div class="frame">
            <canvas id="canvas"></canvas>
            <div id="cropBox" class="crop-box"></div>
        </div>

        <div class="controls">
            <div>
                <label for="brightness">Brightness:</label>
                <input type="range" id="brightness" min="0" max="2" step="0.1" value="1">
            </div>

            <div>
                <label for="contrast">Contrast:</label>
                <input type="range" id="contrast" min="0" max="2" step="0.1" value="1">
            </div>

            <div>
                <label for="saturation">Saturation:</label>
                <input type="range" id="saturation" min="0" max="2" step="0.1" value="1">
            </div>

            <div>
                <label for="rotation">Rotation:</label>
                <input type="number" id="rotation" min="0" max="360" step="1" value="0">
            </div>

            <button class="undo-button" onclick="undoLastAction()">Undo</button>
            <button onclick="enableCropping()">Crop Image</button>
        </div>

        <div class="instructions">
            Use the sliders to adjust brightness, contrast, and saturation. Set the rotation angle and select an area to crop automatically.
        </div>
    </div>

    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const cropBox = document.getElementById('cropBox');
        let img = new Image();
        let originalImgData;
        let filters = {
            brightness: 1,
            contrast: 1,
            saturation: 1,
            rotation: 0
        };
        let cropping = false;
        let startX, startY, endX, endY;
        let previousImgData = [];

        document.getElementById('upload').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    img.onload = () => {
                        const frame = document.querySelector('.frame');
                        canvas.width = frame.offsetWidth;
                        canvas.height = frame.offsetHeight;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        // Save original image data
                        originalImgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        applyEdits(); // Apply initial edits to the image
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        function applyEdits() {
            if (!originalImgData) return;

            // Save current image data before applying new edits
            previousImgData.push(ctx.getImageData(0, 0, canvas.width, canvas.height));

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            // Apply filters
            ctx.save();
            ctx.filter = `brightness(${filters.brightness}) contrast(${filters.contrast}) saturate(${filters.saturation})`;
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate((filters.rotation * Math.PI) / 180);
            ctx.drawImage(img, -canvas.width / 2, -canvas.height / 2, canvas.width, canvas.height);
            ctx.restore();
        }

        function undoLastAction() {
            if (previousImgData.length > 0) {
                const lastImgData = previousImgData.pop();
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.putImageData(lastImgData, 0, 0);
                applyEdits();
            }
        }

        function enableCropping() {
            cropping = true;
            cropBox.style.display = 'block';
            canvas.addEventListener('mousedown', startCropping);
            canvas.addEventListener('mousemove', cropMove);
            canvas.addEventListener('mouseup', cropImage);
        }

        function startCropping(e) {
            if (cropping) {
                startX = e.offsetX;
                startY = e.offsetY;
                cropBox.style.left = startX + 'px';
                cropBox.style.top = startY + 'px';
                cropBox.style.width = '0px';
                cropBox.style.height = '0px';
            }
        }

        function cropMove(e) {
            if (cropping) {
                endX = e.offsetX;
                endY = e.offsetY;
                const width = endX - startX;
                const height = endY - startY;
                cropBox.style.width = Math.abs(width) + 'px';
                cropBox.style.height = Math.abs(height) + 'px';
                if (width < 0) cropBox.style.left = endX + 'px';
                if (height < 0) cropBox.style.top = endY + 'px';
            }
        }

        function cropImage() {
            if (!cropping) return;

            cropping = false;
            cropBox.style.display = 'none';

            const cropX = parseInt(cropBox.style.left);
            const cropY = parseInt(cropBox.style.top);
            const cropWidth = parseInt(cropBox.style.width);
            const cropHeight = parseInt(cropBox.style.height);

            if (cropWidth <= 0 || cropHeight <= 0) {
                // Reset cropping if invalid dimensions
                cropBox.style.width = '0px';
                cropBox.style.height = '0px';
                return;
            }

            // Draw the cropped area to a temporary canvas
            const tempCanvas = document.createElement('canvas');
            const tempCtx = tempCanvas.getContext('2d');
            tempCanvas.width = cropWidth;
            tempCanvas.height = cropHeight;
            tempCtx.drawImage(canvas, cropX, cropY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

            // Update main canvas with cropped image
            canvas.width = cropWidth;
            canvas.height = cropHeight;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(tempCanvas, 0, 0);

            // Save current image data before applying new edits
            previousImgData = [ctx.getImageData(0, 0, canvas.width, canvas.height)];

            applyEdits();
        }

        // Event listeners for sliders and input
        document.getElementById('brightness').addEventListener('input', (e) => {
            filters.brightness = e.target.value;
            applyEdits();
        });

        document.getElementById('contrast').addEventListener('input', (e) => {
            filters.contrast = e.target.value;
            applyEdits();
        });

        document.getElementById('saturation').addEventListener('input', (e) => {
            filters.saturation = e.target.value;
            applyEdits();
        });

        document.getElementById('rotation').addEventListener('input', (e) => {
            filters.rotation = e.target.value;
            applyEdits();
        });
    </script>
</body>
</html>
