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
                        <th>Form Name</th>
                        <th>Form Title</th>
                        <th>Form Description</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($forms as $i => $form)
                        <tr>
                            <td>{{$i+1}}</td>
                            <td>{{$form->form_name}}</td>
                            <td>{{$form->title}}</td>
                            <td>@if($form->description!=null){{$form->description}}@else No Description @endif</td>
                            <td>
                                <center>	
                                    <a href="/show-form/{{$form->id}}">
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
<a href="/create-form/{{$project_id}}">
    <button style="position:fixed; right:3%; bottom:115px;" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
</a>
<button style="position:fixed; right:3%; bottom:60px;"  data-toggle="modal" data-target="#export-modal" title="Export Form" class="btn btn-info btn-circle" type="button"><i class="fa fa-arrow-right fa-lg"></i></button>

<!-- Export Modal -->
<div class="modal" id="export-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Project</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>     
            <div class="modal-body">
                Export this project?
            </div>   
            <div class="modal-footer">
                <a href="/export-project"><button id="btn-export" type="button" class="btn btn-danger">Export</button></a>
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
@endsection