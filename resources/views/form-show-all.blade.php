@extends('layouts.dynamic-form-layout')
@section('body')

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <form id="check-export-form" action="/export-project/{{$project_id}}" method="get">
                <div class="table-responsive" style="background:white;padding:15px 5px;">
                    <table id="example" class="table table-bordered">
                        <thead class="thead-dark">
                            <th>No</th>
                            <th>Form Name</th>
                            <th>Form Title</th>
                            <th>Form Description</th>
                            <th>Sub Form</th>
                            <th>Action</th>
                            <th>Download <input id="check-all" type="checkbox" checked></th>
                        </thead>
                        <tbody>
                            @foreach($forms as $i => $form)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$form->form_name}}</td>
                                    <td>{{$form->title}}</td>
                                    <td>@if($form->description!=null){{$form->description}}@else No Description @endif</td>
                                    <!-- <td>
                                        @if($form->form_type==0) Without Login
                                        @elseif($form->form_type==1) Login With User's Dropbox
                                        @elseif($form->form_type==2) Login With User's Dropbox + Admin Auth
                                        @endif
                                    </td> -->
                                    <td>
                                        {{$form->sub_form_count}} Sub Form 
                                        <a href="/form/{{$form->id}}/sub-forms" title="Go To Sub Form Page">
                                            <button type="button" style="color:orange;">Sub Form Page</button>
                                        </a>   
                                    </td>
                                    <td>
                                        <center>	
                                            <a href="/show-form/{{$form->id}}" title="Show Form">
                                                <i class="fa fa-eye" style="color:#28a745; font-size:20px;"></i>
                                            </a>     
                                            <a href="/edit-form/{{$form->id}}" title="Edit Form">
                                                <i class="fa fa-edit" style="color:#10707f; font-size:20px;"></i>
                                            </a> 
                                            <a data-id="{{ $form->id }}" title="Delete Form" href="#" class="hapus" data-toggle="modal" data-target="#modal-hapus">
                                                <i class="fa fa-trash" style="color:#b21f2d; font-size:20px;"></i>
                                            </a>    
                                            
                                            <br>
                                            <a href="/change-menu-index/{{ $form->id }}/{{'down'}}" title="Move Down">
                                                <i class="fa fa-arrow-down" style="color:white; font-size:20px;"></i>
                                            </a>          
                                            <a href="/change-menu-index/{{ $form->id }}/{{'up'}}" title="Move Up">
                                                <i class="fa fa-arrow-up" style="color:white; font-size:20px;"></i>
                                            </a>                        
                                        </center>												
                                    </td>
                                    <td>
                                        <center><input class="check-form" name="checked_form[]" type="checkbox" value="{{$form->id}}" checked></center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
                
                
                <!-- Export Modal -->
                <div class="modal" id="export-modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Download Project</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>     
                            <div class="modal-body">
                                <input style="margin-left:12px;" name="export_sql" type="checkbox" value="yes"> include mysql database structure (.sql)
                            </div>   
                            <div class="modal-footer">
                                <button id="btn-export" type="button" class="btn btn-danger" onClick="document.getElementById('check-export-form').submit();">Download <i class="fa fa-download fa-lg"></i></button>
                            </div>  
                        </div>
                    </div>
                </div> 


            </form>
        </div>
    </div>
</div>
<a href="/create-form/{{$project_id}}">
    <button style="position:fixed; right:3%; bottom:115px;" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
</a>
<button style="position:fixed; right:3%; bottom:60px;"  data-toggle="modal" data-target="#export-modal" title="Export Project" class="btn btn-info btn-circle" type="button"><i class="fa fa-download fa-lg"></i></button>



<div id="modal-hapus" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete This Form?</h4>
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
        var link = '/delete-form/' + id;
        $('#delete-form').attr("action", link);
    });
</script>
<script>
    $("#check-all").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
@endsection