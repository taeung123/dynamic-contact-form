<div class="form-group col-12 col-md-{{$input->column}} {{$input->slug}}">
    <label for="">{!!$input->label!!}:</label>
    <textarea id="{{$input->slug}}" class="form-control" name="{{$input->slug}}"
        placeholder="{{$input->placeholder}}"></textarea>
    <small id="{{$input->slug}}" class="form-text text-muted">
        {!!$input->note!!}
    </small>
</div>
