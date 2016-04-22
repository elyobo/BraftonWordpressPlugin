<?php
class CallToAction_Widget extends WP_Widget {
    
    function __construct(){

        //parent::__construct(false, $name = __('Call To Action', 'wp_Widget_plugin'));
        parent::__construct('call_to_action', 
                          strtoupper(BRAFTON_BRAND).': Call To Action', 
              array(
                  'classname'   => 'widget_call_to_action '.BRAFTON_BRAND.'_cta',
                  'description' => 'Custom Call to Action Widget. Can be used with '.BRAFTON_BRAND.' Arch Product'
              )
        );
        add_action('admin_enqueue_scripts', array($this, 'add_scripts'));

    }
    function add_scripts(){
        wp_enqueue_script('upload_media_widget', BRAFTON_ROOT . '/js/upload-media.js', array('jquery'));
        wp_enqueue_media();
    }
    //Generates the form on the widgets page to populate the individual widget
    function form($instance){
        
            $title = $instance ? $instance['title'] : '';
            $linktext = $instance ? $instance['linktext'] : '';
            $linkto = $instance ? $instance['linkto'] : 'javascript:void(0)';
            $image = $instance ? $instance['image'] : '';
            $marpro = $instance ? $instance['marpro'] : '';
            $img_support = $instance ? $instance['img_support'] : 0;

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
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" style="margin-bottom:5px;"/><input type="button" class="upload_image_button" value="Add Image" style="cursor:pointer;"><img src="<?php echo $image; ?>" style="width:75%;height:auto" class="pumpkin_widget"><br>
            <br/><label for="<?php echo $this->get_field_id('img_support'); ?>"><?php _e('', 'cta_widget_plugin'); ?>Check this box to turn the image into the CTA</label><br/>
            Image Link <input class="widefat" id="<?php echo $this->get_field_id('img_support'); ?>" name="<?php echo $this->get_field_name('img_support'); ?>" type="checkbox" value="1" <?php if($img_support){ echo 'checked'; } ?>/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link Text', 'cta_widget_plugin'); ?><span class="call-to-action-info">*Text that is clickable</span></label>
            <input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo $linktext; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('marpro'); ?>"><?php _e('Arch Form ID', 'cta_widget_plugin'); ?><span class="call-to-action-info">*If using Arch form be sure link field is set to 'javascript:void(0)'</span> </label>
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
        echo $args['before_widget'];
        
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
        
        echo $args['after_widget'];

    }

    

}