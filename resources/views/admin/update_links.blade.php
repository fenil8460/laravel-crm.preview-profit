@include('admin.header')
<div class="model2" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Useful link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/useful_links/edit/<?php echo $useful_links[0]->id; ?>" method="post" class="form" role="form"
                    autocomplete="off">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Title</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="title" name="title"
                                value="<?php echo $useful_links[0]->title; ?>" placeholder="title"
                                title="Please give title name" required>
                            <span id="title-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">URL</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="url" name="url"
                                value="<?php echo $useful_links[0]->url; ?>" placeholder="url"
                                title="Please give url" required>
                            <span id="url-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Description</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="description" name="description"
                                value="<?php echo $useful_links[0]->description; ?>" placeholder="description"
                                title="Please give description" required>
                            <span id="description-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Sequence</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="sequence" name="sequence"
                                value="<?php echo $useful_links[0]->sequence; ?>" placeholder="sequence"
                                title="Please give Sequence" required>
                            <span id="sequence-error" class="hide" style="font-size: 16px;"></span>
                        </div>
                    </div>
                    <div class="form-group row modal-footer">
                        <div class="col-sm-12" style="text-align: center;">
                            <input type="submit" class="btn btn-info" id="save-btn" value="Update"
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
<script>

$('.close').click(function() {
 $('.model2').fadeOut();
 window.location.href = "{{('/useful_links')}}";
});
</script>
<!-- Footer -->
@include('admin.footer')