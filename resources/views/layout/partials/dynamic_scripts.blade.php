
@foreach (App\Helpers\AssetHelper::getAssets('layout')['scripts'] as $script)
        <script src="{{ $script }}"></script>
    @endforeach
