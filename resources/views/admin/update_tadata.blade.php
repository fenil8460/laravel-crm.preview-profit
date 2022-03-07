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
                        <label class="col-lg-3 col-form-label form-control-label">Ticker</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="ticker" name="ticker"
                                value="<?php echo $tadata[0]->Ticker; ?>" placeholder="Ticker"
                                title="Please give ticker name" style="text-transform:uppercase" required>
                            <span id="ticker-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Company Name</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="CompanyName" name="CompanyName"
                                value="<?php echo $tadata[0]->CompanyName; ?>" placeholder="Company Name"
                                title="Please give Company name" required>
                            <span id="CompanyName-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Listed at</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="Listed_at" name="Listed_at"
                                value="<?php echo $tadata[0]->Listed_at; ?>" placeholder="Listed at"
                                title="Please give Listed_at" required style="text-transform:uppercase">
                            <span id="Listed_at-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">OnDate</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="date" id="OnDate" name="OnDate"
                                value="<?php echo $tadata[0]->OnDate; ?>" placeholder="OnDate"
                                title="Please give On Date" required>
                            <span id="OnDate-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Currency</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="Currency" name="Currency"
                                value="<?php echo $tadata[0]->Currency; ?>" placeholder="Currency"
                                title="Please give Currency type" required>
                            <span id="Currency-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">AtPrice</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="AtPrice" name="AtPrice"
                                value="<?php echo $tadata[0]->AtPrice; ?>" placeholder="AtPrice"
                                title="Please give At Price" required>
                            <span id="AtPrice-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">CurrentPrice</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="CurrentPrice" name="CurrentPrice"
                                value="<?php echo $tadata[0]->CurrentPrice; ?>" placeholder="Current Price"
                                title="Please give CurrentPrice">
                            <span id="CurrentPrice-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">SL_Exit</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="SL_Exit" name="SL_Exit"
                                value="<?php echo $tadata[0]->SL_Exit; ?>" placeholder="SL Exit"
                                title="Please give SL Exit">
                            <span id="SL_Exit-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Target Price</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="TargetPrice" name="TargetPrice"
                                value="<?php echo $tadata[0]->TargetPrice; ?>" placeholder="Target Price"
                                title="Please give Target Price">
                            <span id="TargetPrice-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Holding Period</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="HoldingPeriod" name="HoldingPeriod"
                                value="<?php echo $tadata[0]->HoldingPeriod; ?>" placeholder="Holding Period"
                                title="Please give HoldingPeriod">
                            <span id="HoldingPeriod-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Gain Loss</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="Gain_Loss" name="Gain_Loss"
                                value="<?php echo $tadata[0]->Gain_Loss; ?>" placeholder="Gain/Loss"
                                title="Please give Gain/Loss">
                            <span id="Gain_Loss-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Action</label>
                        <div class="col-lg-9">
                                <select name="Action" id="Action" class="form-control">
                                    <option value="BUY" {{($tadata[0]->Action == "BUY") ? 'selected' : ''}}>BUY</option>
                                    <option value="EXIT" {{($tadata[0]->Action == "EXIT") ? 'selected' : ''}}>EXIT</option>
                                </select>
                            <span id="Action-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row" id="action_date" style="display:none">
                        <label class="col-lg-3 col-form-label form-control-label">Exit Date</label>
                        <div class="col-lg-9">
                        <input class="form-control" type="date" id="Exit" name="exit_date"
                                value="{{isset($tadata[0]->exit_date) ? $tadata[0]->exit_date : date('Y-m-d')}}"  placeholder="Exit Date"
                                title="Please give Exit Date" required>
                            <span id="exitdate-error" class="hide" style="font-size: 16px;"></span>
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
 <!-- mail modal -->
        <div class="modal fade" id="mailmodal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Update Ticker Mail Sending</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="mail_form" class="form" role="form" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">To:</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="email_to" name="emial_to" value=""
                                        placeholder="To" title="Please give Email"
                                         required>
                                    <span id="ticker-error" class="hide" style="font-size: 16px;"></span>
                                    <div class="mob_view" id="tickerList" style="text-align: -webkit-center;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">BCC:</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="email_bcc" name="email_bcc" value=""
                                        placeholder="BCC" title="Please give Email" required>
                                    <span id="CompanyName-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Subject:</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="subject" name="subject" value=""
                                        placeholder="Subject" title="Please give Subject" required>
                                    <span id="Listed_at-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <input class="form-control" type="hidden" id="email_ticker_name" name="email_ticker_name" value=""
                                        placeholder="Subject" title="Please give Subject" required>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Body:</label>
                                <div class="col-lg-9">
                                <textarea cols="10" rows="5" class="form-control" id="email_body" name="email_body" value=" " placeholder="Give some message"></textarea>
                                    <span id="Listed_at-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>

                            <div class="form-group row modal-footer">
                                <div class="col-sm-12" style="text-align: center;">
                                    <input type="button" class="btn btn-info" id="mail-send-btn" value=" Send"
                                        style="border: 1px solid #d0efff;">
                                </div>
                                <div class="col-md-12">
                                    <span id="save-errors" class="hide" style="font-size: 16px;"></span>
                                    <span id="save-btn-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end mailmodal -->
    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {
        $(document).on('click', '#mail-send-btn', function(e) {
            var thisloadEle = $(this);
            mailSend(thisloadEle);
        });
        function mailSend(thisBtn) {
              $("#mail-send-btn").prop("disabled", true);
        $('#save-errors').removeClass('hide').html(
            '<b style="color: green;text-align: center;">Processing...</b>');
            var thisButton = $(thisBtn);
            var postUrl = "/email_sendig";
            var to = $("#email_to").val();
            var bcc = $("#email_bcc").val();
            var subject = $("#subject").val();
            var companyName = $("#email_body").val();
            var formData = $('#mail_form').serialize();
            $.ajax({
                url: postUrl,
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#save-errors').html(response.message).css({
                        "color": "green",
                        "font-weight": "bold"
                    });
                    window.location = response.redirect_url;
                },
                
            });
        }
    });
    $(function() {
        var onloadExit = $('select[name=Action] option').filter(':selected').val();
        if(onloadExit == "EXIT"){
            $("#action_date").css('display','block');
        }
        $('#Action').on('change',function(){
        var actionval = $('select[name=Action] option').filter(':selected').val();
            if(actionval == "EXIT"){
                $("#Gain_Loss").val("Realised");
                $("#action_date").css('display','block');
            }else{
                $("#Gain_Loss").val("Unrealised");
                $("#action_date").css('display','none');
            }
        })
        var getaction = $('#Action').val();
        var slprice = $('#SL_Exit').val();
        $(document).on('click', '#save-btn1', function(e) {
            var thisEle = $(this);
            save(thisEle);
                    
        });
        function save(thisBtn) {
            var thisButton = $(thisBtn);
            var fullNameEle = $('#ticker');
            var fullCompanyNameEle = $('#CompanyName');
            var fullListed_atEle = $('#Listed_at');
            var fullAtPriceEle = $('#AtPrice');
            var fulldateEle = $('#OnDate');
            var sl_exitEle = $('#SL_Exit');
            var actionEle = $('#Action');
            
           
            if (fullNameEle.val() != '') {
                if (!validateFirstLastName(fullNameEle)) {
                    $('#ticker-error').removeClass('hide').text('Enter valid ticker.').css({
                        "color": "#FF5733"
                    });
                    fullNameEle.addClass('has-error');
                    return false;
                } else {
                    var len = fullNameEle.val().length;
                    if (parseInt(len) < 1) {
                        $("#ticker-error").removeClass('hide').text('Enter at least 1 characters.').css({
                            "color": "#FF5733"
                        });
                        fullNameEle.addClass('has-error');
                        return false;
                    }
                }
                $("#ticker-error").addClass('hide').text('');
                fullNameEle.removeClass('has-error');
            } else {
                $("#ticker-error").removeClass('hide').text('Please enter name.').css({
                    "color": "#FF5733"
                });
                $('#ticker-error').addClass('has-error');
                return false;
            }
            if (fullCompanyNameEle.val() != '') {
                    var len = fullCompanyNameEle.val().length;
                    if (parseInt(len) < 1) {
                        $("#CompanyName-error").removeClass('hide').text('Enter at least 1 characters.').css({
                            "color": "#FF5733"
                        });
                        fullCompanyNameEle.addClass('has-error');
                        return false;
                    
                }
                $("#CompanyName-error").addClass('hide').text('');
                fullCompanyNameEle.removeClass('has-error');
            } else {
                $("#CompanyName-error").removeClass('hide').text('Please enter  Company Name.').css({
                    "color": "#FF5733"
                });
                $('#CompanyName-error').addClass('has-error');
                return false;
            }
            if (fullListed_atEle.val() != '') {
                if (!validateFirstLastName(fullListed_atEle)) {
                    $('#Listed_at-error').removeClass('hide').text('Enter valid Listed_at Name.').css({
                        "color": "#FF5733"
                    });
                    fullListed_atEle.addClass('has-error');
                    return false;
                } else {
                    var len = fullListed_atEle.val().length;
                    if (parseInt(len) < 1) {
                        $("#Listed_at-error").removeClass('hide').text('Enter at least 1 characters.').css({
                            "color": "#FF5733"
                        });
                        fullListed_atEle.addClass('has-error');
                        return false;
                    }
                }
                $("#Listed_at-error").addClass('hide').text('');
                fullListed_atEle.removeClass('has-error');
            } else {
                $("#Listed_at-error").removeClass('hide').text('Please enter Listed_at Name.').css({
                    "color": "#FF5733"
                });
                $('#Listed_at-error').addClass('has-error');
                return false;
            }
            if (fulldateEle.val() != '') {
              
                    var len = fulldateEle.val().length;
                    if (parseInt(len) < 1) {
                        $("#OnDate-error").removeClass('hide').text('Enter at least 1 characters.').css({
                            "color": "#FF5733"
                        });
                        fulldateEle.addClass('has-error');
                        return false;
                    
                    }
                $("#OnDate-error").addClass('hide').text('');
                fulldateEle.removeClass('has-error');
            } else {
                $("#OnDate-error").removeClass('hide').text('Please enter On Date.').css({
                    "color": "#FF5733"
                });
                $('#OnDate-error').addClass('has-error');
                return false;
            }
            if (fullAtPriceEle.val() != '') {
                    if (! $.isNumeric( fullAtPriceEle.val())) {
                        $("#AtPrice-error").removeClass('hide').text('Enter Digit Only.').css({
                            "color": "#FF5733"
                        });
                        fullAtPriceEle.addClass('has-error');
                        return false;
                    }
                $("#AtPrice-error").addClass('hide').text('');
                fullAtPriceEle.removeClass('has-error');
            } else {
                $("#AtPrice-error").removeClass('hide').text('Please enter AtPrice .').css({
                    "color": "#FF5733"
                });
                $('#AtPrice-error').addClass('has-error');
                return false;
            }
            if("exit" === actionEle.val().toLowerCase()){
                if (sl_exitEle.val() === ''){
                     $("#Action-error").removeClass('hide').text('SL_Exit is required when Action is Exit.').css({
                        "color": "#FF5733"
                    });
                    actionEle.addClass('has-error');
                    console.log(actionEle);
                    return false; 
                }     
            }
            $("#Action-error").addClass('hide').text('');
            actionEle.removeClass('has-error');
               
            
            var formData = $('#form_update').serialize();
            if (thisButton.data('requestRunning')) {
                return;
            }
            thisButton.data('requestRunning', true);
            thisButton.prop("disabled", true);
         $("#save-btn").prop("disabled", true);
            $('#save-error').removeClass('hide').html(
                '<b style="color: green;text-align: center;">Processing...</b>');
            var postUrl = "/edit/<?php echo $tadata[0]->id; ?>";
            // var postUrl = "/delete/{id}";
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
                        // window.location = response.redirect_url;
                        get_email();
                    $('#email_to').val(response.to);
                    $('#subject').val(response.subject);
                    $('#email_ticker_name').val(response.ticker);
                    $('#email_body').val(response.companyname);
                    var actionval = $('#Action').val();
                    var slpriceval = $('#SL_Exit').val();
                    if(getaction != actionval && actionval == 'EXIT'){
                            $('.model2').fadeOut();
                            $('#mailmodal').modal();
                    }
                    else{window.location = response.redirect_url;}
                    } else {
                        $('#save-btn-error').html(response.message).css({
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
        function get_email() {
            var postUrl = "/email_bcc_loading";
            // var postUrl = "/delete/{id}";
    
            $.ajax({
                url: postUrl,
                type: "GET",
                dataType: "json",
                success: function(response, textStatus, jqXHR) {
                    console.log(response); 
                    if (response.status == "OKBCC") {
                        $('#email_bcc').val(response.message);
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
                }
            });
        }
    });
    function validateFirstLastName(element) {
        var firstLastName = element.val();
        var pattern = /^[a-zA-Z_]+(?:[a-zA-Z\.\-\'\s]+)*$/;
        return pattern.test(firstLastName);
    }
</script>
<script>
$('.close').click(function() {
    $('.model2').fadeOut();
    window.location.href = "{{('/')}}";
});
</script>
<!-- Footer -->
@include('admin.footer')