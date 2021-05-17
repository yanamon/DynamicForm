@extends('layouts.dynamic-form-layout')
@section('body')

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <!-- <form id="check-export-form" action="/export-project/" method="get"> -->
                <div class="table-responsive" style="background:white;padding:15px 5px;">
                    <table id="example" class="table table-bordered">
                        <thead class="thead-dark">
                            <th>No</th>
                            <th>Form Name</th>
                            <th>Form Title</th>
                            <th>Form Description</th>
                            <!-- <th>Login Type</th> -->
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($sub_forms as $i => $sub_form)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$sub_form->sub_form_name}}</td>
                                    <td>{{$sub_form->title}}</td>
                                    <td>@if($sub_form->description!=null){{$sub_form->description}}@else No Description @endif</td>
                                    <!-- <td>
                                        @if($sub_form->sub_form_type==0) Without Login
                                        @elseif($sub_form->sub_form_type==1) Login With User's Dropbox
                                        @elseif($sub_form->sub_form_type==2) Login With User's Dropbox + Admin Auth
                                        @endif
                                    </td> -->
                                    <td>
                                        <center>	
                                            <a href="/show-sub_form/{{$sub_form->id}}" title="Show Sub form">
                                                <i class="fa fa-eye" style="color:#28a745; font-size:20px;"></i>
                                            </a>     
                                            <a href="/edit-sub_form/{{$sub_form->id}}" title="Edit Sub Form">
                                                <i class="fa fa-edit" style="color:#10707f; font-size:20px;"></i>
                                            </a> 
                                            <a data-id="{{ $sub_form->id }}" title="Delete Sub form" href="#" class="hapus" data-toggle="modal" data-target="#modal-hapus">
                                                <i class="fa fa-trash" style="color:#b21f2d; font-size:20px;"></i>
                                            </a>                         
                                        </center>												
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
                
                
             

            <!-- </form> -->
        </div>
    </div>
</div>
<a href="/create-sub-form/{{$form_id}}">
    <button style="position:fixed; right:3%; bottom:115px;" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
</a>

<a href="/project/{{$project_id}}/forms">
    <button style="position:fixed; right:3%; bottom:60px;"  data-toggle="modal" data-target="#export-modal" title="Export Project" class="btn btn-warning btn-circle" type="button"><i class="fa fa-arrow-left fa-lg"></i></button>
</a>


<div id="modal-hapus" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete This Sub Form?</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="delete-sub-form">
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
        var link = '/delete-sub-form/' + id;
        $('#delete-sub-form').attr("action", link);
    });
</script>
<script>
    $("#check-all").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
@endsection