@if(Auth::user()->role==2)
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
        .swal2-warning{
            margin-left: auto !important;
            margin-right: auto !important;
        }
    .swal2-confirm{
        margin-right: 20px !important;
    }
    .swal2-html-container{
        margin-bottom: 20px !important;
    }
    .swal2-title{
        margin-top: 10px !important;
    }
    div.dt-buttons{
        position: absolute !important;
        bottom: 261px !important;
        left: 80.4% !important;
    }


    .dataTables_wrapper .dt-buttons .buttons-pdf    {
        /* position: absolute; */
        color: #fff; 
        border-radius: 3px; 
        padding: 3px 12px;
        margin-right: 10px;
        font-size: 12px !important;
        border-color: #007BFF;     /* Your custom border color */
        color: #fff;  
        background-color: #007BFF !important; 
    }
    .dataTables_wrapper .dt-buttons .buttons-pdf:hover{
        background-color: #007BFF; 
        color: #fff; 
    }
    /* .dataTables_paginate{
        display: none !important;
    } */
    .table-responsive{
        overflow: none;
    }
    .dataTables_filter input{
        border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .dt-buttons {
        display: none !important;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.25rem !important;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        float: none;
        display: inline-block;
        margin-right: 10px;
    }

    .dataTables_wrapper div.dataTables_filter {
        float: inline-end;
    }
    </style>
    {{-- Add Bank Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="add_bank_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('add_bank')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add bank</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <input type="text" name="name" id="" value="" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="code" id="" value="" class="form-control" placeholder="Category Code" required>
                        </div>
                        <div class="form-group mb-2">
                            <select name="currency" id="" class="form-control" required>
                                <option value="">Select Currency</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
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
    {{-- Add Category Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="add_category_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('add_bank_cat')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <select name="category_bank" id="" class="form-control" required>
                                <option value="">Select bank</option>
                                @if($banks)
                                    @foreach($banks as $bankCat)
                                        <option value="{{$bankCat->id}}">{{$bankCat->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="category_name" id="" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="category_code" id="" class="form-control" placeholder="Category Code" required>
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
        <div class="d-flex justify-content-between align-items-center">
                <p style="font-size: 18px;"><strong> Bookkeeping </strong></p>
                <div>
                    <a href="#0" class="btn btn-primary btn-sm btn-pdf">Export PDF</a>
                    <a  href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                    <a style="" data-toggle="modal" data-target="#add_category_modal" class="btn btn-primary btn-sm">New Category +</a>
                    <a style="" data-toggle="modal" data-target="#add_bank_modal" class="btn btn-primary btn-sm">New Bank +</a>
                </div>
            </div>
            {{-- <a style="float:right;margin-right: 5px !important" href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a> --}}
        </div>
        <div class="col-md-12 mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($banks)
                        @foreach($banks as $bank)
                            {{-- {{dd($bank->categories)}} --}}
                            {{-- Add Bank Modal --}}
                            <div class="modal fade" tabindex="-1" role="dialog" id="edit_bank_modal_{{$bank->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{route('update_bank',$bank->id)}}" method="post">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit bank - ({{$bank->name}})</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group mb-2">
                                                    <input type="text" name="name" id="" value="{{$bank->name}}" class="form-control" placeholder="Name" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" name="code" id="" value="{{$bank->code}}" class="form-control" placeholder="Category Code" required>
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
                            {{-- Categories Bank Modal --}}
                            <div class="modal fade" tabindex="-1" role="dialog" id="categories_bank_modal_{{$bank->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Categories - ({{$bank->name}})</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <div class="row my-row">
                                                    <div class="col p-2"><strong>Name</strong></div>
                                                    <div class="col p-2"><strong>Code</strong></div>
                                                    <div class="col p-2"><strong>Action</strong></div>
                                                </div>
                                                @if($bank->categories)
                                                    @foreach($bank->categories as $b_cat)
                                                    {{-- Edit Category Modal --}}
                                                    <div class="modal fade modal-{{$b_cat->id}}" tabindex="-1" role="dialog" id="edit_category_modal_{{$bank->id}}_{{$b_cat->id}}">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{route('edit_categories_update',$b_cat->id)}}" method="post">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Edit - ({{$b_cat->name}})</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-2">
                                                                            <select name="category_bank" id="" class="form-control" required>
                                                                                <option value="{{$b_cat->bank->id}}" selected>{{$b_cat->bank->name}}</option>
                                                                                @if($banks)
                                                                                    @foreach($banks as $bankCat)
                                                                                        @if($b_cat->bank->id!=$bankCat->id)
                                                                                            <option value="{{$bankCat->id}}">{{$bankCat->name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group mb-2">
                                                                            <input type="text" name="category_name" value="{{$b_cat->name}}" class="form-control" placeholder="Name" required>
                                                                        </div>
                                                                        <div class="form-group mb-2">
                                                                            <input type="text" name="category_code" value="{{$b_cat->code}}" class="form-control" placeholder="Category Code" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-primary btn-sm mr-2" type="submit">Save</button>
                                                                        <a href="#0" class="btn btn-secondary btn-sm" onclick="$('#edit_category_modal_{{$bank->id}}_{{$b_cat->id}}').modal('hide')">Close</a>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- End --}}
                                                        <div class="row my-row">
                                                            <div class="col p-2">{{$b_cat->name}}</div>
                                                            <div class="col p-2">{{$b_cat->code}}</div>
                                                            <div class="col p-2">
                                                                <a href="#0" data-toggle="modal" data-target="#edit_category_modal_{{$bank->id}}_{{$b_cat->id}}" class="btn btn-light btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                {{-- <a href="{{route('delete_categories',$bank->id)}}" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </a> --}}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary btn-sm mr-2" type="submit">Save</button>
                                            <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End --}}
                        
                            <tr>
                                @php 
                                    if($bank->currency=='USD'){$currency = '$';}
                                    if($bank->currency=='EUR'){$currency = '€';}
                                    if($bank->currency=='GBP'){$currency = '£';}
                                @endphp
                                <td>{{$bank->code}}</td>
                                <td><a href="{{route('detail_bank',$bank->id)}}">{{$bank->name}}</a></td>
                                @php 
                                    $monyIn = $bank->details->where('type', 'MI | Money In')->sum('total');
                                    $monyOut = $bank->details->where('type', 'MO | Money Out')->sum('total');
                                    $totalBal = number_format((float)($monyIn) - ($monyOut),2,'.','');
                                @endphp
                                <td>{{$currency}}{{$totalBal}}</td>
                                <td>
                                    <a href="#0" data-toggle="modal" data-target="#categories_bank_modal_{{$bank->id}}" class="btn btn-light btn-sm">
                                        <i class="fas fa-list"></i>
                                    </a>
                                    <a href="#0" data-toggle="modal" data-target="#edit_bank_modal_{{$bank->id}}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a data-action="{{route('delete_bank',$bank->id)}}" class="btn btn-danger btn-sm">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.10/jspdf.plugin.autotable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        let actionLinks = document.querySelectorAll('.btn-danger');
        actionLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                let actionURL = this.getAttribute('data-action');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = actionURL;
                    }
                });
            });
        });
    });
    function toDataURL(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
            var reader = new FileReader();
            reader.onloadend = function() {
                callback(reader.result);
            }
            reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    }
    $(document).ready(function() {
    var globalTable; 
    toDataURL('https://ya-hub.com/app/frontend/Pics/logo.png', function(dataUrl) {
        globalTable = $('.table').DataTable({
            destroy: true,
            dom: '<"clear">lBfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            const totalColumns = $(node).parents('table').find('thead th').length;
                            if (idx === 3) {
                                return false;
                            }
                            return true;
                        },
                    },
                    customize: function(doc) {
                        doc.watermark = null;
                        var logo = dataUrl;
                        doc.content.splice(0, 0, {
                            margin: [10, 10, 10, 10],
                            alignment: 'center',
                            width: 50,
                            image: logo
                        });
                        doc.defaultStyle = {
                            alignment: 'center'
                        };
                        function removeWordFromContent(content, wordToRemove) {
                            if (content instanceof Array) {
                                for (var i = 0; i < content.length; i++) {
                                    content[i] = removeWordFromContent(content[i], wordToRemove);
                                }
                            } else if (content instanceof Object) {
                                for (var key in content) {
                                    if (content.hasOwnProperty(key)) {
                                        content[key] = removeWordFromContent(content[key], wordToRemove);
                                    }
                                }
                            } else if (typeof content === 'string') {
                                // Check if the content contains the word to remove
                                if (content.includes(wordToRemove)) {
                                    // Remove the word
                                    content = content.replace(wordToRemove, '');
                                }
                            }
                            return content;
                        }
                        doc.content = removeWordFromContent(doc.content, 'Ya-Hub.com');
                    }
                }
            ]
        });

        // Hide the DataTables buttons
        globalTable.buttons().container().hide();

        // Add a custom button click event handler
        $('.btn-pdf').on('click', function() {
            if (globalTable) {
                globalTable.button('.buttons-pdf').trigger();
            } else {
                console.error("DataTable is not initialized yet.");
            }
        });
    });
});


    </script>
    <div class="tb-hidden" style="display: none">
        <table class="table-hidden-estimate table-stripped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @if($banks)
                    @foreach($banks as $bank)
                        <tr>
                            @php 
                                if($bank->currency=='USD'){$currency = '$';}
                                if($bank->currency=='EUR'){$currency = '€';}
                                if($bank->currency=='GBP'){$currency = '£';}
                            @endphp
                            <td>{{$bank->code}}</td>
                            <td><a href="{{route('detail_bank',$bank->id)}}">{{$bank->name}}</a></td>
                            @php 
                                $monyIn = $bank->details->where('type', 'MI | Money In')->sum('total');
                                $monyOut = $bank->details->where('type', 'MO | Money Out')->sum('total');
                                $totalBal = number_format((float)($monyIn) - ($monyOut),2,'.','');
                            @endphp
                            <td>{{$currency}}{{$totalBal}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
