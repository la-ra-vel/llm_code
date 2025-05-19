<style>

.add-new-icon {
  margin-left: 100px; /* Adjust spacing as needed */
  font-size: 8px; /* Adjust size as needed */
  color: #007bff; /* Adjust color as needed */
  cursor: pointer; /* Makes it clear it's clickable */
}
</style>
<div class="mb-3 {{ $mdClass }}">
    @if ($label)
        <label class="form-label">{{ $label }} @if ($required) <font style="color: red;">*</font> @endif</label>@if($name=='court_categoryID')<span class="add-new-icon" id="addNewCourtCategory"><i class="fa fa-plus"> </i>Add New</span>@endif
    @endif
    @if ($customDropdown)
    <select class="disabling-options" id="{{$id}}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" @if($multiple) multiple="multiple" @endif>
    </select>
    @else
    <select class="disabling-options" id="{{$id}}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" @if($multiple) multiple="multiple" @endif>
        @if(!$multiple)<option value="">-select-</option>@endif

        @foreach ($options as $key => $option)
            @if (is_array($option))
            <?php
            /* echo "<pre>"; print_r("is array"); exit; */
            ?>
                {{-- Handle dynamic array --}}
                <option value="{{ $multiple ? ($keys ? $key : $option['id']) : $option['id'] }}" data-moduleID="{{$option['id']}}"
                {{ $multiple
                                                ? ($keys
                                                    ? (!empty($selected) && in_array($key, (array) $selected) ? 'selected' : '')
                                                    : (!empty($selected) && in_array($option['id'], (array) $selected) ? 'selected' : '')
                                                )
                                                : (!empty($selected) && $selected == $option['id'] ? 'selected' : '')
                                                }}>
                    {{ $option['name'] }}
                </option>
            @else
            <?php
            /* echo "<pre>"; print_r("not array"); exit; */
            ?>
                {{-- Handle static array --}}
                <option value="{{ $key }}"
                    {{ $multiple
                        ? (!empty($selected) && in_array($key, (array) $selected) ? 'selected' : '')
                        : (!empty($selected) && $selected == $key ? 'selected' : '')
                    }}>
                    {{ $option }}
                </option>
            @endif
        @endforeach

    </select>
    @endif
</div>
