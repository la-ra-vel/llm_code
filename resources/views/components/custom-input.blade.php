<div class="mb-3 {{$mdClass}}">
    @if ($label)
        <label class="form-label" for="{{ $name }}">{{ $label }} @if ($required) <font style="color: red;">*</font> @endif</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="{{ $class ?? ''}}"
        id="{{ $id }}"
        @if ($readonly) readonly @endif
    >
</div>
