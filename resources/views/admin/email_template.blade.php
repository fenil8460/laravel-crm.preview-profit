@include('admin.header')

<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script> --}}
<style>
    .modal-header, .modal-body {
    padding: 25px 10px;
}
.modal-dialog {
    width: 800px;
    margin: 70px auto;
}
.btn-warning{
background-color: red !important;
opacity: 1;
text-shadow: 0 0px 0 #000;
}
.btn-info{
background-color: lightblue !important;
}
</style>
<div class="model2" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create a Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="email_form" class="form-horizontal ajax-form" enctype="multipart/form-data" role="form" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="sender" class="col-sm-2 control-label">To:</label>

                        <div class="col-sm-10">
                            <input name="sender" type="text" class="form-control" id="sender" placeholder="To" required>
                            <span id="email-error" class="hide" style="margin-left: 6px"></span></div>
                    </div>
                    <div class="form-group">
                        <label for="bcc" class="col-sm-2 control-label">BCC:</label>
                        <div class="col-sm-10 btn-group-xs">
                            <input name="bcc" type="text" class="form-control" id="bcc" placeholder="BCC">
                            <button type="button" class="btn btn-info btn-load">Load mail</button> 
                            <span id="load_error"></span>
                            <span id="bcc-error" class="hide" style="margin-left: 6px"></span>
                        </div>
                        </div>
                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">Subject:</label>
                        <div class="col-sm-10">
                            <select name="subject" class="form-control" id="subject">
                            @foreach ($data_name as $data)
                                <option value="{{$data->Ticker}}-{{$data->CompanyName}}">{{$data->Ticker}}-{{$data->CompanyName}}</option>
                            @endforeach
                        </select>
                                <span id="subject-error" class="hide" style="margin-left: 6px"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Body:</label>
                        <div class="col-sm-10">
                            <textarea cols="10" rows="5" id="body" name="body" class="form-control" value=" " placeholder="Give some message"></textarea>
                        <span id="body-error" class="hide" style="margin-left: 6px"></span>
                        </div>
                    </div>
                    
                    <div class="pull-right btn-group btn-group-xs">
                        <button type="button" class="btn btn-info btn-send">Send</button> 
                        <span id="save-error"></span>
                        <span id="save-btn-error"></span>
                    </div>
                    <div class=" pull-right btn-group btn-group-xs">
                        <button type="button" class="close btn btn-warning" data-dismiss="modal" aria-label="Close">Discard</button> 
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        /* Validate Email Id */
        function validateEmailId(element) {
            var emailId = element.val();
            var pattern =
                /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            return pattern.test(emailId.trim());
        }
        

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {
        $(document).on('click', '.btn-send', function(e) {
            var thisEle = $(this);
            save(thisEle);
        });
        function save(thisBtn) {
            var thisButton = $(thisBtn);
            var senderEle = $('#sender');
            var bodyEle = $('#body');

            if (senderEle.val() != '') {
                if (!validateEmailId(senderEle)) {
                    $('#email-error').removeClass('hide').text('Please enter valid email id.').css({
                        "color": "#FF5733"
                    });
                    senderEle.addClass('has-error');
                    return false;
                }
                $("#email-error").addClass('hide').text('');
                senderEle.removeClass('has-error');
            } else {
                $("#email-error").removeClass('hide').text('Please enter email id.').css({
                    "color": "#FF5733"
                });
                senderEle.addClass('has-error');
                return false;
            }
            if (bodyEle.val() != '') {
                $("#body-error").addClass('hide').text('');
                bodyEle.removeClass('has-error');
            } else {
                $("#body-error").removeClass('hide').text('Please give some message').css({
                    "color": "#FF5733"
                });
                bodyEle.addClass('has-error');
                return false;
            }
            var formData = $('#email_form').serialize();
                    // console.log(formData);
    
            if (thisButton.data('requestRunning')) {
                return;
            }
            thisButton.data('requestRunning', true);
            thisButton.prop("disabled", true);
    
            $('#save-error').removeClass('hide').html(
                '<b style="color: green;text-align: center;"><i class="fa fa-spinner"></i></b>');
            // controller adderss
    
            var postUrl = "/email_template/{{ $id }}";
            // var postUrl = "/delete/{id}";
    
            $.ajax({
                url: postUrl,
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response, textStatus, jqXHR) {
                    console.log(response); 
                    if (response.status == "OK") {
                        $('#save-error').html(response.message).css({
                            "color": "green",
                            "font-weight": "bold"
                        });
                        window.location = response.redirect_url;
                    }
                   
                     else {
                        $('#save-error').html(response.message).css({
                            "color": "red",
                            "font-weight": "bold"
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#save-btn-error').html('Failed to Process!!').css({
                        "color": "red",
                        "font-weight": "bold"
                    });
                },
                complete: function() {
                    thisButton.data('requestRunning', false);
                    thisButton.prop("disabled", false);
                }
            });
        }
    });
    $(function() {
        $(document).on('click', '.btn-load', function(e) {
            var thisloadEle = $(this);
            save(thisloadEle);
        });
        function save(thisBtn) {
            var thisButton = $(thisBtn);
            if (thisButton.data('requestRunning')) {
                return;
            }
            thisButton.data('requestRunning', true);
            thisButton.prop("disabled", true);
    
            $('#load_error').removeClass('hide').html(
                '<b style="color: green;text-align: center;"><i class="fa fa-spinner"></i></b>');
            // controller adderss
    
            var postUrl = "/email_bcc_loading";
            // var postUrl = "/delete/{id}";
    
            $.ajax({
                url: postUrl,
                type: "GET",
                dataType: "json",
                success: function(response, textStatus, jqXHR) {
                    console.log(response); 
                    if (response.status == "OKBCC") {
                        $('#bcc').val(response.message);
                        $('#load_error').html('&#10003;').css({
                            "color": "green",
                            "font-weight": "bold"
                        });
                    }
                   
                     else {
                        $('#save-error').html(response.message).css({
                            "color": "red",
                            "font-weight": "bold"
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#save-btn-error').html('Failed to Process!!').css({
                        "color": "red",
                        "font-weight": "bold"
                    });
                },
                complete: function() {
                    thisButton.data('requestRunning', false);
                    thisButton.prop("disabled", false);
                }
            });
        }
    });
</script>

<script>
    $('.close').click(function() {
        $('.model2').fadeOut();
        window.location.href = "{{('/')}}";
    });
    </script>
{{-- <script>
    ClassicEditor
        .create(document.querySelector('#body'))
        .catch(error => {
            console.error(error);
        });

</script> --}}
<!-- Footer -->
@include('admin.footer')
