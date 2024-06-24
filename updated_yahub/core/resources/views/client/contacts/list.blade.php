@extends('layouts.client') 
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
    </style>
    <style>
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
            <strong style="font-size: 22px">Contacts</strong>
            <a style="float:right" href="{{route('add_contact')}}" class="btn btn-primary btn-sm">Add Contact +</a>
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
                                {{-- <td>{{$contact->email}}</td>
                                <td>{{$contact->phone}}</td> --}}
                                <td>
                                    <a href="#0" class="btn btn-sm btn-primary detail" data-toggle="modal" data-target="#exampleModal" 
                                        data-name="{{$contact->name}}" 
                                        data-img="{{$contact->profile_picture ? $contact->profile_picture : ''}}"
                                        data-cname="{{$company->name}}" 
                                        data-cimg="{{$company->profile_picture ? $company->profile_picture : ''}}"
                                        data-email="{{$contact->email}}"
                                        data-contact="{{$contact->phone}}"
                                        data-contact2="{{$contact->phone2}}"
                                        data-note="{{preg_replace('/(https?:\/\/\S+)/', '<a href="$1" target="_blank">$1</a>', $contact->note)}}"
                                        data-job_title="{{$contact->job_title}}"
                                        data-social="{{$contact->social}}"
                                        data-baseUrl="{{url('client/mail?email='.$contact->email)}}">Details
                                    </a>
                                    <a href="{{route('attachments',$contact->id)}}" class="btn btn-light btn-sm">
                                        Attachments
                                    </a>
                                    <a href="{{route('edit_contact',$contact->id)}}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{route('delete_contact',$contact->id)}}" class="btn btn-danger btn-sm">
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
            var contact = $(this).data('contact')
            var contact2 = $(this).data('contact2')
            var note = $(this).data('note')
            var url = $(this).data('baseurl')
            var job_title = $(this).data('job_title')
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
@endsection
