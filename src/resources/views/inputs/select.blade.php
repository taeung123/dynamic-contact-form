<div class="form-group col-12 col-md-{{$input->column}} {{$input->slug}}">
    <label for="{{$input->slug}}"> {!!$input->label!!} :</label>
    <select name="{{$input->slug}}" id="{{$input->slug}}" class="form-control">
        @if ($input->contactFormInputItems->count() > 0)
        @foreach ($input->contactFormInputItems as $item)
        <option value="{{$item->value}}">{!!$item->label!!}</option>
        @endforeach
        @endif
    </select>
    <small id="{{$input->slug}}" class="form-text text-muted">
        {!!$input->note!!}
    </small>
</div>
