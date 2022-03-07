@include('admin.header')
<div class="model2" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Technical Analysis Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="form_update" method="post" class="form" role="form"
                    autocomplete="off">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="email" name="email"
                                value="{{ $user_emails[0]->email }}" placeholder="Email"
                                title="Please give email address" required>
                            <span id="email-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row modal-footer">
                        <div class="col-sm-12" style="text-align: center;">
                            <input type="button" class="btn btn-info" id="save-btn1" value="Update"
                                style="border: 1px solid #d0efff;">
                        </div>
                        <div class="col-md-12">
                            <span id="save-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {

        $(document).on('click', '#save-btn1', function(e) {
            var thisEle = $(this);
            save(thisEle);
        });

        function save(thisBtn) {
            var thisButton = $(thisBtn);
            var emailEle = $('#email');
        
           

            var formData = $('#form_update').serialize();

            if (thisButton.data('requestRunning')) {
                return;
            }
            thisButton.data('requestRunning', true);
            thisButton.prop("disabled", true);

            $('#save-error').removeClass('hide').html(
                '<b style="color: green;text-align: center;">Processing...</b>');

            var postUrl = "/edit_email/<?php echo $user_emails[0]->id; ?>";

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
<script>
$('.close').click(function() {
    $('.model2').fadeOut();
    window.location.href = "{{('/manageEmails')}}";
});
</script>
<!-- Footer -->

@include('admin.footer')