<div class="form-group col-12 col-md-{{$input->column}} {{$input->slug}}">
    <label for="">{{$input->label}}:</label>
    <div class="upload-btn-wrapper">
        <p class="btn">
         Chọn tệp tin
        </p>
        <input type="file" name="{{$input->slug}}" />
        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
    </div>
</div>
