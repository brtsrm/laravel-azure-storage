@extends("pardition.master")
@section("content")

    <div class="card-columns">


    </div>


    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Image Upload")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form onsubmit="event.preventDefault()" method="post" class="imageUpload"
                              enctype="multipart/form-data"
                              name="imageUpload">
                            <p>Image Upload</p>
                            <input type="file" name="imageUpload"/>
                            <input type="submit" value="{{__("Update")}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section("footer")
    <script>
        function getData() {

            $.ajax({
                method: "get",
                url: "{{route("api_image.get")}}",
                success: function (result) {
                    let add = "";
                    result.forEach(image => {
                        add += `
                            <div class="card">
                                <img class="card-img-top" src="${image[1]}" alt="">
                                <div class="card-body">
                                    <div class="btn btn-primary">
                                                <a class="text-white modalShow" data-toggle="modal" data-updateImage="${image[0]}" data-target="#modelId">{{__("Update")}}</a>
                                    </div>
                                    <div class="btn btn-danger">
                                        <a onclick='deleteImage("${image[0]}")'  class="text-white delete">{{__("Delete")}}</a>
                                    </div>
                                </div>
                            </div>
                            `;
                    })
                    $(".card-columns").html(add);
                }
            })

        }

        getData()

        $(document).on("click", ".modalShow", function () {
            $("#modelId").modal("toggle");
            var videoName = $(this).attr("data-updateImage");
            $(".imageUpload").submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append("videoName", videoName);
                $.ajax({
                    type: 'POST',
                    url: "{{route("api_image.update")}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        getData()
                    }
                })
            });
        });

        function deleteImage(image) {
            $.ajax({
                method: "delete",
                url: "{{route("api_image.delete")}}",
                data: {url: image},
                success: function (e) {
                    getData()
                }
            })
        }

    </script>

@endsection
