<div class="mb-3 {{ $mdClass ?? '' }}">
    @if ($label)
        <label class="form-label" for="{{ $name }}">
            {{ $label }}
            @if ($required)
                <font style="color: red;">*</font>
            @endif
        </label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows ?? 3 }}"
        cols="{{ $cols ?? 50 }}"
        placeholder="{{ $placeholder ?? '' }}"
        class="{{ $class ?? '' }}"
        @if ($readonly) readonly @endif
        @if ($required) required @endif
    >{{ old($name, $value ?? '') }}</textarea>
</div>
