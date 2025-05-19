<!DOCTYPE html>
<html>
<head>
    <title>Image Verification</title>
</head>
<body>
    <form id="uploadForm" action="/upload" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <button type="submit">Upload Image</button>
    </form>

    <div id="userImages"></div>

    <form id="verifyForm" action="/verify" method="post">
        @csrf
        <input type="hidden" name="image_path" id="image_path">
        <button type="submit">Verify Face</button>
    </form>

    <video id="video" width="640" height="480" autoplay></video>
    <button id="snap">Capture</button>
    <canvas id="canvas" width="640" height="480"></canvas>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/verify_images.js') }}"></script>
</body>
</html>
