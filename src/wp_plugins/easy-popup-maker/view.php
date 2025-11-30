<?php
if(!class_exists('CPWPWM_VIEW')){
    class CPWPWM_VIEW{
        public $popup_array = array();
        public function __construct(){
            add_action('init',array($this,'getPopup'),1,1);
            add_action('wp_enqueue_scripts', array($this,'my_enqueue'),1,2);
            add_shortcode( 'CPWPWM_MODAL', array($this,'shortCodeRun') );

        }
        public function getPopup(){
            if (is_admin()){
                return true;
            }
            $args = array(
                'post_type' => 'cpwpwm_post',
                'meta_query' => array(
                    array(
                        'key' => 'popup_status',
                        'value' => 'published',
                        'compare' => '=',
                    )
                )
            );
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $id = get_the_ID();
                    //print_r($id);
                    if(isset($this->popup_array[$id])){
                        continue;
                    }
                    if(!$this->setCookiesById($id)){
                        continue;
                    }
                    if(!$this->getPageToShow($id)){
                        continue;
                    }
                    //$showing = get_post_meta($id,'showing',true);

                    add_action( 'wp_footer', function () use ($id) {
                        $this->getPopupHtml($id);
                    });
                    $this->trigger($id);

                }
            }
        }
        public function shortCodeRun($id){
            if (is_admin()){
                return true;
            }
            $id = absint($id['id']);
            if(empty($id)){
               return false;
            }
            add_action( 'wp_footer', function () use ($id) {
                $this->getPopupHtml($id);
                $this->trigger($id);
            });
        }
        private function getPageToShow($id){
            $all_site = get_post_meta($id,'all_site',true);
            if($all_site == 'all_site'){
                return true;
            }
            $home_page = get_post_meta($id,'home_page',true);
            if($home_page == 'home_page' && is_front_page() && is_home()){
                return true;
            }
            $post_types = get_post_meta($id,'post_types',true);
            if( !empty($post_types) && (is_single() || is_page())){
                if(is_singular($post_types)){
                    return true;
                }
            }
            $post_custom = get_post_meta($id,'post_custom',true);
            if(!empty($post_custom) && (is_single() || is_page())){
                global $wp_query;
                foreach ($post_custom as $id){
                    if($id == $wp_query->post->ID){
                        return true;
                    }
                }
            }

            return false;
        }
        private function setCookiesById($id){
			ob_start();
            $data = true;
            $showing = get_post_meta($id,'showing',true);
        //    echo $showing;
            if($showing == 'once'){
                if(isset($_COOKIE['CPWPWM'.$id])) {
                    $data = false;
                }
                setcookie('CPWPWM'.$id, $id, time() + (86400 * 30));
            }else {
                $data = true;
            }
            return $data;
        }
        /**
         * @return html
         */
        private function getPopupHtml($id)
        {
            $this->popup_array[$id]=$id;
            $template_position = $this->getTemplatePosition($id);
            $Show_popup_header = get_post_meta($id,'Show_popup_header',true);
            $Show_popup_footer = get_post_meta($id,'Show_popup_footer',true);
            $getAnimate = $this->getAnimate($id);
            $getWidth = $this->getWidth($id);
            $cssCustom = get_post_meta($id,'cssCustom',true);
            $close = get_post_meta($id,'close',true);

            ?>
            <style>
                button.close.close-modals {
                    position: absolute;
                    right: 5px;
                    z-index: 99999999999999;
                }
                <?php esc_html_e(CPWPWM_replace_custom_css('#CPWPWM_Modal_'.absint($id),$cssCustom));?>
                #CPWPWM_Modal_<?php esc_attr_e($id);?> .CPWPWM_Modal_dialog{
                    <?php esc_attr_e($template_position);?>
                    <?php esc_attr_e($getWidth);?>
                }
            </style>
            <!-- Modal -->
            <div class="modal fade CPWPWM_Modal_modal"  <?php if($close == 'auto'){ ?> data-keyboard="false" data-backdrop="static" <?php } ?> id="CPWPWM_Modal_<?php esc_attr_e($id);?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog CPWPWM_Modal_dialog <?php esc_attr_e($getAnimate);?>" role="document">
                    <div class="modal-content CPWPWM_Modal_content">
                        <?php if($close != 'auto'){ ?>
                        <button type="button" class="close close-modals CPWPWM_Modal_close" id="CPWPWM_close_<?php esc_attr_e($id);?>" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php } ?>
                        <?php if($Show_popup_header != 'Show_popup_header'){ ?>
                        <div class="modal-header CPWPWM_Modal_header">
                            <h5 class="modal-title mt-0 CPWPWM_Modal_h5" id="exampleModalLabel"><?php echo get_post_field('post_title', absint($id));?></h5>
                        </div>
                        <?php } ?>
                        <div class="modal-body CPWPWM_Modal_body">
                            <?php echo apply_filters('the_content', get_post_field('post_content', absint($id)));?>
                        </div>
                        <?php if($Show_popup_footer != 'Show_popup_footer'){ ?>
                        <div class="modal-footer CPWPWM_Modal_footer">
                            <button type="button" class="btn btn-secondary CPWPWM_Modal_close_btn" data-dismiss="modal"><?php _e('Close','CPWPWM');?></button>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        private function trigger($id){

            $close = get_post_meta($id,'close',true);
            if($close == 'auto'){
                add_action( 'wp_footer', function () use ($id) {
                    $close_info = get_post_meta($id,'close_info',true);
                    if(empty($close_info)){
                        $close_info = 3;
                    }
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            setTimeout(function(){ jQuery('#CPWPWM_Modal_<?php echo absint($id);?>').modal('hide'); }, <?php echo absint($close_info);?>*1000);
                        });
                    </script>
                    <?php
                }, 100 );
            }

            $Trigger = get_post_meta($id,'trigger',true);

            if($Trigger == 'Click'){
                add_action( 'wp_footer', function () use ($id) {
                    $trigger_info = get_post_meta($id,'trigger_info',true);
                    if(empty($trigger_info)){
                        return true;
                    }
                    ?>
                    <script>
                        jQuery(document).ready(function() {

                            var el_click = "<?php echo esc_js($trigger_info);?>";
                            var el_onClock = '';
                            if(el_click.charAt(0) == '#' ||  el_click.charAt(0) == '.'){
                                el_onClock = el_click;
                            }else if(jQuery(el_click).length){
                                el_onClock = el_click;
                            }else if(jQuery('#'+el_click).length){
                                el_onClock = '#'+el_click;
                            }else if(jQuery('.'+el_click).length){
                                el_onClock = '.'+el_click;
                            }else{
                                return;
                            }
                            jQuery(document).on('click', el_onClock, function() {
                                <?php
                                $showing = get_post_meta($id,'showing',true);
                                if($showing == 'once'){ ?>
                                    if(localStorage.getItem("once_<?php echo esc_js($id);?>") == 'yes' ){
                                        return false;
                                    }
                                    localStorage.setItem("once_<?php echo esc_js($id);?>", 'yes');
                                <?php }else{ ?>
                                    localStorage.removeItem("once_<?php echo esc_js($id);?>");
                                <?php } ?>

                                jQuery('#CPWPWM_Modal_<?php echo absint($id);?>').modal('show');
                            });
                        });
                    </script>
                    <?php
                }, 100 );
            }else if($Trigger == 'Auto'){
                add_action( 'wp_footer', function () use ($id) {
                    $trigger_info = get_post_meta($id,'trigger_info',true);
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            setTimeout(function(){
                                <?php
                                $showing = get_post_meta($id,'showing',true);
                                if($showing == 'once'){ ?>
                                if(localStorage.getItem("once_<?php echo esc_js($id);?>") == 'yes' ){
                                    return false;
                                }
                                localStorage.setItem("once_<?php echo esc_js($id);?>", 'yes');
                                <?php }else{ ?>
                                localStorage.removeItem("once_<?php echo esc_js($id);?>");
                                <?php } ?>
                                jQuery('#CPWPWM_Modal_<?php echo absint($id);?>').modal('show'); }, <?php echo absint($trigger_info);?>*1000);
                        });
                    </script>
                    <?php
                }, 100 );
            }else if($Trigger == 'scroll_down'){
                add_action( 'wp_footer', function () use ($id) {
                    $trigger_info = get_post_meta($id,'trigger_info',true);
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            var number_open = 1;
                            jQuery(window).scroll(function() {
                                if ((jQuery(window).scrollTop() + jQuery(window).height()) * 100 / jQuery(document).height() >= <?php echo absint($trigger_info);?> && number_open == 1) {
                                    <?php
                                    $showing = get_post_meta($id,'showing',true);
                                    if($showing == 'once'){ ?>
                                    if(localStorage.getItem("once_<?php echo esc_js($id);?>") == 'yes' ){
                                        return false;
                                    }
                                    localStorage.setItem("once_<?php echo esc_js($id);?>", 'yes');
                                    <?php }else{ ?>
                                    localStorage.removeItem("once_<?php echo esc_js($id);?>");
                                    <?php } ?>
                                    jQuery('#CPWPWM_Modal_<?php echo absint($id);?>').modal('show');
                                    number_open = 2;
                                }
                            });
                        });
                    </script>
                    <?php
                }, 100 );
            }else if($Trigger == 'Hover'){
                add_action( 'wp_footer', function () use ($id) {
                    $trigger_info = get_post_meta($id,'trigger_info',true);
                    if(empty($trigger_info)){
                        return true;
                    }
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            var el_click = "<?php echo esc_js($trigger_info);?>";
                            var el_onClock = '';
                            if(el_click.charAt(0) == '#' ||  el_click.charAt(0) == '.'){
                                el_onClock = el_click;
                            }else if(jQuery(el_click).length){
                                el_onClock = el_click;
                            }else if(jQuery('#'+el_click).length){
                                el_onClock = '#'+el_click;
                            }else if(jQuery('.'+el_click).length){
                                el_onClock = '.'+el_click;
                            }else{
                                return;
                            }

                            jQuery( el_onClock ).hover(function() {
                                <?php
                                $showing = get_post_meta($id,'showing',true);
                                if($showing == 'once'){ ?>
                                if(localStorage.getItem("once_<?php echo esc_js($id);?>") == 'yes' ){
                                    return false;
                                }
                                localStorage.setItem("once_<?php echo esc_js($id);?>", 'yes');
                                <?php }else{ ?>
                                localStorage.removeItem("once_<?php echo esc_js($id);?>");
                                <?php } ?>
                                jQuery('#CPWPWM_Modal_<?php echo absint($id);?>').modal('show');
                            });
                        });
                    </script>
                    <?php
                }, 100 );
            }else if($Trigger == 'Exit'){
                add_action( 'wp_footer', function () use ($id) {
                    $trigger_info = get_post_meta($id,'trigger_info',true);
                    ?>
                    <script>
                        var show_in_this_page = 0;
                        jQuery(document).ready(function() {
                            jQuery("body").on({
                                mouseleave: function () {
                                    if(show_in_this_page){
                                        return false;
                                    }
                                    <?php
                                    $showing = get_post_meta($id,'showing',true);
                                    if($showing == 'once'){ ?>
                                    if(localStorage.getItem("once_<?php echo esc_js($id);?>") == 'yes' ){
                                        return false;
                                    }
                                    localStorage.setItem("once_<?php echo esc_js($id);?>", 'yes');
                                    <?php }else{ ?>
                                    localStorage.removeItem("once_<?php echo esc_js($id);?>");
                                    <?php } ?>
                                    show_in_this_page = 1;
                                    jQuery('#CPWPWM_Modal_<?php echo absint($id);?>').modal('show');
                                }
                            });
                        });
                    </script>
                    <?php
                }, 100 );
            }


        }
        private function getWidth($id){
            $width = get_post_meta($id,'width',true);
            $minWidth = get_post_meta($id,'minWidth',true);
            $maxWidth = get_post_meta($id,'maxWidth',true);
            $style = '';
            if($width && $width != '' && $width != 0 && $width != '0'){
                $style .= ' width:'.esc_attr($width).'%; ';
            }else {
                $style .= ' width:unset;';
            }
            if($minWidth && $minWidth != '' && $minWidth != 0 && $minWidth != '0'){
                $style .= ' min-width:'.esc_attr($minWidth).'px; ';
            }else{
                $style .= ' min-width:unset; ';
            }
            if($maxWidth && $maxWidth != '' && $maxWidth != 0 && $maxWidth != '0'){
                $style .= ' max-width:'.esc_attr($maxWidth).'px; ';
            }else{
                $style .= ' max-width:unset; ';
            }
            return $style;
        }
        private function getAnimate($id){
            $style = ' animate__animated ';
            $animateTime=get_post_meta($id,'AnimationTime',true);
            $animate=get_post_meta($id,'Animationtype',true);
            if($animateTime){
                $style .= ' animate__'.esc_html($animateTime).' ';
            }
            if($animate){
                $style .= ' animate__'.esc_html($animate).' ';
            }
            return $style;
        }
        private function getTemplatePosition($id){
            $template_position = get_post_meta($id,'template_position',true);
            if($template_position=='bottom-lift'){
                $style = "position:fixed;bottom:0;left:0;right:unset;top:unset;";
            }else if($template_position=='top-middle'){
                $style = "position:fixed;bottom:unset;left:0;right:0;top:0;";
            }else if($template_position=='right-top'){
                $style = "position:fixed;bottom:unset;left:unset;right:0;top:0;";
            }else if($template_position=='right-middle'){
                $style = "position:fixed;bottom:unset;left:unset;right:0;top:calc( 50% - 120px);transform:translateY(-50%);";
            }else if($template_position=='right-bottom'){
                $style = "position:fixed;bottom:0;left:unset;right:0;top:unset;";
            }else if($template_position=='middle'){
                $style = "position:fixed;bottom:0;left:0;right:0;top:calc( 50% - 120px);transform:translateY(-50%);";
            }else if($template_position=='left-middle'){
                $style = "position:fixed;bottom:unset;left:0;right:unset;top:calc( 50% - 120px);transform:translateY(-50%);";
            }else if($template_position=='bottom-middle'){
                $style = "position:fixed;bottom:0;left:0;right:0;top:unset;";
            }else if($template_position=='bottom-lift'){
                $style = "position:fixed;bottom:0;left:0;right:unset;top:unset;";
            }else{
                $style = '';
            }
            return $style;
        }
        public function my_enqueue(){
                wp_enqueue_script('modal-content-min', plugin_dir_url(__FILE__) . 'assets/js/modalcontent.min.js', array('jquery'),'',false );
                wp_enqueue_style('modal-content', plugin_dir_url(__FILE__) . 'assets/css/modalcontent.min.css' );
                wp_enqueue_style('animate-min', plugin_dir_url(__FILE__) . 'assets/css/animate.min.css' );
        }
    }
    new CPWPWM_VIEW();
}