<?php
class BraftonCustomType {
    
    public function __cunstruct(){
        
    }
   static function BraftonInitializeType(){
       if(!BraftonOptions::getSingleOption('braftonArticlePostType')){
           return;
       }
       $brand = BraftonOptions::getSingleOption('braftonApiDomain');
       $brand = BraftonCustomType::switchCase($brand);
       //$slug = BraftonOptions::getSingleOption('braftonCustomSlug');
       $slug = BraftonOptions::getSingleOption('braftonCustomSlug')? BraftonOptions::getSingleOption('braftonCustomSlug'): 'content-blog';
       $post_args = array(
          'label'   => __($brand.' Content'),
          'labels'  => array(
              'name'    => __($brand.' Content'),
              'singular_name'   => __($brand.' Content'),
              'edit_item'   => __('edit')
            ),
          'description' => "$brand Content Imported from {$brand}'s XML API Feed.",
          'public'  => true,
          'has_archive' => true,
          'taxonomies'  => array('category', 'post_tag'),
          'rewrite' => array(
              'slug'    => $slug,
              'with_front'  => false,
              'feeds'   => true,
              'pages'   => true
            ),
          'supports'    => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
          'query_var'   => true
        );
       register_post_type( 'blog_content', $post_args);
       flush_rewrite_rules();
    }
    static function switchCase($brand){
        switch ($brand){
            case 'api.brafton.com':
            return 'Brafton';
            break;
            case 'api.contentlead.com':
            return 'ContentLEAD';
            break;
            case 'api.castleford.com.au':
            return 'Castleford';
            break;
        }
    }
    function BraftonIncludeContent( $query ) {
        if(BraftonOptions::getSingleOption('braftonArticlePostType')){
            if ( !is_admin() && $query->is_main_query() && ($query->is_home() || $query->is_category() || $query->is_archive() )) {
                $query->set( 'post_type', array( 'post', 'blog_content' ) );
            }
        }
    }
}
?>