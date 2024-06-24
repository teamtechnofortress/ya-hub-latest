@extends('layouts.lite-agency') 
@section('content') 
<script src="https://cdn.tiny.cloud/1/znzsdkbqpt4o8ipdfqr6tvr0qopfzeyhrtbd7wrcvds5cgcq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<style>
.img-append{
    margin: 5px;
}
.card-footer span{
    font-size: 18px;
    color: #000000b8;
    font-weight: 700;
}
.card-footer a{
    float:right;
}
.h-info{
    border-bottom: 5px solid #1a96e9;
    padding-bottom: 10px;
    margin-bottom: 30px;
    width: max-content;
}
.show_img:hover .img-append{
    cursor: pointer;
}
.show_img .remove_img{
    display: none;
}
.show_img:hover .remove_img{
    display: block;
}
.show_img{
    position: relative;
    display:flex;
}
.remove_img{
    position: absolute !important;
    right: 47px !important;
}
.remove_img > i{
    color: #565656;
    font-size: 20px;
}
</style>
<div class="p-sm-4 p-3 project">
    <h1>New Email</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form method="post" action="{{route('lite-agency-sendmail')}}" enctype="multipart/form-data"> 
                    @csrf 
                    @method('post') 
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>To: </label>
                            <input type="email" name="mail_to" class="form-control" required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Title: </label>
                            <input type="text" name="title" class="form-control" required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Description: </label>
                            <textarea name="description" class="form-control full-featured"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="row">
                                <div class="col-md-12 form-control" style="height:unset;" id="show_images">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row" style="display:none;">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1">Images</label>
                                <input type="file" id="fileInput" class="form-control col-md-12" name="images[]" placeholder="Images" multiple>
                            </div>
                        </div>
                        <div class="col-md-12 my-2">
                            <div class="form-control" style="height:40px;padding:0 !important">
                                <a href="#0" class="btn btn-light" id="s_f">Upload Files <i class="fa fa-paperclip"></i></a>
                            </div>
                        </div>
                        <div class="form-group row" style="padding-top: 20px;display:none">
                            <div class="col-xs-12 floating-label-form-group controls">
                                <label for="dropContainer">Add Images</label>
                                <div id="dropContainer" style="text-align: center;background: #8080801f;font-size: 20px;font-family: monospace;width: 100%;border: 1px solid #80808026;border-radius: 10px;">
                                    {{-- <h4 style="margin-top: 10px !important;margin-bottom: 10px !important;">Drop files</h4>
                                    <h4 style="margin-bottom: 10px !important;">or</h4> --}}
                                    <div style="margin-bottom: 10px !important; margin-top:20px !important"><input style="font-size: 13px;font-family: revert;border: 1px solid #80808026;padding: 5px 10px;" type="button" class="btn btn-primary" id="s_f" value="Select Files"/></div>
                                    <div style="text-center"><small id="count_file"></small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <input type="submit" name="submit" value="Send" class="btn n-project"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<script>
   tinymce.init({
        selector: '.full-featured',
        plugins: 'advlist autolink image lists link code charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
        height : "300",
        width: '100%',
        file_picker_types: 'image',


        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            var url = "{{route('add-mail-media')}}";
            xhr.open('POST', url);
            xhr.setRequestHeader("X-CSRF-Token", '{{csrf_token()}}');
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                console.log('not');
                success(json.location);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        },
        convert_urls: false,
    })
</script>
<script>
    var total_seleted = 0;
    const dT = new DataTransfer();
    function refresh_files(){
        fileInput.files = dT.files;
        if(fileInput.files.length > 1){
              $("#count_file").text(fileInput.files.length + " Selected")
        }
        else if(fileInput.files.length < 1){
              $("#count_file").text("No file Selected.")
        }
        else if(fileInput.files.length == 1){
              $("#count_file").text(fileInput.files[0].name + " Selected")
        }
        $("#show_images").empty()
        for(let l=0;l < fileInput.files.length; l++){
            var reader = new FileReader()
            reader.onload = function (e) {
                $("#show_images").append('<div class="show_img" style="margin: 5px !important;"><span class="img-append">' +fileInput.files[l].name+ '</span><a href="#0" data-id="'+l+'" style="float:right;" class="remove_img"><i class="fa fa-trash"></i></a></div><hr>')
            }
            reader.readAsDataURL(fileInput.files[l]);
        }
    }
      dropContainer.ondragover = dropContainer.ondragenter = function(evt) {
          evt.preventDefault();
      };

      dropContainer.ondrop = function(evt) {
        // if(dT.items.length > 8 || evt.dataTransfer.files.length > 8){
        //     alert('Maximum files upload limit is 8. Please try again.')
        //     $("#show_images").append('<div class="alert alert-danger">Maximum files upload limit is 8. Please try again.</div>')
        // }
          for (let i = 0; i < evt.dataTransfer.files.length; i++) {
              dT.items.add(evt.dataTransfer.files[i]);
          }
          evt.preventDefault();
          refresh_files()
      };
  $("body").delegate('.remove_img','click',function(){
      var file_id  = $(this).data('id');
      dT.items.remove(file_id);
      refresh_files();
  })
  $("#fileInput").change(function(e){
      const { files } = document.getElementById('fileInput');
        for (let i = 0; i < files.length; i++) {
            const file = files[i]
            dT.items.add(file) // here you exclude the file. thus removing it.
        }
        refresh_files();
  })
  $("#s_f").click(function(){
      $("#fileInput").click()
  })
</script>
@endsection
