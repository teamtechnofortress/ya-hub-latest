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
        bottom: 287px  !important;
        left: 79.7% !important;


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
    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.25rem !important;
    }
    .dt-buttons {
        display: none !important;
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
    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 14px;font-weight: 300">
                    <div class="row">
                        <div class="col-lg-3" style="display: flex;align-items: center;justify-content: left;">Name: </div>
                        <div class="col-lg-9">
                            <div class="flex-center">
                                <div class="pr-10" id="contact-img">
                                    <img style="width:40px;height:40px;border-radius: 50%;" src="" alt="" srcset="">
                                </div>
                                <div id="contact_name"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-2"><hr></div>
                        <div class="col-lg-3 mt-2" style="display: flex;align-items: center;justify-content: left;">Company: </div>
                        <div class="col-lg-9 mt-2">
                            <div class="flex-center">
                                <div class="pr-10" id="company-img">
                                    <img style="width:40px;height:40px;border-radius: 50%;" src="" alt="" srcset="">
                                </div>
                                <div id="company_name"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-2"><hr></div>
                        <div class="col-lg-3 mt-2" style="display: flex;align-items: center;justify-content: left;">Email: </div>
                        <div class="col-lg-9 mt-2">
                            <div id="cemail"></div>
                        </div>
                        <div class="col-12 mt-2"><hr></div>
                        <div class="col-lg-3 mt-2" style="display: flex;justify-content: left;">Phone: </div>
                        <div class="col-lg-9 mt-2">
                            <div id="cphone"></div>
                        </div>
                        <div class="col-12 mt-2"><hr></div>
                        <div class="col-lg-3 mt-2" style="display: flex;align-items: center;justify-content: left;">Social: </div>
                        <div class="col-lg-9 mt-2">
                            <div id="social"></div>
                        </div>
                        <div class="col-12 mt-2"><hr></div>
                        <div class="col-lg-3 mt-2" style="display: flex;align-items: center;justify-content: left;">Title/Skills: </div>
                        <div class="col-lg-9 mt-2">
                            <div id="job_title"></div>
                        </div>
                        <div class="col-12 mt-2"><hr></div>
                        <div class="col-lg-3 mt-2" style="display: flex;justify-content: left;">Notes: </div>
                        <div class="col-lg-9 mt-2">
                            <div id="note"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                    <p style="font-size: 18px;"><strong> Contacts </strong></p>
                    <div>
                        <a href="#0" class="btn btn-primary btn-sm btn-pdf">Export PDF</a>
                        <a  href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                        <a href="{{route('add_contact')}}" class="btn btn-primary btn-sm">Add Contact +</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        {{-- <th>Email</th>
                        <th>Phone</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($contacts)
                        @foreach($contacts as $contact)
                            @php 
                                $company = \DB::table('companies')->where('id',$contact->company_id)->first();
                            @endphp
                            <tr>
                                <td>
                                    @if($contact->profile_picture)
                                        <div class="flex-center">
                                            <div class="pr-10">
                                                <img style="width:40px;height:40px;border-radius: 50%;" src="{{$contact->profile_picture}}" alt="" srcset="">
                                            </div>
                                            <div>
                                            {{$contact->name}}
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-center">
                                            <div class="pr-10">
                                                <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                            </div>
                                            <div>
                                            {{$contact->name}}
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($company)
                                        @if($company->profile_picture)
                                            <div class="flex-center">
                                                <div class="pr-10 company_img">
                                                    <img style="width:40px;height:40px;border-radius: 50%;" src="{{$company->profile_picture}}" alt="" srcset="">
                                                </div>
                                                <div>
                                                {{$company->name}}
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex-center">
                                                <div class="pr-10 company_img">
                                                    <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                                </div>
                                                <div class="name_company">
                                                {{$company->name}}
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                {{-- <td style="display: none" class="contact">{{$contact->email}}</td>
                                <td style="display: none">{{$contact->phone}}</td> --}}
                                <td>
                                    <a href="#0" class="btn btn-sm btn-primary detail" data-toggle="modal" data-target="#exampleModal" 
                                    data-name="{{$contact->name}}" 
                                    data-img="{{$contact->profile_picture ? $contact->profile_picture : ''}}"
                                    data-cname="{{$company ? $company->name : ''}}" 
                                    data-cimg="@if($company){{$company->profile_picture ? $company->profile_picture : ''}} @endif"
                                    data-email="{{$contact->email}}"
                                    data-job_title="{{$contact->job_title}}"
                                    data-contact="{{$contact->phone}}"
                                    data-contact2="{{$contact->phone2}}"
                                    data-note="{{preg_replace('/(https?:\/\/\S+)/', '<a href="$1" target="_blank">$1</a>', $contact->note)}}"
                                    data-social="{{$contact->social}}"
                                    data-baseUrl="{{url('agency/mail?email='.$contact->email)}}">Details</a>
                                    <a href="{{route('attachments',$contact->id)}}" class="btn btn-light btn-sm">
                                        Attachments
                                    </a>
                                    <a href="{{route('edit_contact',$contact->id)}}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a data-action="{{route('delete_contact',$contact->id)}}" class="btn btn-danger btn-sm">
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
    <script>
        $('.detail').click(function(){
            var contactName = $(this).data('name')
            var contactProfile = $(this).data('img')
            var companyName = $(this).data('cname')
            var companyProfile = $(this).data('cimg')
            var email = $(this).data('email')
            var job_title = $(this).data('job_title')
            var contact = $(this).data('contact')
            var contact2 = $(this).data('contact2')
            var note = $(this).data('note')
            var url = $(this).data('baseurl')
            var social = $(this).data('social')
            $('#contact-img').html(`
                ${contactProfile!='' 
                    ? `<img style="width:40px;height:40px;border-radius: 50%;" src="${contactProfile}" alt="" srcset="">`
                    : `<i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>`
                }
            `)
            $('#company-img').html(`
                ${companyProfile!='' 
                    ? `<img style="width:40px;height:40px;border-radius: 50%;" src="${companyProfile}" alt="" srcset="">`
                    : `<i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>`
                }
            `)
            $('#contact_name').html(`${contactName}`)
            $('#company_name').html(`${companyName}`)
            $('#job_title').html(`${job_title}`)
            $('#cemail').html(`${email} <a href="${url}" class="btn btn-sm btn-light"><i class="fa fa-paper-plane"></i></a>`)
            $('#cphone').html(`${contact} ${contact2!='' ? '<br>' : ''} ${contact2}`)
            $('#note').html(`${note}`)
            $('#social').html(`<a target="_blank" href="${social}" class="btn btn-sm btn-light"><i class="fa fa-user"></i></a>`)
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
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
                                // Exclude column at index 2
                                if (idx === 2) {
                                    return false;
                                }
                                return true;
                            },
                            format: {
                                body: function (data, rowIdx, columnIdx, node) {
                                    if (columnIdx === 0 || columnIdx === 1 || columnIdx === 2) {
                                        var textContent = $('<div>').html(data).text();
                                        textContent = textContent.trim();
                                        return textContent;
                                    }
                                    return data;
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
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                @if($contacts)
                    @foreach($contacts as $contact)
                        @php 
                            $company = \DB::table('companies')->where('id',$contact->company_id)->first();
                        @endphp
                        <tr>
                            <td>
                                @if($contact->profile_picture)
                                    <div class="flex-center">
                                        <div class="pr-10">
                                            <img style="width:40px;height:40px;border-radius: 50%;" src="{{$contact->profile_picture}}" alt="" srcset="">
                                        </div>
                                        <div>
                                        {{$contact->name}}
                                        </div>
                                    </div>
                                @else
                                    <div class="flex-center">
                                        <div class="pr-10">
                                            <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                        </div>
                                        <div>
                                        {{$contact->name}}
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($company)
                                    @if($company->profile_picture)
                                        <div class="flex-center">
                                            <div class="pr-10 company_img">
                                                <img style="width:40px;height:40px;border-radius: 50%;" src="{{$company->profile_picture}}" alt="" srcset="">
                                            </div>
                                            <div>
                                            {{$company->name}}
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-center">
                                            <div class="pr-10 company_img">
                                                <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                            </div>
                                            <div class="name_company">
                                            {{$company->name}}
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td>{{$contact->email}}</td>
                            <td>{{$contact->phone}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
