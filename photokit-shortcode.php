<?php
function wpb_pkeditor_shortcode()
{ ?>

    <div class="test">
        <div id="pkeditor_saveimage" style="display:none;">
            <button type="button" name="upload_file" id="uploadimage"><i class="fa fa-wordpress" aria-hidden="true"></i> Upload Image</button>



        </div>
        <div id="pkeditor">

        </div>
        <?php echo do_shortcode('[poiktimg_uploader]'); ?>
    </div>

<?php
}
// register shortcode
add_shortcode('pkeditor', 'wpb_pkeditor_shortcode');
