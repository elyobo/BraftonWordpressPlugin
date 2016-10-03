<?php
class CustomTypeDateArchives_Widget extends WP_Widget {
    public $typeName;
    public $siteUrl;
    public $slugName;
    function __construct(){
        $this->typeName = ucfirst(BraftonOptions::getSingleOption('braftonCustomSlug'));
        $this->slugName = strtolower(str_replace(' ', '-', preg_replace("/[^a-z0-9 ]/i", "",$this->typeName) ));
        $this->siteUrl = home_url();
        parent::__construct($this->slugName.'_DateArchive', strtoupper(BRAFTON_BRAND).':'.$this->typeName.' Archives',
              array(
                  'classname'   => 'widget_archive '.$this->slugName.'_archives',
                  'description' => 'A list or dropdown of '.$this->typeName.' montly archives'
              )
        );
    }

    function form($instance){

            $title = $instance ? $instance['title'] : '';
            $isdrp = $instance ? (boolean)$instance['isdrp'] : false;
            $count = $instance ? (boolean)$instance['incnt'] : false;


        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'CustomTypeDateArchives_Widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('isdrp'); ?>" name="<?php echo $this->get_field_name('isdrp'); ?>" type="checkbox" value="1" <?php if($isdrp){ echo 'checked'; } ?>/>
            <label for="<?php echo $this->get_field_id('isdrp'); ?>"><?php _e('Display as Dropdown', 'CustomTypeDateArchives_Widget_plugin'); ?></label>

        </p>
        <p>
            <input id="<?php echo $this->get_field_id('incnt'); ?>" name="<?php echo $this->get_field_name('incnt'); ?>" type="checkbox" value="1" <?php if($count){ echo 'checked'; } ?>/>
            <label for="<?php echo $this->get_field_id('incnt'); ?>"><?php _e('Include Post Count', 'CustomTypeDateArchives_Widget_plugin'); ?></label>

        </p>
   <?php }


    function update($new_instance, $old_instance){

        $instance = $old_instance;

        $instance['title'] = $new_instance['title'];
        $instance['isdrp'] = $new_instance['isdrp'];
        $instance['incnt'] = $new_instance['incnt'];
        return $instance;

    }


    function widget($args, $instance){

        $title = $instance['title'] != ''? $instance['title'] : $this->typeName.' Archives';
        if($isdrp = (boolean)$instance['isdrp']){
            $format = 'option';
        }else{
            $format = 'html';
        };
        $count = (boolean)$instance['incnt'];
        $siteUrl = home_url();
        $url = $siteUrl.'/'.strtolower($this->slugName);

        echo $args['before_widget'];
        if($title != '' || $title != null){
            echo $args['before_title'];
            echo $title;
            echo $args['after_title'];
        }
        add_filter('month_link', array($this, 'modifyDateLinks'), 10,3);
        add_filter('getarchives_where', array($this, 'modifySqlWhere'), 10, 2);
        if($isdrp){
            echo '<select id="'.$this->slugName.'_archive_drpdwn">';
            echo '<option>Select Date</option>';
        }else{
            echo '<ul>';
        }
        wp_get_archives(array(
            'type'=> 'monthly',
            'format'    => $format,
            'show_post_count'    => $count
        ));
        if($isdrp){
            echo '</select>';
            ?>
        <script type="text/javascript">
        /* <![CDATA[ */
        (function() {
            var braftonArchivedropdown = document.getElementById( "<?php echo $this->slugName.'_archive_drpdwn'; ?>" );
            function braftonArchiveonCatChange() {
                if ( braftonArchivedropdown.options[ braftonArchivedropdown.selectedIndex ].value != -1 ) {
                    location.href = braftonArchivedropdown.options[ braftonArchivedropdown.selectedIndex ].value;
                }
            }
            braftonArchivedropdown.onchange = braftonArchiveonCatChange;
        })();
        /* ]]> */
        </script>
        <?php
        }else{
            echo '</ul>';
        }
        echo $args['after_widget'];
        remove_filter('month_link', array($this, 'modifyDateLinks'));
        remove_filter('getarchives_where', array($this, 'modifySqlWhere'));

    }
    function modifyDateLinks($monthlink, $year, $month){
        return $this->siteUrl.'/'.strtolower($this->slugName).'/archive/'.$year.'/'.$month;
    }
    function modifySqlWhere($sql_where, $r){
        $sql_where = str_replace("post_type = 'post'", "post_type = '".strtolower($this->slugName)."'", $sql_where);
        return $sql_where;
    }
}
