<?php
if(!class_exists('CPWPWM_ADMIN_PAGE')){
    class CPWPWM_ADMIN_PAGE{
        public function __construct(){
            add_action( 'admin_menu', array($this,'register_menu_page') );
            add_action('admin_enqueue_scripts', array($this,'my_enqueue'),1,2);
            add_action('admin_footer', array($this,'footer'),100,2);
            add_action( 'wp_ajax_CPWPWM_GET_SELECT_DATA', array($this,'rudr_get_posts_ajax_callback') );
            add_action( 'wp_ajax_CPWPWM_save_post_tyoe', array($this,'save_post_tyoe') );
            if ( isset( $_REQUEST['popup_download_data_id'] ) ) {
                add_action( 'admin_init', array($this,'popup_download_data') );
            }

        }
        public function recursive_sanitize_text_field($array) {
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = recursive_sanitize_text_field($value);
                }
                else {
                    $value = sanitize_text_field( $value );
                }
            }

            return $array;
        }
        public function save_post_tyoe(){

            $template_select = sanitize_text_field($_POST['template_select']);
            $popup_Title = sanitize_text_field($_POST['popup_Title']);
            $modal_body = wp_kses_post($_POST['modal_body']);
            $all_site = sanitize_text_field($_POST['all_site']);
            $home_page = sanitize_text_field($_POST['home_page']);
            $postType = $this->recursive_sanitize_text_field($_POST['postType']);
            $post =  $this->recursive_sanitize_text_field($_POST['post']);
            $showing = sanitize_text_field($_POST['showing']);
            $trigger = sanitize_text_field($_POST['trigger']);
            $trigger_info = sanitize_text_field($_POST['trigger_info']);

            $my_post = array(
                'post_title'    => wp_strip_all_tags( $popup_Title ),
                'post_content'  => $modal_body,
                'post_type'  => 'cpwpwm_post',
                'post_status'   => 'publish',
            );
            $post_id = wp_insert_post( $my_post );
            $data = array();
            if(!empty($template_select) && file_exists( CPWPWM_DIR . '/templates/'.$template_select.'/data.php')){
                require_once( CPWPWM_DIR . '/templates/'.$template_select.'/data.php' );
            }
            foreach ($data as $key=>$val){
                update_post_meta($post_id,$key,$val);
            }
            update_post_meta($post_id,'template_select',$template_select);
            update_post_meta($post_id,'all_site',$all_site);
            update_post_meta($post_id,'home_page',$home_page);
            update_post_meta($post_id,'post_types',$postType);
            update_post_meta($post_id,'post_custom',$post);
            update_post_meta($post_id,'showing',$showing);
            update_post_meta($post_id,'trigger',$trigger);
            update_post_meta($post_id,'trigger_info',$trigger_info);
            update_post_meta($post_id,'popup_status','published');
            
            echo $post_id;
            die;
        }
        public function rudr_get_posts_ajax_callback(){

            // we will pass post IDs and titles to this array
            $return = array();

            // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
            $search_results = new WP_Query( array(
                's'=> $_GET['q'],
                'post_status' => 'publish',
                'posts_per_page' => 50
            ) );
            if( $search_results->have_posts() ) :
                while( $search_results->have_posts() ) : $search_results->the_post();
                    // shorten the title a little
                    $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
                    $return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
                endwhile;
            endif;
            echo json_encode( $return );
            die;
        }

        public function footer(){
            if(!isset($_GET['page'])) {
                return true;
            }
            if($_GET['page'] != 'CPWPWM_DATA_update' && $_GET['page'] != 'CPWPWM_DATA'){
                return true;
            }
            $dirs = array_filter(glob(CPWPWM_DIR.'/templates/*'), 'is_dir');


            ?>
                <style>
                    .radio_sellected [type=radio] {
                        position: absolute;
                        opacity: 0;
                        width: 0;
                        height: 0;
                    }
                    /* IMAGE STYLES */
                    .radio_sellected [type=radio] + img {
                        cursor: pointer;
                    }
                    /* CHECKED STYLES */
                    .radio_sellected [type=radio]:checked + img {
                        outline: 2px solid #0f89ea;
                    }
                    .radio_sellected input[type="radio"] {
                        width: auto !important;
                    }

                    .radio_sellected_pos [type=radio] {
                        position: absolute;
                        opacity: 0;
                        width: 0;
                        height: 0;
                    }
                    /* IMAGE STYLES */
                    .radio_sellected_pos [type=radio] + img {
                        cursor: pointer;
                    }
                    /* CHECKED STYLES */
                    .radio_sellected_pos [type=radio]:checked + img {
                        background: #0a87c3;
                    }
                    .radio_sellected_pos input[type="radio"] {
                        width: auto !important;
                    }
                    img.radio_image_pos {
                        padding: 5px;
                        border: 1px solid #f4f7f2;
                    }

                    img.radio_image_icon {
                        width: 135px;
                        margin: 0px 20px 0px 0px;
                    }
                    img.radio_image_slid {
                        width: 165px;
                        margin: 0px 20px;
                    }
                    img.radio_image_pos {
                        width: auto;
                        margin: 0px 2px;
                    }


                    .filter {
                        width: 100%;
                    }

                    .filter ul.checkbox {
                        margin: 0;
                        padding: 0;
                        margin-left: 20px;
                        list-style: none;
                    }
                    .filter ul.checkbox li input {
                        margin-right: .25em;
                    }
                    .filter ul.checkbox li {
                        border: 1px transparent solid;
                        display:inline-block;
                        width:40%;
                    }
                    .filter ul.checkbox li label {
                        margin-left:;
                    }
                    .filter ul.checkbox li:hover, ul.checkbox li.focus {
                    }

                    .select2_post_type span.select2.select2-container.select2-container--default.select2-container--focus {
                        width: 80% !important;
                    }
                    span.select2_post_type .select2-container {
                        width: 80% !important;
                    }






                    /* Absolute Center Spinner */
                    .loading {
                        position: fixed;
                        z-index: 999;
                        height: 2em;
                        width: 2em;
                        overflow: show;
                        margin: auto;
                        top: 0;
                        left: 0;
                        bottom: 0;
                        right: 0;
                    }

                    /* Transparent Overlay */
                    .loading:before {
                        content: '';
                        display: block;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

                        background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
                    }

                    /* :not(:required) hides these rules from IE9 and below */
                    .loading:not(:required) {
                        /* hide "loading..." text */
                        font: 0/0 a;
                        color: transparent;
                        text-shadow: none;
                        background-color: transparent;
                        border: 0;
                    }

                    .loading:not(:required):after {
                        content: '';
                        display: block;
                        font-size: 10px;
                        width: 1em;
                        height: 1em;
                        margin-top: -0.5em;
                        -webkit-animation: spinner 150ms infinite linear;
                        -moz-animation: spinner 150ms infinite linear;
                        -ms-animation: spinner 150ms infinite linear;
                        -o-animation: spinner 150ms infinite linear;
                        animation: spinner 150ms infinite linear;
                        border-radius: 0.5em;
                        -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
                        box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
                    }

                    /* Animation */

                    @-webkit-keyframes spinner {
                        0% {
                            -webkit-transform: rotate(0deg);
                            -moz-transform: rotate(0deg);
                            -ms-transform: rotate(0deg);
                            -o-transform: rotate(0deg);
                            transform: rotate(0deg);
                        }
                        100% {
                            -webkit-transform: rotate(360deg);
                            -moz-transform: rotate(360deg);
                            -ms-transform: rotate(360deg);
                            -o-transform: rotate(360deg);
                            transform: rotate(360deg);
                        }
                    }
                    @-moz-keyframes spinner {
                        0% {
                            -webkit-transform: rotate(0deg);
                            -moz-transform: rotate(0deg);
                            -ms-transform: rotate(0deg);
                            -o-transform: rotate(0deg);
                            transform: rotate(0deg);
                        }
                        100% {
                            -webkit-transform: rotate(360deg);
                            -moz-transform: rotate(360deg);
                            -ms-transform: rotate(360deg);
                            -o-transform: rotate(360deg);
                            transform: rotate(360deg);
                        }
                    }
                    @-o-keyframes spinner {
                        0% {
                            -webkit-transform: rotate(0deg);
                            -moz-transform: rotate(0deg);
                            -ms-transform: rotate(0deg);
                            -o-transform: rotate(0deg);
                            transform: rotate(0deg);
                        }
                        100% {
                            -webkit-transform: rotate(360deg);
                            -moz-transform: rotate(360deg);
                            -ms-transform: rotate(360deg);
                            -o-transform: rotate(360deg);
                            transform: rotate(360deg);
                        }
                    }
                    @keyframes spinner {
                        0% {
                            -webkit-transform: rotate(0deg);
                            -moz-transform: rotate(0deg);
                            -ms-transform: rotate(0deg);
                            -o-transform: rotate(0deg);
                            transform: rotate(0deg);
                        }
                        100% {
                            -webkit-transform: rotate(360deg);
                            -moz-transform: rotate(360deg);
                            -ms-transform: rotate(360deg);
                            -o-transform: rotate(360deg);
                            transform: rotate(360deg);
                        }
                    }
					
                    .CodeMirror {
                        border: 1px solid #ddd;
                    }
                    pre.CodeMirror-line {
                        padding-left: 35px;
                    }
                    .CodeMirror-gutters {
                        width: 30px;
                        left: 0px !important;
                    }
                    button.close.close-modals {
                        position: absolute;
                        right: 5px;
                    }
                    input.save-popup {
                        white-space: nowrap;
                        background: #28a745;
                        color: #fff;
                        text-decoration: none;
                        text-shadow: none;
                        border:none;
                        padding: 10px;
                        width: 100%;
                    }
                    #modal_fix{
                        width: 100%;
                        left: -15px;
                    }
                    div#submitModal label {
                        font-weight: 600;
                    }
                    div#submitModal h5 {
                        font-size: 14px;
                        font-weight: 600;
                    }
                    div#submitModal ul label {
                        font-weight: 500;
                    }
					
					
					
					.card{
						border:1px solid rgb(0 0 0 / 78%)
					}
					.card-body{
						background-color:#292929;
						color:white;
						line-height:30px
					}
					
					.card-header{
						background-color:#000000;
						color:#E5E5E5;
					}
					
					.select2-container--default .select2-selection--multiple .select2-selection__choice
					{
						background-color:#292929;
						color:white;
					}
                    .short_style {
                        margin: 0px 0px 20px;
                        text-align: inherit;
                        padding: 0px 10px;
                        color: darkgray;
                        font-weight: 700;
                    }
					.btn-link{
					color:white;
					text-decoration:none;
					font-size:18px
					}
					
					.btn-link:hover{
						color:#ffffff;
						text-decoration:none;
					}
                    .Go_back_save_popup{
                        white-space: nowrap;
                        background: #000000;
                        color: #fff;
                        text-decoration: none;
                        text-shadow: none;
                        border: none;
                        padding: 10px 8px;
                        display: inline-block;
                        text-align: center;
                    }
                </style>
            <script>
                var data_array = {
                    <?php
                    $iLoad = 1;
                    foreach ($dirs as $dir){
                    $template_folder = basename($dir);
                    include(CPWPWM_DIR.'/templates/'.$template_folder.'/data.php');
                    ?>
                    '<?php echo $template_folder;?>':{'content':'<?php echo isset($data['post_content'])?preg_replace("/\s+|\n+|\r/", ' ', $data['post_content']):'';?>','title':'<?php echo isset($data['post_title'])?trim(preg_replace('/\s\s+/', ' ', $data['post_title'])):'';?>'},
                    <?php $iLoad++; } ?>
                };
                jQuery(document).ready(function() {
                    jQuery('.modal').MultiStep({
                        title:'Multi modal',
                        data:[
                            {
                            content:'<div class="radio_sellected">' +
                                '<label>' +
                                '   <input type="radio" checked  class="template_select" name="template_select" value="" >' +
                                '  <img class="radio_image_slid" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/blank.png');?>">' +
                                '</label>' +
                                <?php

                                foreach ($dirs as $dir){
                                    $template_folder = basename($dir);
                                    ?>
                                '<label>' +
                                '   <input type="radio"  class="template_select" name="template_select" value="<?php esc_attr_e($template_folder);?>" >' +
                                 '  <img class="radio_image_slid" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/templates/'.$template_folder.'/screenshot.jpg');?>">' +
                                 '</label>' +
                                <?php  } ?>
                                  '</div>',
                            label:'Select Template'
                        },{
                            content:'' +
                                '<div class="form-group">' +
                                '<label for="popup_Title"><?php _e('Title','CPWPWM');?></label>' +
                                '<input type="text" class="form-control" id="popup_Title" placeholder="<?php _e('Popup Title','CPWPWM');?>">' +
                                '</div>'+
                                '<div class="form-group">' +
                                '<label for="exampleInputEmail1">Email address</label>' +
                                '<span id="ck_editor_wordpress_put"></span>'+

                                '</div>',
                                label:'Title and Body'
                        },{

                            content:'<div class="filter">' +
                                '<h5>Show on</h5>' +
                                '<ul class="checkbox">' +
                                '<li>' +
                                '<input type="checkbox" id="all_site" name="all_site" value="all_site" />' +
                                '<label for="all_site">All site</label>' +
                                '</li>' +
                                '<li>' +
                                '<input type="checkbox" id="home_page" name="show_on" value="home_page" />' +
                                '<label for="home_page">Home page</label>' +
                                '</li>' +
                                '</ul>' +

                                '<h5>Show on post type</h5>' +
                                '<span class="select2_post_type">' +
                                '<select id="js-example-basic-hide-search-multi" name="post_types[]" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="select2-data-js-example-basic-hide-search-multi" tabindex="-1" aria-hidden="true">'+
                                <?php $args=array(
                                            'public'                => true,
                                        );
                                ?>
                                <?php $post_types = get_post_types($args, 'objects');?>
                                <?php foreach ($post_types as $post_type){ ?>
                                '<option value="<?php esc_attr_e($post_type->name);?>" ><?php esc_html_e($post_type->label);?></option>'+
                                <?php } ?>
                                '</select>'+
                                '</span>'+

                                '<h5>Select custom</h5>' +
                                '<span class="select2_post_type">' +
                                '<select id="js-example-basic-hide-search-multi-ajax" name="post_custom[]" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="select2-data-js-example-basic-hide-search-multi-ajax" tabindex="-1" aria-hidden="true">'+
                                '<option value="" ></option>'+
                                '</select>'+
                                '</span>'+

                                '</div>',
                                label:'susceptibility'
                        },{

                                content:'<div class="filter">' +
                                    '<h5>Frequancy</h5>' +
                                    '<ul class="checkbox">' +
                                    '<li>' +
                                    '<input type="radio" id="once" class="showing" name="showing" value="once" />' +
                                    '<label for="once"><?php _e('Once','CPWPWM');?></label>' +
                                    '</li>' +
                                    '<li>' +
                                    '<input type="radio" id="all_time"  class="showing" name="showing" value="all_time" />' +
                                    '<label for="all_time">All time</label>' +
                                    '</li>' +
                                    '</ul>' +
                                    '<div class="form-group filter">' +
                                    '<h5><?php _e('Trigger','CPWPWM');?></h5>' +
                                        '<label for="trigger"><?php _e('Trigger','CPWPWM');?></label>' +

                                    '<select  name="trigger" id="trigger" class="form-control">' +
                                    '<option value="Click"><?php _e('Click','CPWPWM');?></option>' +
                                    '<option value="Auto"><?php _e('Auto','CPWPWM');?></option>' +
                                    '<option value="Hover"><?php _e('Hover','CPWPWM');?></option>' +
                                    '<option value="scroll_down"><?php _e('Scroll down','CPWPWM');?></option>' +
                                    '<option value="Exit"><?php _e('Exit','CPWPWM');?></option>' +
                                    '</select>' +

                                    '</div>' +
                                    '<div class="form-group filter" id="trigger_info_div" style="display: none;">' +
                                    '<label for="trigger_info" id="trigger_info_label"><?php _e('Trigger data','CPWPWM');?></label>' +
                                    '<input type="text"  name="trigger_info" style="max-width: 25rem;" id="trigger_info" class="form-control" value="">' +
                                    '<p class="description" id="trigger_info_description"></p>' +
                                    '</div>' +

                                    '</div>',
                                label:'Showing & Trigger'
                            }],
                        final:'Created Successfully..',
                        modalSize:'lg',
                        prevText:'Previous',
                        skipText:'Skip',
                        nextText:'Next',
                        finishText:'Finish',

                    });


                    //data_array = JSON.parse(data_array);
                    jQuery('input:radio[name="template_select"]').change(
                        function(){
                            if (this.checked) {
                                var key_chick = jQuery(this).val();
                                if(key_chick !=''){
                                    jQuery('#popup_Title').val(data_array[key_chick]['title']);
                                    tinymce.execCommand('mceSetContent', true, data_array[key_chick]['content']);
                                }else{
                                    jQuery('#popup_Title').val(" ");
                                    tinymce.execCommand('mceSetContent', true, " ");
                                }

                            }
                        });


                    $('#js-example-basic-hide-search-multi').select2();

                    $('#js-example-basic-hide-search-multi').on('select2:opening select2:closing', function( event ) {
                        var $searchfield = $(this).parent().find('.select2-search__field');
                        $searchfield.prop('disabled', true);
                    });
                    $('#js-example-basic-hide-search-multi').on('select2:select', function (e) {
                        var data = e.params.data;
                    });
                    $('#js-example-basic-hide-search-multi').on('select2:unselect', function (e) {
                        var data = e.params.data;
                    });
                    $("#all_site").change(function() {
                        if(this.checked) {
                            $("#js-example-basic-hide-search-multi").prop("disabled", true);
                            $("#js-example-basic-hide-search-multi-ajax").prop("disabled", true);
                            $("#home_page").prop("disabled", true);
                        }else{
                            $("#js-example-basic-hide-search-multi").prop("disabled", false);
                            $("#js-example-basic-hide-search-multi-ajax").prop("disabled", false);
                            $("#home_page").prop("disabled", false);
                        }
                    });
                    if($('input#all_site').is(':checked')) {
                        $("#js-example-basic-hide-search-multi").prop("disabled", true);
                        $("#js-example-basic-hide-search-multi-ajax").prop("disabled", true);
                        $("#home_page").prop("disabled", true);
                    }else{
                        $("#js-example-basic-hide-search-multi").prop("disabled", false);
                        $("#js-example-basic-hide-search-multi-ajax").prop("disabled", false);
                        $("#home_page").prop("disabled", false);
                    }

                });

                jQuery(document).ready(function($) {

                    // Perform AJAX login on form submit
                    $(document).on('click', ".submmentFormClass", function() {
                        $('body').append('<div class="loading">Loading&#8230;</div>');
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: ajaxurl,
                            data: {
                                'action': 'CPWPWM_save_post_tyoe', //calls wp_ajax_nopriv_ajaxlogin
                                'template_select': $('.template_select:checked').val(),
                                'popup_Title': $('#popup_Title').val(),

                                'all_site': $('#all_site:checked').val(),
                                'home_page': $('#home_page:checked').val(),
                                'postType': $('#js-example-basic-hide-search-multi').val(),
                                'post': $('#js-example-basic-hide-search-multi-ajax').val(),
                                'showing': $('.showing:checked').val(),
                                'trigger': $('#trigger').val(),
                                'trigger_info': $('#trigger_info').val(),
                                'modal_body': tinyMCE.activeEditor.getContent(),
                            },
                            success: function(data){
                                $( ".loading" ).remove();
                                window.location.href = "<?php echo get_admin_url(); ?>/admin.php?page=CPWPWM_DATA_update&id="+data;
                            }
                        });
                    });

                    // Get the contents of the hidden wp_editor
                    reply_editor = $('#ck_editor_wordpress').contents();

                    // Append the contents of the hidden wp_editor to div container
                    $('#ck_editor_wordpress_put').append( reply_editor );

                    // Reinitialize the editor: Remove the editor then add it back
                    tinymce.execCommand( 'mceRemoveEditor', false, 'modal_body' );
                    tinymce.execCommand( 'mceAddEditor', false, 'modal_body' );
                    if($("#fancy-textarea").length > 0) {
                        jQuery(document).ready(function ($) {

                            var editorCss = wp.codeEditor.initialize($('#fancy-textarea'), cm_settings);
                            jQuery(document).on('keyup', '.CodeMirror-code', function () {
                                $('#fancy-textarea').html(editorCss.codemirror.getValue());
                                $('#fancy-textarea').trigger('change');

                            });
                        });
                    }


                });
                function trigger_select(){
                    var value = jQuery('#trigger').val();
                    if(value == 'Click'){
                        jQuery('#trigger_info_div').show();
                        jQuery("#trigger_info").prop("type", "text");
                        jQuery('#trigger_info_label').text("<?php _e('Click class','CPWPWM');?>");
                        jQuery('#trigger_info_description').text("<?php _e('please insert class name in user clicked pop up opening','CPWPWM');?>");
                    }
                    if(value == 'Hover'){
                        jQuery('#trigger_info_div').show();
                        jQuery("#trigger_info").prop("type", "text");
                        jQuery('#trigger_info_label').text("<?php _e('Hover class','CPWPWM');?>");
                        jQuery('#trigger_info_description').text("<?php _e('please insert class name in user Hover pop up opening','CPWPWM');?>");
                    }
                    if(value == 'Auto'){
                        jQuery('#trigger_info_div').show();
                        jQuery("#trigger_info").prop("type", "number");
                        jQuery('#trigger_info_label').text("<?php _e('number of seconds','CPWPWM');?>");
                        jQuery('#trigger_info_description').text("<?php _e('please insert number of seconds pop up opening','CPWPWM');?>");
                    }
                    if(value == 'scroll_down'){
                        jQuery('#trigger_info_div').show();
                        jQuery("#trigger_info").prop("type", "number");
                        jQuery('#trigger_info_label').text("<?php _e('number of scroll down %','CPWPWM');?>");
                        jQuery('#trigger_info_description').text("<?php _e('please insert number of scroll down pop up opening','CPWPWM');?>");
                    }
                    if(value == 'Exit'){
                        jQuery('#trigger_info_div').hide();
                    }
                }
                jQuery(document).ready(function($) {
                    trigger_select();
                    jQuery('#trigger').change(function () {
                        trigger_select();
                    });
                });
                jQuery(document).on('click', ".shortcodecopy", function() {
                    jQuery(this).select();
                    navigator.clipboard.writeText(jQuery(this).text());
                    alert("<?php _e('Copied the shortCode','CPWPWM');?>");
                });

                jQuery(document).on('click', ".shortcodephpcopy", function() {
                    jQuery(this).select();
                    navigator.clipboard.writeText(jQuery(this).text());
                    alert("<?php _e('Copied the shortCode','CPWPWM');?>");
                });


                function close_select(){
                    var value = jQuery('#close').val();
                    if(value == 'auto'){
                        jQuery('#close_info_div').show();
                        jQuery("#close_info").prop("type", "number");
                        jQuery('#close_info_label').text("<?php _e('after x sec.','CPWPWM');?>");
                        jQuery('#close_info_description').text("<?php _e('after x sec','CPWPWM');?>");
                    }
                    if(value == 'Click'){
                        jQuery('#close_info_div').hide();
                    }
                }
                jQuery(document).ready(function($) {
                    close_select();
                    jQuery('#close').change(function () {
                        close_select();
                    });
                });


                <?php if(isset($_GET['page']) && $_GET['page']== 'CPWPWM_DATA') {;?>
                jQuery(document).ready(function($) {
                var key_chick = jQuery('input:radio[name="template_select"]:checked').val();
                if(key_chick != ''){
                    jQuery('#popup_Title').val(data_array[key_chick]['title']);
                    tinymce.execCommand('mceSetContent', false, data_array[key_chick]['content']);
                }else{
                    jQuery('#popup_Title').val(" ");
                    tinymce.execCommand('mceSetContent', false, " ");
                }

                });
                <?php } ?>
            </script>

                <?php

        }
        public function register_menu_page(){
            add_menu_page(
                __( 'Popup Maker', 'CPWPWM' ),
                __( 'Popup Maker', 'CPWPWM' ),
                'manage_options',
                'CPWPWM_DATA',
                array($this,'page'),
                'dashicons-welcome-widgets-menus',
                6
            );
            add_submenu_page( '',  __( 'update Popup Maker', 'CPWPWM' ),  __( 'update Popup Maker', 'CPWPWM' ),
                'manage_options', 'CPWPWM_DATA_update',array($this,'update_page'));
        }
        public function sanitize_text_or_array_field($array_or_string) {
            if( is_string($array_or_string) ){
                $array_or_string = sanitize_text_field($array_or_string);
            }elseif( is_array($array_or_string) ){
                foreach ( $array_or_string as $key => &$value ) {
                    if ( is_array( $value ) ) {
                        $value = sanitize_text_or_array_field($value);
                    }
                    else {
                        $value = sanitize_text_field( $value );
                    }
                }
            }
            return $array_or_string;
        }
        public function update_page(){

            $id = absint($_GET['id']);
                if(isset($_POST) && !empty($_POST)){

                    $popup_post = array(
                        'ID'           => $id,
                        'post_title'   => sanitize_text_field($_POST['popup_Title']),
                        'post_content' => isset($_POST['the_content'])?wp_kses_post($_POST['the_content']):wp_kses_post($_POST['modal_body']),
                    );

                    wp_update_post( $popup_post ,true,false);
                    if(isset($_POST['template_position'])){
                        update_post_meta($id,'template_position',$this->sanitize_text_or_array_field($_POST['template_position']));
                    }else{
                        delete_post_meta($id,'template_position');
                    }
                    if(isset($_POST['Show_popup_header'])){
                        update_post_meta($id,'Show_popup_header',$this->sanitize_text_or_array_field($_POST['Show_popup_header']));
                    }else{
                        delete_post_meta($id,'Show_popup_header');
                    }
                    if(isset($_POST['Show_popup_footer'])){
                        update_post_meta($id,'Show_popup_footer',$this->sanitize_text_or_array_field($_POST['Show_popup_footer']));
                    }else{
                        delete_post_meta($id,'Show_popup_footer');
                    }
                    if(isset($_POST['width'])){
                        update_post_meta($id,'width',$this->sanitize_text_or_array_field($_POST['width']));
                    }else{
                        delete_post_meta($id,'width');
                    }
                    if(isset($_POST['minWidth'])){
                        update_post_meta($id,'minWidth',$this->sanitize_text_or_array_field($_POST['minWidth']));
                    }else{
                        delete_post_meta($id,'minWidth');
                    }
                    if(isset($_POST['maxWidth'])){
                        update_post_meta($id,'maxWidth',$this->sanitize_text_or_array_field($_POST['maxWidth']));
                    }else{
                        delete_post_meta($id,'maxWidth');
                    }
                    if(isset($_POST['AnimationTime'])){
                        update_post_meta($id,'AnimationTime',$this->sanitize_text_or_array_field($_POST['AnimationTime']));
                    }else{
                        delete_post_meta($id,'AnimationTime');
                    }
                    if(isset($_POST['Animationtype'])){
                        update_post_meta($id,'Animationtype',$this->sanitize_text_or_array_field($_POST['Animationtype']));
                    }else{
                        delete_post_meta($id,'Animationtype');
                    }
                    if(isset($_POST['showing'])){
                        update_post_meta($id,'showing',$this->sanitize_text_or_array_field($_POST['showing']));
                    }else{
                        delete_post_meta($id,'showing');
                    }
                    if(isset($_POST['post_types'])){
                        update_post_meta($id,'post_types',$this->sanitize_text_or_array_field($_POST['post_types']));
                    }else{
                        delete_post_meta($id,'post_types');
                    }
                    if(isset($_POST['post_custom'])){
                        update_post_meta($id,'post_custom',$this->sanitize_text_or_array_field($_POST['post_custom']));
                    }else{
                        delete_post_meta($id,'post_custom');
                    }
                    if(isset($_POST['cssCustom'])){
                        update_post_meta($id,'cssCustom',wp_kses_post($_POST['cssCustom']));
                    }else{
                        delete_post_meta($id,'cssCustom');
                    }
                    if(isset($_POST['trigger'])){
                        update_post_meta($id,'trigger',$this->sanitize_text_or_array_field($_POST['trigger']));
                    }else{
                        delete_post_meta($id,'trigger');
                    }
                    if(isset($_POST['close'])){
                        update_post_meta($id,'close',$this->sanitize_text_or_array_field($_POST['close']));
                    }else{
                        delete_post_meta($id,'close');
                    }
                    if(isset($_POST['close_info'])){
                        update_post_meta($id,'close_info',$this->sanitize_text_or_array_field($_POST['close_info']));
                    }

                    if(isset($_POST['trigger_info'])){
                        update_post_meta($id,'trigger_info',$this->sanitize_text_or_array_field($_POST['trigger_info']));
                    }else{
                        delete_post_meta($id,'trigger_info');
                    }
                    if(isset($_POST['popup_status'])){
                        update_post_meta($id,'popup_status',$this->sanitize_text_or_array_field($_POST['popup_status']));
                    }else{
                        delete_post_meta($id,'popup_status');
                    }
                    if(isset($_POST['home_page']) && !empty($_POST['home_page'])){
                        update_post_meta($id,'home_page',$this->sanitize_text_or_array_field($_POST['home_page']));
                    }else{
                        delete_post_meta($id,'home_page');
                    }
                    if(isset($_POST['all_site']) && !empty($_POST['all_site'])){
                        update_post_meta($id,'all_site',$this->sanitize_text_or_array_field($_POST['all_site']));
                    }else{
                        delete_post_meta($id,'all_site');
                    }

                }
            ?>
                <div class="container-fluid mt-3" style="max-width: 97%;">

                    <div class="row" >
                        <div class="col-md-3" id="sidebar_popup" style="min-height: 100vh;">
                            <form action="#" method="post" id="popup_form">
                                <input type="submit" class="save-popup mt-2 mr-1 mb-2 ml-0 col-md-6" value="<?php _e( 'Update', 'CPWPWM' );?>">
                                <a href="<?php echo admin_url();?>/admin.php?page=CPWPWM_DATA" class=" Go_back_save_popup mt-2 mr-1 mb-2 ml-0 col-md-5">Go back</a>
                                <div class="accordion" id="accordionExample">
                                <div class="card pt-0 pl-0 pb-0 pr-0 mt-0">
                                    <div class="card-header" id="headingOne1">
                                        <h5 class="mb-0 mt-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                                                <?php _e('Content','CPWPWM');?>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="popup_status"><?php _e('Popup status','CPWPWM');?></label>
                                                <?php $popup_status=get_post_meta($id,'popup_status',true);?>
                                                <select style="width: 100%;" class="form-control" name="popup_status" id="popup_status">
                                                    <option value="published" <?php if($popup_status == 'publish'){?> selected<?php } ?>><?php _e('Publish','CPWPWM');?></option>
                                                    <option value="draft" <?php if($popup_status == 'draft'){?> selected<?php } ?>><?php _e('Draft','CPWPWM');?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="popup_Title"><?php _e('Title','CPWPWM');?></label>
                                                <input type="text" data-type="text" value="<?php echo get_post_field('post_title', absint($id));?>" data-style="" data-changeClass="modal-title" class="form-control" name="popup_Title" id="popup_Title" placeholder="Popup Title">
                                            </div>
                                            <div class="form-group">
                                                <label for="modal_body"><?php _e('Content','CPWPWM');?></label>

                                                <?php  wp_editor( get_post_field('post_content', $id,'db'), 'modal_body' ,array(
                                                    'media_buttons'       => true,
                                                    'default_editor'      => 'tinymce',
                                                    'drag_drop_upload'    => true,
                                                    'textarea_name'       => 'modal_body',
                                                    'textarea_rows'       => 10,
                                                    'tabindex'            => 0,
                                                    'teeny'               => true,
                                                    '_content_editor_dfw' => false,
                                                    'tinymce' => array(
                                                        'init_instance_callback' => 'function(editor) {
                                                                    editor.on("change", function(){
                                                                        showValues();
                                                                });
                                                            }'
                                                        ),
                                                    'quicktags'           => true,
                                                    'dfw' => false, // replace the default fullscreen with DFW


                                                ));?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-0 pl-0 pb-0 pr-0 mt-0">
                                    <div class="card-header" id="headingTwo2">
                                        <h5 class="mb-0 mt-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                                                <?php _e('PopUp Display','CPWPWM');?>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo2" data-parent="#accordionExample">
                                        <div class="card-body">

                                            <div class="form-group radio_sellected_pos">
                                                <?php
                                                $template_position = get_post_meta($id,'template_position',true);
                                                ?>
                                                <label for="popup_Title" style="display: block"><?php _e('Title','CPWPWM');?></label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest" data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','bottom'=>0,'left'=>0,'right'=>'unset','top'=>'unset')));?>" class="template_select" name="template_position" value="bottom-lift"  <?php if($template_position == 'bottom-lift'){?> checked <?php } ?> >
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/bottom-lift.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','right'=>0,'left'=>0,'top'=>0,'bottom'=>'unset')));?>" class="template_select" name="template_position" value="top-middle" <?php if($template_position == 'top-middle'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/top-middle.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','right'=>0,'top'=>0,'bottom'=>'unset','left'=>'unset')));?>" class="template_select" name="template_position" value="right-top" <?php if($template_position == 'right-top'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/right-top.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','bottom'=>'unset','left'=>'unset','right'=>0,'top'=>'calc( 50% - 120px)','transform'=>'translateY(-50%);')));?>" class="template_select" name="template_position" value="right-middle" <?php if($template_position == 'right-middle'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/right-middle.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','bottom'=>0,'right'=>0,'top'=>'unset','left'=>'unset')));?>" class="template_select" name="template_position" value="right-bottom" <?php if($template_position == 'right-bottom'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/right-bottom.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','bottom'=>0,'right'=>0,'left'=>0,'top'=>'calc( 50% - 120px)','transform'=>'translateY(-50%);')));?>" class="template_select" name="template_position" value="middle" <?php if($template_position == 'middle'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/middle.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','bottom'=>'unset','left'=>0,'right'=>'unset','top'=>'calc( 50% - 120px)','transform'=>'translateY(-50%);')));?>" class="template_select" name="template_position" value="left-middle" <?php if($template_position == 'left-middle'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/left-middle.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','left'=>0,'bottom'=>0,'right'=>0,'top'=>'unset')));?>"  class="template_select" name="template_position" value="bottom-middle" <?php if($template_position == 'bottom-middle'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/bottom-middle.png');?>">
                                                </label>
                                                <label>
                                                    <input type="radio" data-type="style"  data-changeClass="modaltest"  data-style="<?php esc_html_e( json_encode(array('position'=>'absolute','left'=>0,'bottom'=>0,'top'=>'unset','right'=>'unset')));?>" class="template_select" name="template_position" value="bottom-lift" <?php if($template_position == 'bottom-lift'){?> checked <?php } ?>>
                                                    <img class="radio_image_pos" src="<?php echo esc_url(CPWPWM_PLUGIN_URL.'/assets/img/position/bottom-lift.png');?>">
                                                </label>

                                            </div>
                                            <div class="form-group filter">
                                                <?php
                                                $Show_popup_header = get_post_meta($id,'Show_popup_header',true);
                                                $Show_popup_footer = get_post_meta($id,'Show_popup_footer',true);
                                                ?>
                                                <label for=""><?php _e('Element','CPWPWM');?></label>
                                                <ul class="checkbox" style="margin-left: 0px;">
                                                    <li style="display: block;width: 100%;">
                                                        <input type="checkbox" <?php if($Show_popup_header == 'Show_popup_header'){?> checked <?php } ?>  data-type="style" onchange="ShowEle('modal-header')"  data-changeClass="modal-header"  data-style="<?php esc_html_e( json_encode(array('display'=>'none')));?>" id="Show_popup_header" class="Trigger"  name="Show_popup_header" value="Show_popup_header" />
                                                        <label for="Click"><?php _e('Hide popup header','CPWPWM');?></label>
                                                    </li>
                                                    <li style="display: block;width: 100%;">
                                                        <input type="checkbox" <?php if($Show_popup_footer == 'Show_popup_footer'){?> checked <?php } ?>  data-type="style" onchange="ShowEle('modal-footer')"  data-changeClass="modal-footer"  data-style="<?php esc_html_e( json_encode(array('display'=>'none')));?>"  id="Show_popup_footer" class="Trigger"  name="Show_popup_footer" value="Show_popup_footer" />
                                                        <label for="Auto"><?php _e('Hide popup footer','CPWPWM');?></label>
                                                    </li>


                                                </ul>
                                            </div>
                                            <div class="form-group">
                                                <?php
                                                $width = get_post_meta($id,'width',true);
                                                ?>
                                                <label for="customRange11" style="display: block"><?php _e('Size','CPWPWM');?></label>
                                                <div class="d-flex mt-0">
                                                    <div class="w-75">
                                                        <input type="range" value="<?php esc_attr_e(absint($width));?>" name="width" data-type="width" data-changeClass="modal-dialog" data-change-type="%" data-style="width" class="custom-range" id="customRange11" min="0" max="100">
                                                    </div>
                                                    <span class="font-weight-bold text-primary ml-2 valueSpan2"></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php
                                                $minWidth = get_post_meta($id,'minWidth',true);
                                                ?>
                                                <label for="minWidth"><?php _e('Minimum Width','CPWPWM');?></label>
                                                <div class="input-group">

                                                    <input type="text" class="form-control" value="<?php esc_attr_e(absint($minWidth));?>"  data-type="width" data-changeClass="modal-dialog" data-change-type="px"  data-style="min-width" id="minWidth" name="minWidth" placeholder="minWidth" aria-describedby="validationTooltipMinPrepend" required>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="validationTooltipMinPrepend">PX</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <?php
                                                $maxWidth = get_post_meta($id,'maxWidth',true);
                                                ?>
                                                <label for="maxWidth"><?php _e('Maximum Width','CPWPWM');?></label>
                                                <div class="input-group">

                                                    <input type="text" class="form-control" value="<?php esc_attr_e(absint($maxWidth));?>" data-type="width" data-changeClass="modal-dialog" data-change-type="px"   data-style="max-width" id="maxWidth" name="maxWidth"  placeholder="maxWidth" aria-describedby="validationTooltipMaxPrepend" required>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="validationTooltipMaxPrepend">PX</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label for="AnimationTime"><?php _e('Animation Speed','CPWPWM');?></label>
                                                <?php $animateTime=get_post_meta($id,'AnimationTime',true);?>
                                                <select style="width: 100%;" data-type="class"  onchange="showValues()" data-changeClass="modal-dialog"  data-style="animate__" class="form-control" name="AnimationTime" id="AnimationTime">
                                                    <option value="slow" <?php if($animateTime == 'slow'){?> selected<?php } ?>><?php _e('slow','CPWPWM');?></option>
                                                    <option value="slower" <?php if($animateTime == 'slower'){?> selected<?php } ?>><?php _e('slower','CPWPWM');?></option>
                                                    <option value="fast" <?php if($animateTime == 'fast'){?> selected<?php } ?>><?php _e('fast','CPWPWM');?></option>
                                                    <option value="faster" <?php if($animateTime == 'faster'){?> selected<?php } ?>><?php _e('faster','CPWPWM');?></option>
                                                </select>
                                            </div>
