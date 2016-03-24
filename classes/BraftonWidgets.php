<?php
class BraftonWidgets {

    static function CallToAction(){
        include_once BRAFTON_DIR.'widgets/CallToAction.php';
        register_widget('CallToAction_Widget');
    }
    static function CustomTypeCategory(){
        include_once BRAFTON_DIR.'widgets/CustomTypeCategory.php';
        register_widget('CustomTypeCategory_Widget');
    }
    static function CustomTypeDateArchives(){
        include_once BRAFTON_DIR.'widgets/customTypeDateArchives.php';
        register_widget('customTypeDateArchives_Widget');
    }
}