@extends('layouts.dynamic-form-layout')
@section('body')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="table-responsive" style="background:white;padding:15px 5px;">
                <table id="example" class="table table-bordered">
                    <thead class="thead-dark">
                        <th>No</th>
                        <th>Project Name</th>
                        <th>Dropbox App Key</th>
                        <th>Dropbox App Secret</th>
                        <th>Dropbox Access Token</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($projects as $i => $project)
                        <tr>
                            <td>{{$i+1}}</td>
                            <td>{{$project->project_name}}</td>
                            <td>{{$project->dropbox_app_key}}</td>
                            <td>{{$project->dropbox_app_secret}}</td>
                            <td>{{$project->dropbox_access_token}}</td>
                            <td>
                                <center>	
                                    <a href="/project/{{$project->id}}">
                                        <i class="fa fa-eye" style="color:#28a745; font-size:20px;"></i>
                                    </a> 
                                    <a href="#">
                                        <i class="fa fa-edit" style="color:#10707f; font-size:20px;"></i>
                                    </a> 
                                    <a href="#">
                                        <i class="fa fa-trash" style="color:#b21f2d; font-size:20px;"></i>
                                    </a>                            
                                </center>												
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<a href="create-project">
    <button style="position:fixed; right:3%; bottom:6%;" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
</a>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script> 
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );       
</script>

@endsection