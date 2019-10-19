@extends('layouts.dynamic-form-layout')
@section('body')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            @foreach($projects as $i => $project)	
                <a href="/project/{{$project->id}}">									
                    <div class="card">
                        <div class="card-header">
                            {{$project->project_name}}
                            <a  style="float:right;" data-id="{{ $project->id }}" href="#" class="hapus" data-toggle="modal" data-target="#modal-hapus">
                                <i class="fa fa-trash" style="color:#b21f2d; font-size:20px;"></i>
                            </a>   
                            <a style="float:right; margin-right:5px;" href="/edit-project/{{$project->id}}">
                                <i class="fa fa-edit" style="color:#10707f; font-size:20px;"></i>
                            </a> 
                        </div>
                        <div class="card-body">
                        <label class="card-text">Dropbox App Key : {{$project->dropbox_app_key}}</label><br>
                        <label class="card-text">Dropbox App Secret : {{$project->dropbox_app_secret}}</label><br>
                        <label class="card-text">Dropbox Access Token : {{ str_limit($project->dropbox_access_token, $limit = 30, $end = '...') }}</label><br>
                        <a href="/project/{{$project->id}}" class="btn btn-primary">Show Form</a>
                        </div>
                    </div>
                </a><br>
            @endforeach     
        </div>
    </div>
</div>
<a href="create-project">
    <button style="position:fixed; right:3%; bottom:6%;" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
</a>

<div id="modal-hapus" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete This Project?</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="delete-form">
                    {{ csrf_field() }}
                    <div style="display: unset;" class="modal-footer">  
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-danger btn-block">Delete</button>
                            </div>      
                            <div class="col-md-6">
                                <button class="btn btn-info btn-block" data-dismiss="modal">Cancel</button>	
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script> 
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );       
</script>
<script>
    $(document).on("click", ".hapus", function () {
        var id = $(this).data('id');
        var link = '/delete-project/' + id;
        $('#delete-form').attr("action", link);
    });
</script>
@endsection