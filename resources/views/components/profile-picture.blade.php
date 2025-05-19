{{-- resources/views/components/profile-picture.blade.php --}}
<div class="mb-3 {{ $mdClass }}">

    <div class="profile-picture-container">
        <input type="file" id="profile_picture_input" name="{{ $name }}" accept="image/*" onchange="previewImage(event, {{ @$user->id }})" style="display: none;">
        <label for="profile_picture_input" class="profile-picture-label">
            <img id="profile_picture_preview" src="@if (@$user->logo) {{getFile('users',@$user->logo)}} @else {{ asset('uploads/no-image.png') }} @endif" alt="Profile Picture" class="profile-picture-img">
            <div class="overlay" id="overlay">
                <div id="spinner" class="spinner" style="display: none;"></div>
                <div class="text">Change Image</div>
            </div>
        </label>
    </div>
</div>

@if($ajax)
<script>
    function previewImage(event, userId) {
        const overlay = document.getElementById('overlay');
        const spinner = document.getElementById('spinner');

        // Show spinner and overlay
        overlay.style.opacity = '1';
        spinner.style.display = 'block';

        // AJAX upload
        const formData = new FormData();
        formData.append('{{ $name }}', event.target.files[0]);
        formData.append('user_id', userId);

        fetch('{{ route('update.profile.pic') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                const output = document.getElementById('profile_picture_preview');
                output.src = `{{ asset('uploads/users') }}/${data.image}`;
            } else {
                toastr.error(data.message);
            }
            // Hide spinner and overlay after image is displayed
            overlay.style.opacity = '0';
            spinner.style.display = 'none';
        })
        .catch(error => {
            toastr.error('An error occurred while uploading the image.');
            console.error('Error:', error);
            // Hide spinner and overlay
            overlay.style.opacity = '0';
            spinner.style.display = 'none';
        });
    }
</script>
@else
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profile_picture_preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endif

<style>
.profile-picture-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: auto;
}

.profile-picture-label {
    display: block;
    cursor: pointer;
    width: 100%;
    height: 100%;
    position: relative;
}

.profile-picture-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100%;
    width: 100%;
    opacity: 0;
    transition: .5s ease;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-picture-label:hover .overlay {
    opacity: 1;
}

.text {
    color: white;
    font-size: 16px;
    text-align: center;
}

.spinner {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
