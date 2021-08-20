@extends("pardition.master")
@section("content")
    <form action="{{route("image.uploadStorage")}}" method="post" enctype="multipart/form-data">
        <div class="col-md-6 mt-5">
            <div class="form-group">
                <label for="">{{__("Image Upload")}}}</label>
                <input type="file" class="form-control-file" name="fileUpload" aria-describedby="fileHelpId">
            </div>
            @csrf
        </div>
        <input class="btn btn-success w-100 mt-5" type="submit" value="Kaydet"/>
    </form>
@endsection
