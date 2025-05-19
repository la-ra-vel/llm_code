<div class="mb-3 {{$mdClass}}">
    <div class="profile-picture-container">

        <input type="file" id="profile_picture_input" name="{{$name}}" accept="image/*"
            onchange="previewImage(event)" style="display: none;">
        <label for="profile_picture_input" class="profile-picture-label">
            <img id="profile_picture_preview"
                src="{{ asset('uploads/no-image.png') }}"
                alt="Profile Picture" class="profile-picture-img">
            <div class="overlay">
                <div class="text">Change Image</div>
            </div>
        </label>

    </div>
</div>


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
</style>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('profile_picture_preview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