<!--animate__animated animate__bounce-->
                                            <div class="form-group">
                                                <label for="Animationtype"><?php _e('Animation type','CPWPWM');?></label>
                                                <?php $animate=get_post_meta($id,'Animationtype',true);?>
                                                <select style="width: 100%;" onchange="showValues()" data-type="class" data-changeClass="modal-dialog"  data-style="animate__" class="js-example-basic-single js-states form-control" name="Animationtype" id="Animationtype">

                                                    <optgroup label="shake">
                                                        <option value="shakeX" <?php if($animate == 'shakeX'){?> selected<?php } ?>><?php _e('shakeX','CPWPWM');?></option>
                                                        <option value="shakeY" <?php if($animate == 'shakeY'){?> selected<?php } ?>><?php _e('shakeY','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="back">
                                                        <option value="backInDown" <?php if($animate == 'backInDown'){?> selected<?php } ?>><?php _e('backInDown','CPWPWM');?></option>
                                                        <option value="backInLeft" <?php if($animate == 'backInLeft'){?> selected<?php } ?>><?php _e('backInLeft','CPWPWM');?></option>
                                                        <option value="backInRight" <?php if($animate == 'backInRight'){?> selected<?php } ?>><?php _e('backInRight','CPWPWM');?></option>
                                                        <option value="backInUp" <?php if($animate == 'backInUp'){?> selected<?php } ?>><?php _e('backInUp','CPWPWM');?></option>
                                                        <option value="backOutDown" <?php if($animate == 'backOutDown'){?> selected<?php } ?>><?php _e('backOutDown','CPWPWM');?></option>
                                                        <option value="backOutLeft" <?php if($animate == 'backOutLeft'){?> selected<?php } ?>><?php _e('backOutLeft','CPWPWM');?></option>
                                                        <option value="backOutRight" <?php if($animate == 'backOutRight'){?> selected<?php } ?>><?php _e('backOutRight','CPWPWM');?></option>
                                                        <option value="backOutUp" <?php if($animate == 'backOutUp'){?> selected<?php } ?>><?php _e('backOutUp','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="bounce">
                                                        <option value="bounceIn" <?php if($animate == 'bounceIn'){?> selected<?php } ?>><?php _e('bounceIn','CPWPWM');?></option>
                                                        <option value="bounceInDown" <?php if($animate == 'bounceInDown'){?> selected<?php } ?>><?php _e('bounceInDown','CPWPWM');?></option>
                                                        <option value="bounceInLeft" <?php if($animate == 'bounceInLeft'){?> selected<?php } ?>><?php _e('bounceInLeft','CPWPWM');?></option>
                                                        <option value="bounceInRight" <?php if($animate == 'bounceInRight'){?> selected<?php } ?>><?php _e('bounceInRight','CPWPWM');?></option>
                                                        <option value="bounceInUp" <?php if($animate == 'bounceInUp'){?> selected<?php } ?>><?php _e('bounceInUp','CPWPWM');?></option>
                                                        <option value="bounceOut" <?php if($animate == 'bounceOut'){?> selected<?php } ?>><?php _e('bounceOut','CPWPWM');?></option>
                                                        <option value="bounceOutDown" <?php if($animate == 'bounceOutDown'){?> selected<?php } ?>><?php _e('bounceOutDown','CPWPWM');?></option>
                                                        <option value="bounceOutLeft" <?php if($animate == 'bounceOutLeft'){?> selected<?php } ?>><?php _e('bounceOutLeft','CPWPWM');?></option>
                                                        <option value="bounceOutRight" <?php if($animate == 'bounceOutRight'){?> selected<?php } ?>><?php _e('bounceOutRight','CPWPWM');?></option>
                                                        <option value="bounceOutUp" <?php if($animate == 'bounceOutUp'){?> selected<?php } ?>><?php _e('bounceOutUp','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="fade">
                                                        <option value="fadeIn" <?php if($animate == 'fadeIn'){?> selected<?php } ?>><?php _e('fadeIn','CPWPWM');?></option>
                                                        <option value="fadeInDown" <?php if($animate == 'fadeInDown'){?> selected<?php } ?>><?php _e('fadeInDown','CPWPWM');?></option>
                                                        <option value="fadeInDownBig" <?php if($animate == 'fadeInDownBig'){?> selected<?php } ?>><?php _e('fadeInDownBig','CPWPWM');?></option>
                                                        <option value="fadeInLeft" <?php if($animate == 'fadeInLeft'){?> selected<?php } ?>><?php _e('fadeInLeft','CPWPWM');?></option>
                                                        <option value="fadeInLeftBig" <?php if($animate == 'fadeInLeftBig'){?> selected<?php } ?>><?php _e('fadeInLeftBig','CPWPWM');?></option>
                                                        <option value="fadeInRight" <?php if($animate == 'fadeInRight'){?> selected<?php } ?>><?php _e('fadeInRight','CPWPWM');?></option>
                                                        <option value="fadeInRightBig" <?php if($animate == 'fadeInRightBig'){?> selected<?php } ?>><?php _e('fadeInRightBig','CPWPWM');?></option>
                                                        <option value="fadeInUp" <?php if($animate == 'fadeInUp'){?> selected<?php } ?>><?php _e('fadeInUp','CPWPWM');?></option>
                                                        <option value="fadeInUpBig" <?php if($animate == 'fadeInUpBig'){?> selected<?php } ?>><?php _e('fadeInUpBig','CPWPWM');?></option>
                                                        <option value="fadeInTopLeft" <?php if($animate == 'fadeInTopLeft'){?> selected<?php } ?>><?php _e('fadeInTopLeft','CPWPWM');?></option>
                                                        <option value="fadeInTopRight" <?php if($animate == 'fadeInTopRight'){?> selected<?php } ?>><?php _e('fadeInTopRight','CPWPWM');?></option>
                                                        <option value="fadeInBottomLeft" <?php if($animate == 'fadeInBottomLeft'){?> selected<?php } ?>><?php _e('fadeInBottomLeft','CPWPWM');?></option>
                                                        <option value="fadeInBottomRight" <?php if($animate == 'fadeInBottomRight'){?> selected<?php } ?>><?php _e('fadeInBottomRight','CPWPWM');?></option>
                                                        <option value="fadeOut" <?php if($animate == 'fadeOut'){?> selected<?php } ?>><?php _e('fadeOut','CPWPWM');?></option>
                                                        <option value="fadeOutDown" <?php if($animate == 'fadeOutDown'){?> selected<?php } ?>><?php _e('fadeOutDown','CPWPWM');?></option>
                                                        <option value="fadeOutDownBig" <?php if($animate == 'fadeOutDownBig'){?> selected<?php } ?>><?php _e('fadeOutDownBig','CPWPWM');?></option>
                                                        <option value="fadeOutLeft" <?php if($animate == 'fadeOutLeft'){?> selected<?php } ?>><?php _e('fadeOutLeft','CPWPWM');?></option>
                                                        <option value="fadeOutLeftBig" <?php if($animate == 'fadeOutLeftBig'){?> selected<?php } ?>><?php _e('fadeOutLeftBig','CPWPWM');?></option>
                                                        <option value="fadeOutRight" <?php if($animate == 'fadeOutRight'){?> selected<?php } ?>><?php _e('fadeOutRight','CPWPWM');?></option>
                                                        <option value="fadeOutRightBig" <?php if($animate == 'fadeOutRightBig'){?> selected<?php } ?>><?php _e('fadeOutRightBig','CPWPWM');?></option>
                                                        <option value="fadeOutUp" <?php if($animate == 'fadeOutUp'){?> selected<?php } ?>><?php _e('fadeOutUp','CPWPWM');?></option>
                                                        <option value="fadeOutUpBig" <?php if($animate == 'fadeOutUpBig'){?> selected<?php } ?>><?php _e('fadeOutUpBig','CPWPWM');?></option>
                                                        <option value="fadeOutTopLeft" <?php if($animate == 'fadeOutTopLeft'){?> selected<?php } ?>><?php _e('fadeOutTopLeft','CPWPWM');?></option>
                                                        <option value="fadeOutTopRight" <?php if($animate == 'fadeOutTopRight'){?> selected<?php } ?>><?php _e('fadeOutTopRight','CPWPWM');?></option>
                                                        <option value="fadeOutBottomRight" <?php if($animate == 'fadeOutBottomRight'){?> selected<?php } ?>><?php _e('fadeOutBottomRight','CPWPWM');?></option>
                                                        <option value="fadeOutBottomLeft" <?php if($animate == 'fadeOutBottomLeft'){?> selected<?php } ?>><?php _e('fadeOutBottomLeft','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="flip">
                                                        <option value="flip" <?php if($animate == 'flip'){?> selected<?php } ?>><?php _e('flip','CPWPWM');?></option>
                                                        <option value="flipInX" <?php if($animate == 'flipInX'){?> selected<?php } ?>><?php _e('flipInX','CPWPWM');?></option>
                                                        <option value="flipInY" <?php if($animate == 'flipInY'){?> selected<?php } ?>><?php _e('flipInY','CPWPWM');?></option>
                                                        <option value="flipOutX" <?php if($animate == 'flipOutX'){?> selected<?php } ?>><?php _e('flipOutX','CPWPWM');?></option>
                                                        <option value="flipOutY" <?php if($animate == 'flipOutY'){?> selected<?php } ?>><?php _e('flipOutY','CPWPWM');?></option>
                                                    </optgroup>
                                                        <optgroup label="light Speed">
                                                        <option value="lightSpeedInRight" <?php if($animate == 'lightSpeedInRight'){?> selected<?php } ?>><?php _e('lightSpeedInRight','CPWPWM');?></option>
                                                        <option value="lightSpeedInLeft" <?php if($animate == 'lightSpeedInLeft'){?> selected<?php } ?>><?php _e('lightSpeedInLeft','CPWPWM');?></option>
                                                        <option value="lightSpeedOutRight" <?php if($animate == 'lightSpeedOutRight'){?> selected<?php } ?>><?php _e('lightSpeedOutRight','CPWPWM');?></option>
                                                        <option value="lightSpeedOutLeft" <?php if($animate == 'lightSpeedOutLeft'){?> selected<?php } ?>><?php _e('lightSpeedOutLeft','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="rotate">
                                                        <option value="rotateIn" <?php if($animate == 'rotateIn'){?> selected<?php } ?>><?php _e('rotateIn','CPWPWM');?></option>
                                                        <option value="rotateInDownLeft" <?php if($animate == 'rotateInDownLeft'){?> selected<?php } ?>><?php _e('rotateInDownLeft','CPWPWM');?></option>
                                                        <option value="rotateInDownRight" <?php if($animate == 'rotateInDownRight'){?> selected<?php } ?>><?php _e('rotateInDownRight','CPWPWM');?></option>
                                                        <option value="rotateInUpLeft" <?php if($animate == 'rotateInUpLeft'){?> selected<?php } ?>><?php _e('rotateInUpLeft','CPWPWM');?></option>
                                                        <option value="rotateInUpRight" <?php if($animate == 'rotateInUpRight'){?> selected<?php } ?>><?php _e('rotateInUpRight','CPWPWM');?></option>
                                                        <option value="rotateOut" <?php if($animate == 'rotateOut'){?> selected<?php } ?>><?php _e('rotateOut','CPWPWM');?></option>
                                                        <option value="rotateOutDownLeft" <?php if($animate == 'rotateOutDownLeft'){?> selected<?php } ?>><?php _e('rotateOutDownLeft','CPWPWM');?></option>
                                                        <option value="rotateOutDownRight" <?php if($animate == 'rotateOutDownRight'){?> selected<?php } ?>><?php _e('rotateOutDownRight','CPWPWM');?></option>
                                                        <option value="rotateOutUpLeft" <?php if($animate == 'rotateOutUpLeft'){?> selected<?php } ?>><?php _e('rotateOutUpLeft','CPWPWM');?></option>
                                                        <option value="rotateOutUpRight" <?php if($animate == 'rotateOutUpRight'){?> selected<?php } ?>><?php _e('rotateOutUpRight','CPWPWM');?></option>
                                                    </optgroup>

                                                    <optgroup label="roll">
                                                        <option value="rollIn" <?php if($animate == 'rollIn'){?> selected<?php } ?>><?php _e('rollIn','CPWPWM');?></option>
                                                        <option value="rollOut" <?php if($animate == 'rollOut'){?> selected<?php } ?>><?php _e('rollOut','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="zoom">
                                                        <option value="zoomIn" <?php if($animate == 'zoomIn'){?> selected<?php } ?>><?php _e('zoomIn','CPWPWM');?></option>
                                                        <option value="zoomInDown" <?php if($animate == 'zoomInDown'){?> selected<?php } ?>><?php _e('zoomInDown','CPWPWM');?></option>
                                                        <option value="zoomInLeft" <?php if($animate == 'zoomInLeft'){?> selected<?php } ?>><?php _e('zoomInLeft','CPWPWM');?></option>
                                                        <option value="zoomInRight" <?php if($animate == 'zoomInRight'){?> selected<?php } ?>><?php _e('zoomInRight','CPWPWM');?></option>
                                                        <option value="zoomInUp" <?php if($animate == 'zoomInUp'){?> selected<?php } ?>><?php _e('zoomInUp','CPWPWM');?></option>
                                                        <option value="zoomOut" <?php if($animate == 'zoomOut'){?> selected<?php } ?>><?php _e('zoomOut','CPWPWM');?></option>
                                                        <option value="zoomOutDown" <?php if($animate == 'zoomOutDown'){?> selected<?php } ?>><?php _e('zoomOutDown','CPWPWM');?></option>
                                                        <option value="zoomOutLeft" <?php if($animate == 'zoomOutLeft'){?> selected<?php } ?>><?php _e('zoomOutLeft','CPWPWM');?></option>
                                                        <option value="zoomOutRight" <?php if($animate == 'zoomOutRight'){?> selected<?php } ?>><?php _e('zoomOutRight','CPWPWM');?></option>
                                                        <option value="zoomOutUp" <?php if($animate == 'zoomOutUp'){?> selected<?php } ?>><?php _e('zoomOutUp','CPWPWM');?></option>
                                                    </optgroup>
                                                        <optgroup label="slide">
                                                        <option value="slideInDown" <?php if($animate == 'slideInDown'){?> selected<?php } ?>><?php _e('slideInDown','CPWPWM');?></option>
                                                        <option value="slideInLeft" <?php if($animate == 'slideInLeft'){?> selected<?php } ?>><?php _e('slideInLeft','CPWPWM');?></option>
                                                        <option value="slideInRight" <?php if($animate == 'slideInRight'){?> selected<?php } ?>><?php _e('slideInRight','CPWPWM');?></option>
                                                        <option value="slideInUp" <?php if($animate == 'slideInUp'){?> selected<?php } ?>><?php _e('slideInUp','CPWPWM');?></option>
                                                        <option value="slideOutDown" <?php if($animate == 'slideOutDown'){?> selected<?php } ?>><?php _e('slideOutDown','CPWPWM');?></option>
                                                        <option value="slideOutLeft" <?php if($animate == 'slideOutLeft'){?> selected<?php } ?>><?php _e('slideOutLeft','CPWPWM');?></option>
                                                        <option value="slideOutRight" <?php if($animate == 'slideOutRight'){?> selected<?php } ?>><?php _e('slideOutRight','CPWPWM');?></option>
                                                        <option value="slideOutUp" <?php if($animate == 'slideOutUp'){?> selected<?php } ?>><?php _e('slideOutUp','CPWPWM');?></option>
                                                    </optgroup>
                                                    <optgroup label="other">
                                                        <option value="hinge" <?php if($animate == 'hinge'){?> selected<?php } ?>><?php _e('hinge','CPWPWM');?></option>
                                                        <option value="jackInTheBox" <?php if($animate == 'jackInTheBox'){?> selected<?php } ?>><?php _e('jackInTheBox','CPWPWM');?></option>
                                                        <option value="bounce" <?php if($animate == 'bounce'){?> selected<?php } ?>><?php _e('bounce','CPWPWM');?></option>
                                                        <option value="flash" <?php if($animate == 'flash'){?> selected<?php } ?>><?php _e('flash','CPWPWM');?></option>
                                                        <option value="pulse" <?php if($animate == 'pulse'){?> selected<?php } ?>><?php _e('pulse','CPWPWM');?></option>
                                                        <option value="rubberBand" <?php if($animate == 'rubberBand'){?> selected<?php } ?>><?php _e('rubberBand','CPWPWM');?></option>
                                                        <option value="headShake" <?php if($animate == 'headShake'){?> selected<?php } ?>><?php _e('headShake','CPWPWM');?></option>
                                                        <option value="swingtada" <?php if($animate == 'swingtada'){?> selected<?php } ?>><?php _e('swingtada','CPWPWM');?></option>
                                                        <option value="tada" <?php if($animate == 'tada'){?> selected<?php } ?>><?php _e('tada','CPWPWM');?></option>
                                                        <option value="wobble" <?php if($animate == 'wobble'){?> selected<?php } ?>><?php _e('wobble','CPWPWM');?></option>
                                                        <option value="jello" <?php if($animate == 'jello'){?> selected<?php } ?>><?php _e('jello','CPWPWM');?></option>

                                                    </optgroup>
                                                </select>

                                            </div>


                                            <!-- no no -->
                                            <div class="form-group filter">
                                                <?php
                                                $showing = get_post_meta($id,'showing',true);
                                                ?>
                                                <label for=""><?php _e('Frequancy','CPWPWM');?></label>
                                                <ul class="checkbox" style="margin-left: 0px;">
                                                    <li>
                                                        <input type="radio" id="once" <?php if($showing == 'once'){?> checked <?php } ?> class="showing" name="showing" value="once" />
                                                        <label for="once"><?php _e('Once','CPWPWM');?></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="all_time"  <?php if($showing == 'all_time'){?> checked <?php } ?> class="showing" name="showing" value="all_time" />
                                                        <label for="all_time"><?php _e('All time','CPWPWM');?></label>
                                                    </li>
                                                </ul>

                                            </div>
                                            <!-- no no -->
                                            <div class="form-group filter">
                                                <?php
                                                $all_site = get_post_meta($id,'all_site',true);
                                                $home_page = get_post_meta($id,'home_page',true);
                                                ?>
                                                <label for=""><?php _e('Show on','CPWPWM');?></label>
                                                <ul class="checkbox" style="margin-left: 0px;">
                                                    <li>
                                                        <input type="checkbox" <?php if('all_site'==$all_site){?> checked <?php } ?>  id="all_site" name="all_site"  value="all_site" />
                                                        <label for="all_site"><?php _e('All site','CPWPWM');?></label>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" <?php if('home_page'==$home_page){?> checked <?php } ?> id="home_page" name="home_page" value="home_page" />
                                                        <label for="home_page"><?php _e('Home page','CPWPWM');?></label>
                                                    </li>


                                                </ul>
                                            </div>

                                            <div class="form-group">
                                                <?php
                                                $post_types_selected = get_post_meta($id,'post_types',true);
                                                ?>
                                                <label for="js-example-basic-hide-search-multi" style="display: block;"><?php _e('Show on post type','CPWPWM');?></label>
                                                <span class="select2_post_type">
                                                    <select id="js-example-basic-hide-search-multi" name="post_types[]" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="select2-data-js-example-basic-hide-search-multi" tabindex="-1" aria-hidden="true">
                                                        <?php $args=array(
                                                            'public'=> true,
                                                        );
                                                        ?>
                                                        <?php $post_types = get_post_types($args, 'objects');?>
                                                        <?php foreach ($post_types as $post_type){ ?>
                                                            <option <?php if( !empty($post_types_selected) && in_array($post_type->name, $post_types_selected, true)){?> selected<?php } ?> value="<?php esc_attr_e($post_type->name);?>" ><?php esc_html_e($post_type->label);?></option>
                                                        <?php } ?>
                                                    </select>
                                                </span>
                                                <?php
                                                $post_custom = get_post_meta($id,'post_custom',true);
                                                ?>
                                                <label for="js-example-basic-hide-search-multi-ajax" style="display: block;"><?php _e('Select custom','CPWPWM');?></label>
                                                <span class="select2_post_type">
                                                    <select id="js-example-basic-hide-search-multi-ajax" name="post_custom[]" class="js-states form-control select2-hidden-accessible" multiple="" data-select2-id="select2-data-js-example-basic-hide-search-multi-ajax" tabindex="-1" aria-hidden="true">
                                                        <?php if(is_array($post_custom) && !empty($post_custom)){?>
                                                        <?php foreach ($post_custom as $postCustom){?>
                                                            <option value="<?php esc_attr_e(absint($postCustom));?>" selected> <?php echo get_post_field('post_title', absint($postCustom));?> </option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <?php
                                                $cssCustom = get_post_meta($id,'cssCustom',true);
                                                if(empty($cssCustom)){
                                                    $cssCustom = '.CPWPWM_Modal_dialog{
	
}
.CPWPWM_Modal_content{
	
}
.CPWPWM_Modal_close{
	
}
.CPWPWM_Modal_header{
	
}
.CPWPWM_Modal_h5{
	
}
.CPWPWM_Modal_body{
	
}
.CPWPWM_Modal_footer{
	
}
.CPWPWM_Modal_close_btn{
	
}';
                                                }
                                                ?>
                                                    <label for="fancy-textarea"><?php _e('Custom Css','CPWPWM');?></label>
                                                    <textarea onchange="showValues()" data-type="content" data-changeClass="test_css" data-change-type="" data-style=""   id="fancy-textarea" name="cssCustom"><?php echo esc_textarea($cssCustom);?></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-0 pl-0 pb-0 pr-0 mt-0">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0 mt-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <?php _e('Triggers','CPWPWM');?>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <!-- no no -->
                                            <div class="form-group filter">
                                                <label for="trigger"><?php _e('Trigger','CPWPWM');?></label>
                                                <?php
                                                $Trigger = get_post_meta($id,'trigger',true);
                                                ?>
                                                <select  name="trigger" id="trigger" class="form-control">
                                                    <option value="Click" <?php if($Trigger == 'Click'){?> selected<?php } ?>><?php _e('Click','CPWPWM');?></option>
                                                    <option value="Auto" <?php if($Trigger == 'Auto'){?> selected<?php } ?>><?php _e('Auto','CPWPWM');?></option>
                                                    <option value="Hover" <?php if($Trigger == 'Hover'){?> selected<?php } ?>><?php _e('Hover','CPWPWM');?></option>
                                                    <option value="scroll_down" <?php if($Trigger == 'scroll_down'){?> selected<?php } ?>><?php _e('Scroll down','CPWPWM');?></option>
                                                    <option value="Exit" <?php if($Trigger == 'Exit'){?> selected<?php } ?>><?php _e('Exit','CPWPWM');?></option>
                                                </select>

                                            </div>
                                            <div class="form-group filter" id="trigger_info_div" style="display: none;">
                                                <label for="trigger_info" id="trigger_info_label"><?php _e('Trigger data','CPWPWM');?></label>
                                                <?php
                                                $trigger_info = get_post_meta($id,'trigger_info',true);
                                                ?>
                                                <input type="text"  name="trigger_info"  id="trigger_info" class="form-control" value="<?php esc_attr_e($trigger_info);?>">
                                                <p class="description" id="trigger_info_description"></p>
                                            </div>

                                            <div style="clear: both;"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-0 pl-0 pb-0 pr-0 mt-0">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0 mt-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <?php _e('Closing','CPWPWM');?>
                                            </button>
                                        </h5>
                                    </div>
                                    <!-- no no -->
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                        <div class="card-body">


                                            <div class="form-group filter">
                                                <label for="close"><?php _e('Close','CPWPWM');?></label>
                                                <?php
                                                $close = get_post_meta($id,'close',true);
                                                ?>
                                                <select  name="close" id="close" class="form-control">
                                                    <option value="Click" <?php if($close == 'Click'){?> selected<?php } ?>><?php _e('Click','CPWPWM');?></option>
                                                    <option value="auto" <?php if($close == 'auto'){?> selected<?php } ?>><?php _e('Auto','CPWPWM');?></option>
                                                </select>

                                            </div>
                                            <div class="form-group filter" id="close_info_div" style="display: none;">
                                                <label for="close_info" id="close_info_label"><?php _e('close data','CPWPWM');?></label>
                                                <?php
                                                $close_info = get_post_meta($id,'close_info',true);
                                                ?>
                                                <input type="text"  name="close_info"  id="close_info" class="form-control" value="<?php esc_attr_e($close_info);?>">
                                                <p class="description" id="close_info_description"></p>
                                            </div>


                                            <div style="clear: both;"></div>
                                        </div>
                                    </div>
                                </div>

                                    <div class="card  pt-0 pl-0 pb-0 pr-0 mt-0 mb-3">
                                        <div class="card-header" id="headingshortcode">
                                            <h5 class="mb-0 mt-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseshortcode" aria-expanded="false" aria-controls="collapseThree">
                                                    <?php _e('Shortcode','CPWPWM');?>
                                                </button>
                                            </h5>
                                        </div>
                                        <!-- no no -->
                                        <div id="collapseshortcode" class="collapse" aria-labelledby="headingshortcode" data-parent="#accordionExample">
                                            <div class="card-body">

                                                <label for="close_info"><?php _e('Shortcode','CPWPWM');?></label>

                                                <div class="comments column-comments shortcodecopy short_style" data-colname="Comments" >
                                                    [CPWPWM_MODAL id="<?php echo absint($id); ?>"]
                                                </div>
                                                <label for="close_info"><?php _e('php Shortcode','CPWPWM');?></label>

                                                <div class="comments column-comments shortcodephpcopy short_style" data-colname="Comments" >
                                                    &lt;?php  echo do_shortcode( '[CPWPWM_MODAL id="<?php echo absint($id); ?>"]' );?&gt;
                                                </div>

                                                <div style="clear: both;"></div>
                                            </div>
                                        </div>
                                    </div>

                            </div>

                            </form>


                        </div>
                        <div class="col-md-8" style="height: 90vmin;">

                            <div class="col-12" id="modal_fix" style="width: 100% !important;">


                                <div class="CPWPWM_Modal_modal" style="width: 100%;height: 100vh;" tabindex="-1" role="dialog">
                                    <div class="modaltest modal-dialog CPWPWM_Modal_dialog animate__animated " role="document">
                                        <div class="modal-content CPWPWM_Modal_content">
                                            <button type="button" class="close close-modals CPWPWM_Modal_close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <div class="modal-header CPWPWM_Modal_header">
                                                <h5 class="modal-title mt-0 CPWPWM_Modal_h5"></h5>
                                            </div>
                                            <div class="modal-body CPWPWM_Modal_body">
                                                <p></p>
                                            </div>
                                            <div class="modal-footer CPWPWM_Modal_footer">
                                                <button type="button" class="btn btn-secondary CPWPWM_Modal_close_btn" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="col-12" id="row_popup_div"></div>
                    </div>
                </div>


            <style id="test_css" class="test_css"></style>
            <script>
                jQuery(document).ready(function() {
                    showValues();
                });
            </script>
                <?php
        }
        public function page(){

            if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
                $id = absint($_GET['delete_id']);
                $post = get_post($id);
                if (!$post || $post->post_type != 'cpwpwm_post') {
                    $ret = array('status' => false);
                } else {
                    wp_delete_post($id);
                    $ret = array('status' => true);
                }
            }

            ?>
                <div id="ck_editor_wordpress" style="display: none">
                    <?php echo wp_editor( '', 'modal_body' ,array(
                        'media_buttons'       => true,
                        'default_editor'      => 'tinymce',
                        'drag_drop_upload'    => true,
                        'textarea_name'       => 'modal_body',
                        'textarea_rows'       => 5,
                        'tabindex'            => 0,
                        'teeny'               => true,
                        '_content_editor_dfw' => false,
                        'tinymce'             => true,
                        'quicktags'           => true,
                        'dfw' => false, // replace the default fullscreen with DFW


                    ));?>
                </div>
            <div class="wrap">
                <?php if(isset($ret) && isset($ret['status']) && $ret['status'] == true){ ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e( 'deleted Done', 'CPWPWM' ); ?></p>
                </div>
                <?php } ?>
                <?php if(isset($ret) && isset($ret['status']) && $ret['status'] == false){ ?>
                    <div class="notice notice-error is-dismissible">
                        <p><?php _e( 'popup not found', 'CPWPWM' ); ?></p>
                    </div>
                <?php } ?>
                <h1 class="wp-heading-inline"> <?php esc_html_e(__('Add New','CPWPWM')); ?></h1>

                <a href="#" class="page-title-action" data-toggle="modal" data-target="#submitModal">Add New</a>
                <div id="submitModal" class="multi-step">
                </div>
                <hr class="wp-header-end">
                <form id="posts-filter" method="get">

                    <?php
                        // WP_Query arguments

                        $args = array(
                            'post_type' => 'cpwpwm_post',

                        );
                        $query = new WP_Query( $args );
                        if ( $query->have_posts() ) {
                    ?>
                    <h2 class="screen-reader-text">Pages list</h2>
                    <table class="wp-list-table widefat fixed striped table-view-list pages">
                        <thead>
                        <tr>

                            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                                <a href="#">
                                    <span>Title</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="ShortCode" style="width: 190px;" class="manage-column column-author"><a href="#"><?php esc_html_e(__('ShortCode','CPWPWM')); ?></a></th>
                            <th scope="col" id="ShortCode-php" style="width: 385px;" class="manage-column column-author"><a href="#"><?php esc_html_e(__('ShortCode php','CPWPWM')); ?></a></th>
                            <th scope="col" id="date" class="manage-column column-date sortable asc">
                                <a href="#">
                                    <span>Date</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                        </tr>
                        </thead>

                        <tbody id="the-list">
                        <?php
                       
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                ?>
                                <tr id = "post-<?php the_ID();?>" class="iedit author-self level-0 post-<?php the_ID();?> type-page status-publish hentry" >

                                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title"><div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                                        <strong><a class="row-title" href="#" aria-label="“Cart” (Edit)"><?php the_title();?></a></strong>

                                        <div class="row-actions">
                                    <span class="edit">
                                        <a href="<?php echo admin_url();?>/admin.php?page=CPWPWM_DATA_update&id=<?php the_ID();?>" aria-label="Edit “Cart”">Edit</a> |
                                    </span>
                                    <span class="trash">
                                        <a onclick="return confirm('<?php _e( 'Are you sure you want to delete this popup?', 'CPWPWM' ); ?>');" href="<?php echo admin_url();?>/admin.php?page=CPWPWM_DATA&delete_id=<?php the_ID();?>" class="submitdelete" aria-label="Move “Cart” to the Trash">Trash</a> |
                                    </span>
                                     <span class="edit">
                                        <a href="<?php echo admin_url();?>/admin.php?page=CPWPWM_DATA&popup_download_data_id=<?php the_ID();?>" aria-label="Edit “Cart”">export</a> 
                                    </span>
                                        </div>

                                    </td>

                                    <td class="comments column-comments shortcodecopy" data-colname="Comments" >
                                        [CPWPWM_MODAL id="<?php echo get_the_ID(); ?>"]
                                    </td>
                                    <td class="comments column-comments shortcodephpcopy" data-colname="Comments" >
                                        &lt;?php  echo do_shortcode( '[CPWPWM_MODAL id="<?php echo get_the_ID(); ?>"]' );?&gt;
                                    </td>
                                    <td class="date column-date" data-colname="Date">
                                        Published<br><?php the_date(); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                        </tbody>

                        <tfoot>
                        <tr>

                            <th scope="col" class="manage-column column-title column-primary sortable desc">
                                <a href="#">
                                    <span>Title</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" id="ShortCode" class="manage-column column-author"><a href="#"><?php esc_html_e(__('ShortCode','CPWPWM')); ?></a></th>
                            <th scope="col" id="ShortCode-php" class="manage-column column-author"><a href="#"><?php esc_html_e(__('ShortCode php','CPWPWM')); ?></a></th>
                            <th scope="col" class="manage-column column-date sortable asc">
                                <a href="#">
                                    <span>Date</span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                        </tr>
                        </tfoot>

                    </table>
<?php
}else{ ?>
<div style="margin: 65px auto;width: 400px;text-align: center;">
    <a href="#" data-toggle="modal" data-target="#submitModal">
    <img src="<?php echo CPWPWM_PLUGIN_URL;?>/assets/img/pop.png">
    </a>
    <h2 style="color: #444;font-size: 1.5em;margin: 40px 0px;">Ready to start creating something awesome?</h2>
    <a href="#" style="background-color: #39c0ba;webkit-box-shadow: rgb(0 0 0 / 16%) 0 2px 5px 0, rgb(0 0 0 / 12%) 0 2px 10px 0;box-shadow: rgb(0 0 0 / 16%) 0 2px 5px 0, rgb(0 0 0 / 12%) 0 2px 10px 0;cursor: pointer;font-weight: 700;text-transform: uppercase;text-decoration: none;white-space: normal;overflow-wrap: break-word;color: #fff;line-height: 1.5;-webkit-transition: color .15s ease-in-out 0s,background-color .15s ease-in-out 0s,border-color .15s ease-in-out 0s,-webkit-box-shadow .15s ease-in-out 0s;-o-transition: color .15s ease-in-out 0s,background-color .15s ease-in-out 0s,border-color .15s ease-in-out 0s,box-shadow .15s ease-in-out 0s;transition: color .15s ease-in-out 0s,background-color .15s ease-in-out 0s,border-color .15s ease-in-out 0s,box-shadow .15s ease-in-out 0s;transition: color .15s ease-in-out 0s,background-color .15s ease-in-out 0s,border-color .15s ease-in-out 0s,box-shadow .15s ease-in-out 0s,-webkit-box-shadow .15s ease-in-out 0s;margin: .375rem;border-width: 0;border-style: initial;border-color: initial;-webkit-border-image: initial;-o-border-image: initial;border-image: initial;-webkit-border-radius: .125rem;border-radius: .125rem;position: relative;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;-webkit-tap-highlight-color: transparent;font-size: 18px;padding: .5rem 1.6rem;" data-toggle="modal" data-target="#submitModal">ADD POPUP</a>
</div>
<?php }
                        wp_reset_postdata();
                        ?>
                </form>
            </div>


<?php

        }
        function popup_download_data() {
        
            
            //Check if invoice is set and get value = the path of the invoice
            if ( isset( $_REQUEST['popup_download_data_id'] ) ) {
                $id = absint($_REQUEST['popup_download_data_id']);
                $file = CPWPWM_DIR."data.txt";
                 $data ='$'; 
                    $data .= "data = array(
                       'template_position' => '".get_post_meta($id,'template_position',true)."',
                        'post_content' =>'".get_post_field('post_content', $id,'db')."',
                        'post_title' =>'".get_post_field('post_title', $id,'db')."',
                        'Show_popup_header' =>'".get_post_meta($id,'Show_popup_header',true)."',
                        'Show_popup_footer' =>'".get_post_meta($id,'Show_popup_footer',true)."',
                        'width' =>'".get_post_meta($id,'width',true)."',
                        'minWidth' =>'".get_post_meta($id,'minWidth',true)."',
                        'maxWidth' =>'".get_post_meta($id,'maxWidth',true)."',
                        'AnimationTime' =>'".get_post_meta($id,'AnimationTime',true)."',
                        'Animationtype' =>'".get_post_meta($id,'Animationtype',true)."',
                        'showing' =>'".get_post_meta($id,'showing',true)."',
                        'post_types' =>'".get_post_meta($id,'post_types',true)."',
                        'post_custom' =>'".get_post_meta($id,'post_custom',true)."',
                        'cssCustom' =>'".get_post_meta($id,'cssCustom',true)."',
                        'trigger' =>'".get_post_meta($id,'trigger',true)."',
                        'close' =>'".get_post_meta($id,'close',true)."',
                        'close_info' =>'".get_post_meta($id,'close_info',true)."',
                        'trigger_info' =>'".get_post_meta($id,'trigger_info',true)."',
                        'popup_status' =>'".get_post_meta($id,'popup_status',true)."',
                        'home_page' =>'".get_post_meta($id,'home_page',true)."',
                        'all_site' =>'".get_post_meta($id,'all_site',true)."',
                        'popup_status' =>'publish'
                        );";
                   //print_r($id);exit;
                if ( is_admin() && file_exists( $file ) ) {
                    
                    $txt = fopen($file, "w") or die("Unable to open file!");
                    fwrite($txt, $data);
                    fclose($txt);
                    
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename='.basename($file));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    header("Content-Type: text/plain");
                    readfile($file);
                                        
                    exit();
        
                }
            }
        }
        function my_enqueue($hook){
            if (strpos($hook, 'CPWPWM_DATA') !== false || strpos($hook, 'CPWPWM_DATA_update') !== false) {
                wp_enqueue_script('jquery-scrolltofixed-min', plugin_dir_url(__FILE__) . '../assets/js/jquery-scrolltofixed-min.js?'.time(), array('jquery'),'',false );
                wp_enqueue_script('modal-content-min', plugin_dir_url(__FILE__) . '../assets/js/modalcontent.min.js', array('jquery'),'',false );
                wp_enqueue_script('MultiStep-min', plugin_dir_url(__FILE__) . '../assets/js/MultiStep.min.js?fe', array('jquery') ,'',false);
                wp_enqueue_script('select2-min', plugin_dir_url(__FILE__) . '../assets/js/select2.min.js', array('jquery') ,'',true);
                wp_enqueue_style('modal-content', plugin_dir_url(__FILE__) . '../assets/css/modalcontent.min.css' );
                wp_enqueue_style('MultiStep-min', plugin_dir_url(__FILE__) . '../assets/css/MultiStep.min.css' );
                wp_enqueue_style('MultiStep-theme-min', plugin_dir_url(__FILE__) . '../assets/css/MultiStep-theme.min.css' );
                wp_enqueue_style('select2-min', plugin_dir_url(__FILE__) . '../assets/css/select2.min.css' );
                wp_enqueue_style('animate-min', plugin_dir_url(__FILE__) . '../assets/css/animate.min.css' );
                wp_enqueue_script('mycustom', plugin_dir_url(__FILE__) . '../assets/js/custom.js?'.time(), array( 'jquery', 'select2-min' ) ,'',true);

                $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
                wp_localize_script('jquery', 'cm_settings', $cm_settings);

                wp_enqueue_script('wp-theme-plugin-editor');
                wp_enqueue_style('wp-codemirror');
            }
        }

    }


    new CPWPWM_ADMIN_PAGE();
}

