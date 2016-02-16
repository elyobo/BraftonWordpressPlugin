<?php
class BraftonWidgets {

    static function CallToAction(){
        include_once 'widgets/CallToAction.php';
        register_widget('CallToAction_Widget');
    }
    static function CustomTypeCategory(){
        include_once 'widgets/CustomTypeCategory.php';
        register_widget('CustomTypeCategory_Widget');
    }
    static function CustomTypeDateArchives(){
        include_once 'widgets/customTypeDateArchives.php';
        register_widget('customTypeDateArchives_Widget');
    }
}