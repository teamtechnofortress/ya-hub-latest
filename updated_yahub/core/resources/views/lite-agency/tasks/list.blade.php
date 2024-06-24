@extends('layouts.lite-agency') 
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
            <strong style="font-size: 22px">Tasks</strong>
            <a style="float:right" href="#0" data-toggle="modal" data-target="#addTaskModal" class="btn btn-primary btn-sm add">Add Task +</a>
            <a style="float:right;margin-right: 5px !important" href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
        </div>
        <div class="col-md-12 task-form" style="display:none">
            <form action="{{route('store_task')}}" method="post" id="form-task" data-action="{{route('store_task')}}">
                @csrf
                <input type="hidden" class="task_id" name="id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Contact</label>
                            <select name="contact_id" id="contact" class="form-control contact_task_id" required>
                                <option value="">Select Contact</option>
                                @if($contacts_list)
                                    @foreach($contacts_list as $cont)
                                        <option value="{{$cont->id}}">{{$cont->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="task">Task</label>
                            <input type="text" class="form-control task_title" name="task" id="task" placeholder="Enter Task" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control task_status">
                                <option value="0" selected>In Progress</option>
                                <option value="1">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control task_date" name="end_date" id="end_date" placeholder="End date" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="note" class="form-label">Notes</label>
                            <textarea name="note" id="note" class="form-control task_note" placeholder="Notes"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4 text-right">
                            <a href="#0" class="btn btn-light cancel">Cancel</a>
                            <input type="submit" class="btn btn-secondary" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12 mt-4 task-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Contact</th>
                        {{-- <th>Company</th> --}}
                        <th>Task</th>
                        <th>Note</th>
                        {{-- <th>Status</th>
                        <th>End Date</th>
                        <th>Created</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($tasks)
                        @foreach($tasks as $task)
                            @php 
                                $contact = null;
                                $company = null;
                                if($task->contact_id){
                                    $contact = \DB::table('contacts')->where('id',$task->contact_id)->first();
                                    if($contact){
                                        $company = \DB::table('companies')->where('id',$contact->company_id)->first();
                                    }
                                }
                            @endphp
                            <tr>
                                <td>
                                    @if($contact)
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
                                    @else
                                        No Contact
                                    @endif
                                </td>
                                {{-- <td>
                                    @if($company)
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
                                    @else
                                        No Company
                                    @endif
                                </td> --}}
                                <td>{{$task->title}}</td>
                                <td><div style="max-width: 300px">{!! $task->note !!}</div></td>
                                {{-- <td>{{$task->status==0 ? 'In Progress' : 'Completed'}}</td>
                                <td>{{date('d-m-Y', strtotime($task->end_date))}}</td>
                                <td>{{date('d-m-Y', strtotime($task->date_time))}}</td> --}}
                                <td>
                                    <a href="#0" data-action="{{route('update_task',$task->id)}}" data-id="{{$task->id}}" data-note="{{$task->note}}" data-contact="{{$task->contact_id}}" data-status="{{$task->status}}" data-task="{{$task->title}}" data-end_date="{{$task->end_date}}" class="btn btn-light btn-sm edit-task">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{route('delete_task',$task->id)}}" class="btn btn-danger btn-sm">
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
        $('.add,.cancel').click(function(){
            $('.task-table').toggle('slow')
            $('.task-form').toggle('slow')
            clear_form()
        })
        $('.edit-task').click(function(){
            $("#form-task").attr('action',$(this).data('action'))
            $(".task_id").val($(this).data('id'))
            $(".contact_task_id option:selected").each(function () {
            $(this).removeAttr('selected');
            })
            $(".contact_task_id option[value='"+$(this).data('contact')+"']").attr("selected","selected")
            $(".task_title").val($(this).data('task'))
            $(".task_status option:selected").each(function () {
            $(this).removeAttr('selected');
            })
            $(".task_status option[value='"+$(this).data('status')+"']").attr("selected","selected")
            $(".task_date").val($(this).data('end_date'))
            $(".task_note").val($(this).data('note'))
            $('.task-table').toggle('slow')
            $('.task-form').toggle('slow')
        })

        function clear_form(){
            $('#form-task').attr('action',$("#form-task").data('action'))
            $(".task_id").val('')
            $(".contact_task_id option:selected").each(function () {
            $(this).removeAttr('selected')
            })
            $(".task_title").val('')
            $(".task_note").val('')
            $(".task_status option:selected").each(function () {
            $(this).removeAttr('selected')
            })
            $(".task_date").val('')
        }
    </script>
    <div class="tb-hidden" style="display: none">
        <table class="table-hidden-estimate table-stripped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>Contact</th>
                    <th>Task</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @if($tasks)
                    @foreach($tasks as $task)
                        @php 
                            $contact = null;
                            $company = null;
                            if($task->contact_id){
                                $contact = \DB::table('contacts')->where('id',$task->contact_id)->first();
                                if($contact){
                                    $company = \DB::table('companies')->where('id',$contact->company_id)->first();
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                @if($contact)
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
                                @else
                                    No Contact
                                @endif
                            </td>
                            <td>{{$task->title}}</td>
                            <td><div style="max-width: 300px">@isset($company){!! $company->note !!}@endisset</div></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
