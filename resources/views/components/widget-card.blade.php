<div>
    <div class="{{$mdClass}}">
        <div class="{{$customClass}}">
            <div class="card-body  p-4">
                <div class="media">
                    @if($icon)
                    <span class="me-3">
                        <i class="{{$icon}}"></i>
                    </span>
                    @endif
                    <div class="media-body text-white text-end">
                        <p class="mb-1">{{$title}}</p>
                        <h3 class="text-white" id="{{$id}}">{{$value}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
