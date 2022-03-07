@extends('admin.layout')

@section('content')
<style>
.stock-search-result {
    position: absolute;
    z-index: 999;
    margin-top: -3px;
    background-color: #fff;
    width: auto;
    max-height: 0;
    overflow: auto;
    border-bottom-left-radius: 7px;
    border-bottom-right-radius: 7px;
    box-shadow: 0 20px 15px 0 rgb(0 0 0 / 9%);
    transition: all .4s;
}
a.ticker_name:hover {
    background-color: rgb(140, 217, 240) !important;
    background: rgb(140, 217, 240) !important;
    text-decoration: none !important;
}
.ticker_res:hover {
    background-color: rgb(140, 217, 240) !important;
}
.ticker_res {
    cursor: pointer;
    display: flex;
    align-items: center;
    width: 100%;
    padding: .5em 1em;
    transition: all .3s;
    text-align: left;
}
.currency{display: none;}
.listed-at{display: none;}
</style>
<!-- Container (The Band Section) -->
<div id="ta" class="container text-center">
    <h3>Technical Analysis</h3>
    <div class="col-md-12 pt-20 pb-20">

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create new Technical Analysis Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="tadata-form" class="form" role="form" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Ticker</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="ticker" name="ticker" value=""
                                        placeholder="Ticker" title="Please give ticker name"
                                        style="text-transform:uppercase" required style="text-transform:uppercase">
                                    <span id="ticker-error" class="hide" style="font-size: 16px;"></span>
                                    <div class="mob_view" id="tickerList" style="text-align: -webkit-center;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Company Name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="CompanyName" name="CompanyName" value=""
                                        placeholder="Company Name" title="Please give Company name" required>
                                    <span id="CompanyName-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Listed at</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="Listed_at" name="Listed_at" value=""
                                        placeholder="Listed at" title="Please give Listed_at" required
                                        style="text-transform:uppercase">
                                    <span id="Listed_at-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">OnDate</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="date" id="OnDate" name="OnDate" value="{{date('Y-m-d')}}"
                                        placeholder="OnDate" title="Please give On Date" required>
                                    <span id="OnDate-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Currency</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="Currency" name="Currency" value=""
                                        placeholder="Currency" title="Please give Currency type" required>
                                    <span id="Currency-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Buy Price</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="AtPrice" name="AtPrice" value=""
                                        placeholder="AtPrice" title="Please give At Price" required>
                                    <span id="AtPrice-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">CurrentPrice</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="CurrentPrice" name="CurrentPrice"
                                        value="" placeholder="Current Price" title="Please give CurrentPrice" required
                                        style="text-transform:uppercase">
                                    <span id="CurrentPrice-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">SL_Exit</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="SL_Exit" name="SL_Exit" value=""
                                        placeholder="SL Exit" title="Please give SL Exit" required>
                                    <span id="SL_Exit-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Target Price</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="TargetPrice" name="TargetPrice" value=""
                                        placeholder="Target Price" title="Please give Target Price" required>
                                    <span id="TargetPrice-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Holding Period</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="HoldingPeriod" name="HoldingPeriod"
                                        value="" placeholder="Holding Period" title="Please give HoldingPeriod">
                                    <span id="HoldingPeriod-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Gain Loss</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" id="Gain_Loss" name="Gain_Loss" value=""
                                        placeholder="Gain/Loss" title="Please give Gain/Loss">
                                    <span id="Gain_Loss-error" class="hide" style="font-size: 16px;"></span>
                                </div>
                            </div>
                           <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Action</label>
                        <div class="col-lg-9">
                                <select name="Action" id="Action" class="form-control">
                                    <option value="BUY">BUY</option>
                                    <option value="EXIT">EXIT</option>
                                </select>
                            <span id="Action-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row" id="action_date" style="display:none">
                        <label class="col-lg-3 col-form-label form-control-label">Exit Date</label>
                        <div class="col-lg-9">
                        <input class="form-control" type="date" id="Exit" name="exit_date"
                                value="{{date('Y-m-d')}}"  placeholder="Exit Date"
                                title="Please give Exit Date" required>
                            <span id="exitdate-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>


                            <div class="form-group row modal-footer">
                                <div class="col-sm-12" style="text-align: center;">
                                    <input type="button" class="btn btn-info" id="save-btn" value=" Add"
                                        style="border: 1px solid #d0efff;">
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
        <!-- mail modal -->
        <div class="modal fade" id="mailmodal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">New Ticker Mail Sending</h5>
                        <button type="button" class="close mail_close" data-dismiss="modal" aria-label="Close">
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
    </div>
