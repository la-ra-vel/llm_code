<div class="nav-header" style="background-color: #114944;">
    <a href="{{route('dashboard')}}" class="brand-logo">
        <!-- First SVG (Unchanged) -->
        <!-- In your nav-header.blade.php -->
        @php 
            $general = \App\Models\GeneralSetting::first();
            $logoPath = public_path('uploads/logo/' . ($general->logo ?: $general->default_image));
            $logo = imageToBase64($logoPath);
            $title = $general->title ?: 'Title';
        @endphp
        
        <div class="logo-container">
            <img src="{{ $logo }}" width="40px" height="40px" alt="Logo" class="logo">
        </div>
        <div class="brand-title text-white text-uppercase" style="font-size:18px; color: #ffffff !important">{{ $title }}</div>
    </a>
    <div class="nav-control" id="navControl">
        <div class="hamburger">
            <span class="line" style="background-color: #ffffff;"></span><span class="line text-white" style="background-color: #ffffff;"></span><span class="line text-white" style="background-color: #ffffff;"></span>
        </div>
    </div>
</div>
