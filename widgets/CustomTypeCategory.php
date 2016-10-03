<?php
class CustomTypeCategory_Widget extends WP_Widget {
    public $typeName;
    public $slugName;
    function __construct(){
        $this->typeName = ucfirst(BraftonOptions::getSingleOption('braftonCustomSlug'));
        $this->slugName = strtolower(str_replace(' ', '-', preg_replace("/[^a-z0-9 ]/i", "",$this->typeName) ));
        parent::__construct($this->slugName.'_Categories',
                          strtoupper(BRAFTON_BRAND).':'.$this->typeName.' Categories',
              array(
                  'classname'   => 'widget_categories '.$this->slugName.'_cats',
                  'description' => 'A list or dropdown of '.$this->typeName.' categories'
              )
        );
    }

    function form($instance){

            $title = $instance ? $instance['title'] : '';
            $isdrp = $instance ? (boolean)$instance['isdrp'] : false;
            $count = $instance ? (boolean)$instance['incnt'] : false;

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'CustomTypeCategory_Widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('isdrp'); ?>" name="<?php echo $this->get_field_name('isdrp'); ?>" type="checkbox" value="1" <?php if($isdrp){ echo 'checked'; } ?>/>
            <label for="<?php echo $this->get_field_id('isdrp'); ?>"><?php _e('Display as Dropdown', 'CustomTypeCategory_Widget_plugin'); ?></label>

        </p>
        <p>
            <input id="<?php echo $this->get_field_id('incnt'); ?>" name="<?php echo $this->get_field_name('incnt'); ?>" type="checkbox" value="1" <?php if($count){ echo 'checked'; } ?>/>
            <label for="<?php echo $this->get_field_id('incnt'); ?>"><?php _e('Include Post Count', 'CustomTypeCategory_Widget_plugin'); ?></label>

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

        $title = $instance['title'] != ''? $instance['title'] : $this->typeName.' Categories';
        $isdrp = (boolean)$instance['isdrp'];
        $count = (boolean)$instance['incnt'];
        $categories = get_categories();
        $siteUrl = home_url();
        $url = $siteUrl.'/'.strtolower($this->slugName);

        echo $args['before_widget'];
        if($title != '' || $title != null){
            echo $args['before_title'];
            echo $title;
            echo $args['after_title'];
        }
        $currentCat = false;
        if(is_category()){
            $currentCat = get_query_var('category_name');
        }
        if($isdrp){?>
        <select name="cat" id="<?php echo $this->slugName.'_cat_drop'; ?>" class="postform">
            <option value="-1">Select Category</option>
            <?php foreach($categories as $category){
            $q = new WP_Query('post_type='.strtolower($this->slugName).'&cat='.$category->term_id);
            if($q->found_posts == 0){ continue; } ?>
            <option class="level-0" value="<?php echo $category->slug; ?>" <?php if($currentCat){ echo $currentCat == $category->slug ? 'selected': ''; } ?>><?php echo $category->name; if($count){ echo ' ('.$q->found_posts.')'; } ?> </option>
            <?php } ?>
        </select>
        <script type="text/javascript">
        /* <![CDATA[ */
        (function() {
            var braftonCatdropdown = document.getElementById( "<?php echo $this->slugName.'_cat_drop'; ?>" );
            function braftonCatonCatChange() {
                if ( braftonCatdropdown.options[ braftonCatdropdown.selectedIndex ].value != -1 ) {
                    location.href = "<?php echo $url; ?>/category/" + braftonCatdropdown.options[ braftonCatdropdown.selectedIndex ].value;
                }
            }
            braftonCatdropdown.onchange = braftonCatonCatChange;
        })();
        /* ]]> */
        </script>
        <?php }else{
        ?>
        <ul>
            <?php foreach($categories as $category){
            $q = new WP_Query('post_type='.strtolower($this->slugName).'&cat='.$category->term_id);
            if($q->found_posts == 0){ continue; } ?>
            <li class="cat-item cat-item-<?php echo $category->term_id; ?> <?php if($currentCat){ echo $currentCat == $category->slug ? "current-cat" : ""; } ?>">
                <a href="<?php echo $url; ?>/category/<?php echo $category->slug; ?>/"><?php echo $category->name; if($count){ echo ' ('.$q->found_posts.')'; } ?></a>
            </li>
            <?php } ?>
        </ul>
        <?php
        }
        echo $args['after_widget'];

    }
}