</div>

<!-- save ta data -->
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
        var actionvalue = $('select[name=Action] option').filter(':selected').val();
            if(actionvalue == "EXIT"){
                $("#Gain_Loss").val("Realised");
            }else{
                $("#Gain_Loss").val("Unrealised");
            }
    $(document).on('click', '#save-btn', function(e) {
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
            if (!$.isNumeric(fullAtPriceEle.val())) {
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
        if ("exit" === actionEle.val().toLowerCase()) {
            if (sl_exitEle.val() === '') {
                $("#Action-error").removeClass('hide').text('SL_Exit is required when Action is Exit.').css({
                    "color": "#FF5733"
                });
                actionEle.addClass('has-error');
                // console.log(actionEle);
                return false;
            }
        }
        $("#Action-error").addClass('hide').text('');
        actionEle.removeClass('has-error');
        var formData = $('#tadata-form').serialize();
        if (thisButton.data('requestRunning')) {
            return;
        }
        thisButton.data('requestRunning', true);
        thisButton.prop("disabled", true);
         $("#save-btn").prop("disabled", true);
        $('#save-error').removeClass('hide').html(
            '<b style="color: green;text-align: center;">Processing...</b>');
        var postUrl = "/save";
        // var postUrl = "/delete/{id}";
        $('#exampleModalCenter').hide();
        $.ajax({
            url: postUrl,
            type: "POST",
            dataType: "JSON",
            data: formData,
            success: function(response, textStatus, jqXHR) {
                // console.log(response);return false;
                if (response.status == "OK") {
                    $('#save-error').html(response.message).css({
                        "color": "green",
                        "font-weight": "bold"
                    });
                    //$("#save-btn").prop("disabled", false);
                    //window.location = response.redirect_url;
                    get_email();
                    $('#email_to').val(response.to);
                    $('#subject').val(response.subject);
                    $('#email_ticker_name').val(response.ticker);
                    $('#email_body').val(response.companyname);
                    $('#mailmodal').modal();
                } else {
                    $('#save-btn-error').html(response.message).css({
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
$('.mail_close').click(function() {
    $('#mailmodal').fadeOut();
    window.location.href = "{{('/')}}";
});
</script>
<!-- end save ta data -->
<div class="table_div">
    <table id="example" class="display" style="width:100%">

        <thead>
            <tr>
                <th> <a href="" class="	glyphicon glyphicon-plus-sign" data-toggle="modal"
                        data-target="#exampleModalCenter">Add</a></th>

            </tr>

            <tr>

                <!-- <th>Id</th> -->
                <th>Ticker</th>
                <th>Company Name</th>
                <th>Listed At</th>
                <th>On date</th>
                <th>Buy Price</th>
                <th>Currency</th>
                <th>Current Price</th>
                <th>SL Exit</th>
                <!-- <th>Target Price</th>
                <th>Holding Period</th> -->
                <th>Gain Loss</th>
                <th>Action</th>
                <th>Exit Date</th>
                <th>Last Saved</th>
                <th>

                </th>



            </tr>
        </thead>
        <tbody>
            @if(!empty($tadata) && $tadata->count())
            @foreach ($tadata as $data)
            <tr>
                <!-- <td>{{ $data->id }}</td> -->
                <td>{{ $data->Ticker }}</td>
                <td>{{ $data->CompanyName }}</td>
                <td>{{ $data->Listed_at }}</td>
                <td>{{ $data->OnDate }}</td>
                <td>{{ $data->AtPrice }}</td>
                <td>{{ $data->Currency }}</td>
                <td>{{ $data->CurrentPrice }}</td>
                <td>{{ $data->SL_Exit }}</td>
               <!--  <td>{{ $data->TargetPrice }}</td>
                <td>{{ $data->HoldingPeriod }}</td> -->
                <td>{{ ucfirst(trans($data->Gain_Loss)) }}</td>
                <td>{{ $data->Action }}</td>
                <td>{{ $data->exit_date }}</td>

                <form action="/{{$data->status}}" method="post" id="statusForm{{$data->id}}">
                    @csrf
                    <!-- If status is 1 an unchecked checkbox -->
                    @if ($data->status == "hide")
                    <td>{{ date('d-M-y', strtotime($data->updated_at)) }}<a href="/{{$data->status}}/{{$data->id}}">
                            <span style="color:orangered;" class="glyphicon glyphicon-eye-close"></span>

                        </a>

                    </td>
                    <input name="id" type="hidden" value="{{$data->id}}">

                    @else
                    <td>{{ date('d-M-y', strtotime($data->updated_at)) }}<a href="/{{$data->status}}/{{$data->id}}">
                            <span class="glyphicon glyphicon-eye-open"></span>

                        </a>
                </form>

                </td>
                @endif
                <td>
                    <a href='/email_template/{{ $data->id }}' data-toggle="modal2" style="font-size: 18px;">&#9993;</a>
                    <a href='/edit/{{ $data->id }}' data-toggle="modal1" style="font-size: 18px;">🖉</a>
                    <a href='/delete/{{ $data->id }}'>❌</a>

                </td>

            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="10">There are no data.</td>
            </tr>
            @endif
        </tbody>

    </table>
    {{-- {!! $tadata->links() !!} --}}
</div>

<script>
$(document).ready(function() {
    $(".showhide").click(function(e) {
        e.preventDefault();
        $('tr.cancelled').toggle();
    });
});
var msg = "{{Session::get('alert')}}";
var exist = "{{Session::has('alert')}}";
if (exist) {
    alert(msg);
}
</script>
<script>
var currentRequest = null;
function test(ticker){
    var tickerName = $(ticker).children('.ticker_res').children('.tickerName').text().split('-')[0];
    var companyName = $(ticker).children('.ticker_res').children('.tickerName').text().split('-')[1];
    $("#ticker").val(tickerName);
    $("#CompanyName").val(companyName);
    $("#Currency").val($(ticker).children('.ticker_res').children('.currency').text());
    $("#Listed_at").val($(ticker).children('.ticker_res').children('.listed-at').text());
    var _token = $('input[name="_token"]').val();
    $.ajax({
        beforeSend: function() {
            if (currentRequest != null) {
                currentRequest.abort();
            }
        },
        url:"{{route('autocomplete.getprice')}}",
        method: "POST",
        data: {
            ticker: tickerName,
            _token: _token
        },
        success: function(data) {
               $('#CurrentPrice').val(data);
               $('#AtPrice').val(data);
        }
    });
}
$(function() {
    $('#ticker').val('');
    $('#ticker').keyup(function() {
        var query = $(this).val();
        if (query != '') {
            var _token = $('input[name="_token"]').val();
            currentRequest =  $.ajax({
                beforeSend: function() {
                    if (currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                url: "{{ route('autocomplete.fetch') }}",
                method: "POST",
                data: {query: query,_token: _token},
                success: function(data) {
                    $('#tickerList').fadeIn();
                    $('#tickerList').html(data);
                    sessionStorage.clear();
                }
            });
        }
    });
});
$(document).on('click', '#tickerList', function() {
    $('#tickerList').fadeOut();
});
$(function() {
    $(document).on('click', '#hidebtn', function(e) {
        var thisEle = $(this);
        save(thisEle);
    });
    
    function save(thisBtn) {
        if (thisButton.data('requestRunning')) {
            return;
        }
        thisButton.data('requestRunning', true);
        thisButton.prop("disabled", true);
        $('#save-error').removeClass('hide').html(
            '<b style="color: green;text-align: center;">Processing...</b>');
        var postUrl = "/hide";
        // var postUrl = "/delete/{id}";
        $.ajax({
            url: postUrl,
            type: "GET",
            dataType: "JSON",
            data: formData,
            success: function(response, textStatus, jqXHR) {
                // console.log(response);return false;
                if (response.status == "OK") {
                    $('#save-error').html(response.message).css({
                        "color": "green",
                        "font-weight": "bold"
                    });
                    window.location = response.redirect_url;
                } else {
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
@stop