@if(Auth::user()->role==1)
    <?php $layout = 'layouts.admin'; ?>
@elseif(Auth::user()->role==2)
    <?php $layout = 'layouts.agency'; ?>
@elseif(Auth::user()->role==3)
    <?php $layout = 'layouts.client'; ?>
@elseif(Auth::user()->role==4)
    <?php $layout = 'layouts.lite-agency'; ?>
@endif
@extends($layout)
@section('content') 
    <style>
        .table{
            border: 0 !important;
        }
        .pr-10{
            padding-right: 10px;
        }
        .flex-center{
            display: flex;
            /* justify-content: center; */
            align-items: center
        }
        th{
            text-align:left !important;
        }
        td{
            text-align:left !important;
            border-right: 0px !important;
            border-left: 0px !important
        }
        .modal-dialog-centered{
            max-width: 100% !important;
            justify-content: center
        }
        .modal-content{
            max-width: 500px;
        }
        .modal-header .close {
            padding: 0
        }
        .my-row{
            border-bottom: 1px solid #8080801a;
        }
    </style>
    {{-- Add Category Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="add_attachment_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('add_attachment',$contact)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Attachment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <input type="text" name="title" class="form-control" placeholder="Title" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="file">Files</label>
                            <input type="file" id="file" class="form-control" name="attachments[]" multiple required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm mr-2" type="submit">Save</button>
                        <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <strong style="font-size: 22px">Attachments</strong>
            <a style="float:right" data-toggle="modal" data-target="#add_attachment_modal" class="btn btn-primary btn-sm ml-2">New Attachment +</a>
            <a style="float:right" href="{{url('contacts')}}" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
        </div>
        <div class="col-md-12 mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($attachments)
                        @foreach($attachments as $key=>$at)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$at->title}}</td>
                                <td><a href="{{$at->path}}" target="_blank" class="btn btn-sm btn-light">View <i class="fa fa-eye"></i></a></td>
                                <td>{{$at->created_at}}</td>
                                <td>
                                    <a href="{{route('delete_attachment',$at->id)}}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
