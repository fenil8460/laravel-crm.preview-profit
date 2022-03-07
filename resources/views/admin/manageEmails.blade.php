@extends('admin.layout')

@section('content')
<meta name="_token" content="{{ csrf_token() }}">
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
        display: none;
        text-align: right;
    }
    .dataTables_wrapper .dataTables_length {
    float: left;
    display: none;
    }
</style>
<div id="ta" class="container text-center">
    <h3>Manage Emails</h3>
        <div class="col-md-3 input-group pull-right">
           
            <input type="text" name="search" id="search" class="form-control"/>
            <span type="button" class="input-group-addon" id="search_error">
                <i class="fa fa-search"></i>
            </span>
        </div>
    {{-- <div class="form-group pull-right">
        <input type="text" class="form-controller" id="search" name="search"/>
    </div> --}}
        
</div>
<!-- <div class="table_div">
    <table id="example" class="display" style="width:100%">

    <thead>
            <tr>
                <th> <a href="" class="	glyphicon glyphicon-plus-sign" data-toggle="modal" data-target="#exampleModalCenter">Add</a></th>
            </tr>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>DoNotSend</th>
                <th>Unsubscribe</th>
                <th>Created At</th>

            </tr>
        </thead>
            <tbody>
                @if(!empty($data))
                @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->email }}</td>
                    @if ($d->DoNotSend == 0) 
                    <td><input type="checkbox" name="donotbtn[]"  onClick="Javascript:window.location.href = '/donotsend/on/{{ $d->id }}';"></td>
                    @else
                    <td><input type="checkbox" name="donotbtn[]"  onClick="Javascript:window.location.href = '/donotsend/off/{{ $d->id }}';" checked=""></td>
                    @endif
                  
                    <td>{{ $d->Unsubscribe }}</td>
                    <td>{{ date('d-M-y', strtotime($d->created_at)) }}</td>
                    <td><a href='/edit_email/{{ $d->id }}' data-toggle="modal1" style="font-size: 18px;">🖉</a></td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10">There are no data.</td>
                </tr>
                @endif
            </tbody>
    </table>
</div> -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> <a href="" class="	glyphicon glyphicon-plus-sign" data-toggle="modal" data-target="#exampleModalCenter">Add</a></th>
            </tr>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>DoNotSend</th>
                <th>Unsubscribe</th>
                <th>Created At</th>

            </tr>
        </thead>
            <tbody>
                @if(!empty($data))
                @foreach ($data as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->email }}</td>
                    @if ($d->DoNotSend == 0) 
                    <td><input type="checkbox" name="donotbtn[]"  onClick="Javascript:window.location.href = '/donotsend/on/{{ $d->id }}';"></td>
                    @else
                    <td><input type="checkbox" name="donotbtn[]"  onClick="Javascript:window.location.href = '/donotsend/off/{{ $d->id }}';" checked=""></td>
                    @endif
                  
                    <td>{{ $d->Unsubscribe }}</td>
                    <td>{{ date('d-M-y', strtotime($d->created_at)) }}</td>
                    <td><a href='/edit_email/{{ $d->id }}' data-toggle="modal1" style="font-size: 18px;">🖉</a></td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10">There are no data.</td>
                </tr>
                @endif
            </tbody>
        </table>
        {{$data->links()}}
    <div id="ta" class="container text-center">
        <div class="col-md-12 pt-20 pb-20">
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add new Email</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="email_form" class="form" role="form" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="text" id="name" name="name" value="" placeholder="name" title="Please give name name" style="text-transform:uppercase" required style="text-transform:uppercase">
                                        <span id="name-error" class="hide" style="font-size: 16px;"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" type="text" id="email" name="email" value="" placeholder="email" title="Please give email name" style="text-transform:uppercase" required style="text-transform:uppercase">
                                        <span id="email-error" class="hide" style="font-size: 16px;"></span>
                                    </div>
                                </div>
                                <div class="form-group row modal-footer">
                                    <div class="col-sm-12" style="text-align: center;">
                                        <input type="button" class="btn btn-info" id="save-btn" value=" Add" style="border: 1px solid #d0efff;">
                                    </div>
                                    <div class="col-md-12">
                                        <span id="save-error" class="hide" style="font-size: 16px;"></span>
                                        <span id="save-btn-error" class="hide" style="font-size: 16px;"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- end model popup for creating new email --}}
<!-- save/create email data -->
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {

        $(document).on('click', '#save-btn', function(e) {
            var thisEle = $(this);
            save(thisEle);
        });

        function save(thisBtn) {
            var thisButton = $(thisBtn);
            var formData = $('#email_form').serialize();
            if (thisButton.data('requestRunning')) {
                return;
            }
            thisButton.data('requestRunning', true);
            thisButton.prop("disabled", true);

            $('#save-error').removeClass('hide').html(
                '<b style="color: green;text-align: center;">Processing...</b>');

            var postUrl = "/save_email";

            $.ajax({
                url: postUrl
                , type: "POST"
                , dataType: "JSON"
                , data: formData
                , success: function(response, textStatus, jqXHR) {
                    // console.log(response);return false;
                    if (response.status == "OK") {
                        $('#save-error').html(response.message).css({
                            "color": "green"
                            , "font-weight": "bold"
                        });
                        window.location = response.redirect_url;
                    } else {
                        $('#save-error').html(response.message).css({
                            "color": "red"
                            , "font-weight": "bold"
                        });
                    }
                }
                , error: function(jqXHR, textStatus, errorThrown) {
                    $('#save-btn-error').html('Failed to Process!!').css({
                        "color": "red"
                        , "font-weight": "bold"
                    });
                }
                , complete: function() {
                    thisButton.data('requestRunning', false);
                    thisButton.prop("disabled", false);
                }
            });
        }
    });

</script>
<!-- end save email data -->
<script type="text/javascript">
    $('#search').on('blur',function(){
    $value=$(this).val();
        $.ajax({
            type : 'get',
            url : '{{route('fetchData')}}',
            data:{'search':$value},
            success:function(data){
                $('tbody').html(data);
            }
        });
    });
    </script>
    <script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
    

@stop