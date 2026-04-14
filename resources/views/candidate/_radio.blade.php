<label class="radio-opt">
    <input type="radio"
           name="{{ $name }}"
           value="{{ $value }}"
           x-model="{{ $model }}"
           {{ old($name) === $value ? 'checked' : '' }}>
    <div class="radio-pip"></div>
    <div>
        <div class="radio-label">{{ $label }}</div>
        @if(!empty($sub))<div class="radio-sub">{{ $sub }}</div>@endif
    </div>
</label>