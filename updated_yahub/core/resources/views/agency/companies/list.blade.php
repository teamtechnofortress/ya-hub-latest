@extends('layouts.agency') 
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
    /* div.dt-buttons{
        position: absolute !important;
        bottom: 461px !important;
        left: 78.9% !important;


    }
    .dataTables_wrapper .dt-buttons .buttons-pdf    {
        color: #fff; 
        border-radius: 3px; 
        padding: 3px 12px;
        margin-right: 10px;
        font-size: 12px !important;
        border-color: #007BFF;   
        color: #fff;  
        background-color: #007BFF !important; 
    }
    .dataTables_wrapper .dt-buttons .buttons-pdf:hover{
        background-color: #007BFF; 
        color: #fff; 
    }
    .dataTables_paginate{
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
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                    <p style="font-size: 18px;"><strong> Companies </strong></p>
                    <div>
                        <a href="#0" class="btn btn-primary btn-sm btn-pdf">Export PDF</a>                 
                        <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                        <a href="{{route('add_company')}}" class="btn btn-primary btn-sm">Add Company +</a>      
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($companies)
                        @foreach($companies as $company)
                            <tr>
                                <td>
                                    @if($company->profile_picture)
                                        <div class="flex-center">
                                            <div class="pr-10">
                                                <img style="width:40px;height:40px;border-radius: 50%;" src="{{$company->profile_picture}}" alt="" srcset="">
                                            </div>
                                            <div>
                                            {{$company->name}}
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-center">
                                            <div class="pr-10">
                                                <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                            </div>
                                            <div>
                                            {{$company->name}}
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td><div style="max-width: 300px">{!! $company->address !!}</div></td>
                                <td><div style="max-width: 300px">{!! preg_replace('/(https?:\/\/\S+)/', '<a href="$1" target="_blank">$1</a>', $company->note) !!}</div></td>
                                <td>
                                    <a href="{{route('edit_company',$company->id)}}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a data-action="{{route('delete_company',$company->id)}}" class="btn btn-danger btn-sm">
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
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
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

</script>
<script>
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
                                // Exclude column at index 3
                                if (idx === 3) {
                                    return false;
                                }
                                return true;
                            },
                            format: {
                                body: function (data, rowIdx, columnIdx, node) {
                                    var textContent = $('<div>').html(data).text();
                                    textContent = textContent.trim();
                                    return textContent;
                                }
                            }
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

            // Hide the PDF button now that the DataTable is initialized
            globalTable.buttons('.buttons-pdf').nodes().css("display", "none");
        });

        $('.btn-pdf').on('click', function() {
            if (globalTable) {
                globalTable.button('.buttons-pdf').trigger();
            } else {
                console.error("DataTable is not initialized yet.");
            }
        });
    });


</script>
    <div class="tb-hidden" style="display: none">
        <table class="table-hidden-estimate table-stripped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @if($companies)
                    @foreach($companies as $company)
                        <tr>
                            <td>
                                @if($company->profile_picture)
                                    <div class="flex-center">
                                        <div class="pr-10">
                                            <img style="width:40px;height:40px;border-radius: 50%;" src="{{$company->profile_picture}}" alt="" srcset="">
                                        </div>
                                        <div>
                                        {{$company->name}}
                                        </div>
                                    </div>
                                @else
                                    <div class="flex-center">
                                        <div class="pr-10">
                                            <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                        </div>
                                        <div>
                                        {{$company->name}}
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td><div style="max-width: 300px">{!! $company->address !!}</div></td>
                            <td><div style="max-width: 300px">{!! $company->note !!}</div></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
