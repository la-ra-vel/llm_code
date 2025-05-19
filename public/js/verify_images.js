// public/js/scripts.js

$(document).ready(function() {
    // Upload form submission
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/upload',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#image_path').val(response.image_path);
                alert('Image uploaded successfully!');
                getUserImages();
            },
            error: function(response) {
                alert('Failed to upload image');
            }
        });
    });

    // Verify form submission
    $('#verifyForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        // Create an array of known images from displayed user images
        var knownImages = [];
        $('#userImages img').each(function() {
            knownImages.push($(this).attr('src')); // Assuming 'src' holds the image path or URL
        });

        // Convert the array to a JSON string
        formData.append('known_images', JSON.stringify(knownImages));

        $.ajax({
            type: 'POST',
            url: '/verify',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Verification results: ' + JSON.stringify(response.results));
            },
            error: function(response) {
                alert('Failed to verify face');
            }
        });
    });

    // Get user images
    function getUserImages() {
        $.ajax({
            type: 'GET',
            url: '/user-images',
            success: function(response) {
                var imagesHtml = '';
                response.forEach(function(image) {
                    imagesHtml += '<img src="' + image.path + '" width="100" height="100">';
                });
                $('#userImages').html(imagesHtml);
            },
            error: function(response) {
                alert('Failed to retrieve user images');
            }
        });
    }

    getUserImages();

    // Set up camera
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var snap = document.getElementById('snap');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            video.srcObject = stream;
        })
        .catch(function(err) {
            console.log("An error occurred: " + err);
        });

    snap.addEventListener("click", function() {
        context.drawImage(video, 0, 0, 640, 480);
        var dataURL = canvas.toDataURL('image/png');
        $('#image_path').val(dataURL); // Send the captured image as base64 string
    });
});
