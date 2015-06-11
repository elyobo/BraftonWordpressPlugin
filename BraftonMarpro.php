<?php
//include 'MarproWidget.php';
class BraftonMarpro {
    //constructs the marpo widget
    public function __construct(){
        add_action('widgets_init', create_function('', 'return register_widget("CallToAction_Widget");'));
    }
    
    static function MarproScript(){
        $static = BraftonOptions::getSingleOption('braftonMarproStatus');
        $marproId = BraftonOptions::getSingleOption('braftonMarproId');
        $domain = BraftonOptions::getSingleOption('braftonApiDomain');
        $domain = str_replace('api', '', $domain);
        $pumpkin =<<<EOC
            <script>if(typeof angular == 'undefined') {
	(function(w,pk){var s=w.createElement('script');s.type='text/javascript';s.async=true;s.src='//pumpkin$domain/pumpkin.js';var f=w.getElementsByTagName('script')[0];f.parentNode.insertBefore(s,f);if(!pk.__S){window._pk=pk;pk.__S = 1.1;}pk.host='conversion$domain';pk.clientId='$marproId';})(document,window._pk||[])}
</script>
EOC;
        if($static == 'on'){
            echo $pumpkin;   
        }
    }
    //add needed marpro css to wp_head()
    static function MarproHeadScripts(){
        
    }
}
class CallToAction_Widget extends WP_Widget {

    
    function CallToAction_Widget(){

        parent::WP_Widget(false, $name = __('Call To Action', 'wp_Widget_plugin'));
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('upload_media_widget', plugin_dir_url(__FILE__) . 'js/upload-media.js', array('jquery'));
        wp_enqueue_style('thickbox');

    }
    
    //Generates the form on the widgets page to populate the individual widget
    function form($instance){

        if($instance){

            $title = $instance['title'];
            $linktext = $instance['linktext'];
            $linkto = $instance['linkto'];
            $image = $instance['image'];
            $marpro = $instance['marpro'];
            $img_support = $instance['img_support'];

        } else{

            $title = '';
            $linktext = '';
            $linkto = 'javascript:void(0)';
            $image = '';
            $marpro = '';
            $img_support = 0;

        }

        ?>
        <style>
            input.upload_image_button {
                vertical-align: top;
            }
            span.call-to-action-info {
                font-size: 11px;
                margin-left:10px;
            }
            
        </style>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'CallToAction_Widget_plugin'); ?><span class="call-to-action-info">* text displayed to grab the users attention</span></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Call To Action Image', 'cta_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" style="margin-bottom:5px;"/><input type="button" class="upload_image_button" value="Add Image"><img src="<?php echo $image; ?>" style="width:75%;height:auto"><br>
            <br/><label for="<?php echo $this->get_field_id('img_support'); ?>"><?php _e('', 'cta_widget_plugin'); ?>Check this box to turn the image into the CTA</label><br/>
            Image Link <input class="widefat" id="<?php echo $this->get_field_id('img_support'); ?>" name="<?php echo $this->get_field_name('img_support'); ?>" type="checkbox" value="1" <?php if($img_support){ echo 'checked'; } ?>/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link Text', 'cta_widget_plugin'); ?><span class="call-to-action-info">*Text that is clickable</span></label>
            <input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo $linktext; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('marpro'); ?>"><?php _e('Marpro Form ID', 'cta_widget_plugin'); ?><span class="call-to-action-info">*If usign marpro form be sure link field is set to 'javascript:void(0)'</span> </label>
            <input class="widefat" id="<?php echo $this->get_field_id('marpro'); ?>" name="<?php echo $this->get_field_name('marpro'); ?>" type="text" value="<?php echo $marpro; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('linkto'); ?>"><?php _e('Link', 'cta_widget_plugin'); ?><span class="call-to-action-info">*page this link goes to.  If using for other purpose leave default '#'</span></label>
            <input class="widefat" id="<?php echo $this->get_field_id('linkto'); ?>" name="<?php echo $this->get_field_name('linkto'); ?>" type="text" value="<?php echo $linkto; ?>"/>
        </p>

   <?php }

    
    function update($new_instance, $old_instance){

        $instance = $old_instance;

        $instance['title'] = $new_instance['title'];
        $instance['linktext'] = $new_instance['linktext'];
        $instance['linkto'] = $new_instance['linkto'];
        $instance['image'] = $new_instance['image'];
        $instance['marpro'] = $new_instance['marpro'];
        $instance['img_support'] = $new_instance['img_support'];

        return $instance; 

    }


    function widget($args, $instance){

        $title = $instance['title'];
        $linktext = $instance['linktext'];
        $linkto = $instance['linkto'];
        $image = $instance['image'];
        $marpro = $instance['marpro'];
        $img_support = $instance['img_support'];
        echo $before_widget;
        
        if($img_support){ ?>
        <!-- Enter what happens if the image is the cta -->
        <div class="call-to-action alt">
            <div class="call-to-action-img-cont"><a href="<?php echo $linkto; ?>" <?php if($marpro != ''){ ?>data-br-form-id="<?php echo $marpro; ?>"<?php } ?> class="br-form-link cta-link"><img src="<?php echo $image; ?>" class="call-to-action-widget-image"></a>
            </div>
        </div>
        <?php }
        else{
        ?>
        <div class="call-to-action alt">
            <div class="call-to-action-img-cont"><img src="<?php echo $image; ?>" class="call-to-action-widget-image"></div>
            <div class="call-to-action-text-cont"><?php echo $title; ?></div>
            <a href="<?php echo $linkto; ?>" <?php if($marpro != ''){ ?>data-br-form-id="<?php echo $marpro; ?>"<?php } ?> class="br-form-link cta-link"><?php echo $linktext; ?></a>
        </div>
        <?php
        }
        
        echo $after_widget;

    }

    

}
?>