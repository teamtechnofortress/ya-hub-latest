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
    </style>
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
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
                                <td>{{$contact->email}}</td>
                                <td>{{$contact->phone}}</td>
                                <td>
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
@endsection
