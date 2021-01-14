<div class="form-group {{$input->slug}}">
    <label for="">{{ $input->label}}:</label>
    <div class="checkbox">
        @if ($input->contactFormInputItems->count() > 0)
        @foreach ($input->contactFormInputItems as $item)
        <label>
            <input type="checkbox" name="{{$input->slug}}[]" value="{{$item->value}}">
            {{ $item->label}}
        </label>
        @endforeach
        @endif
        <input type="checkbox" name="{{$input->slug}}[]" value="" hidden checked>
    </div><small id="{{$input->slug}}" class="form-text text-muted">
        {{$input->note}}
    </small>
</div>
