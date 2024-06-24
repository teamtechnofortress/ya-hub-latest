@extends('layouts.admin') 
@section('content') 
<style>
    .card{
        padding: 20px;
        border: none;
        box-shadow: 0px 0px 5px 0px #00000014
    }
    .progress-bar-danger{
        background-color: #c33e3e;
    }
</style>
<div class="p-sm-4 p-3 project">
    <h1>Usage</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <span class="mb-3">Total Usage</span> 
                    <div class="progress" style="height: 1.5rem">
                        @if((int)count($projects) > (int)$maxLimit)
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40"
                                aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                Out of Limit
                            </div>
                        @elseif((int)count($projects) == (int)$maxLimit)
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40"
                                aria-valuemin="0" aria-valuemax="100" style="width:{{(int)count($projects) / (int)$maxLimit * 100}}%">
                                {{(int)count($projects) / (int)$maxLimit * 100}}%
                            </div>
                        @else
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                            aria-valuemin="0" aria-valuemax="100" style="width:{{(int)count($projects) / (int)$maxLimit * 100}}%">
                            {{(int)count($projects) / (int)$maxLimit * 100}}%
                        </div>
                        @endif
                    </div>
                    @if((int)count($projects) <= (int)$maxLimit)
                        <span style="text-align: right">{{count($projects)}} / {{$maxLimit}}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
