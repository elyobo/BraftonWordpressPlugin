<?php
class BraftonMarpro {

    public function __construct(){
        add_action('widgets_init', array('BraftonWidgets', 'CallToAction'));
        add_action('wp_footer', array($this, 'MarproScript'));
        add_shortcode('arch_form', array($this, 'marproShortCode'));
    } 
    static function MarproScript(){
        $marproId = BraftonOptions::getSingleOption('braftonMarproId');
        if(!(bool)$marproId){
            return;
        }
        $domain = BraftonOptions::getSingleOption('braftonApiDomain');
        $domain = str_replace('api', '', $domain);
        $pumpkin =<<<EOC
            <script>if(typeof angular == 'undefined') {
	(function(w,pk){var s=w.createElement('script');s.type='text/javascript';s.async=true;s.src='//pumpkin$domain/pumpkin.js';var f=w.getElementsByTagName('script')[0];f.parentNode.insertBefore(s,f);if(!pk.__S){window._pk=pk;pk.__S = 1.1;}pk.host='conversion$domain';pk.clientId='$marproId';})(document,window._pk||[])}
</script>
EOC;
        echo $pumpkin;
    }

    static function marproShortCode($args, $content = null){
        $args = shortcode_atts(array(
            'id'    => null,
            'type'  => 'native'
            ), $args);
        if($args['id'] == null){
            return null;
        }
        switch($args['type']){
            case 'native':
                return self::native_form($args['id']);
                break;
            case 'iframe':
                return self::iframe_form($args['id']);
                break;
            case 'popup':
                return self::popup_form($args['id'], $content);
                break;
            default:
                return null;
                break;
        }
    }
    
    static function native_form($id){
        $form = sprintf('<div data-br-form-id="%s" class="br-native-form"></div>', $id);
        return $form;
    }
    
    static function iframe_form($id){
        $archId = BraftonOptions::getSingleOption('braftonMarproId');
        $domain = str_replace('api', 'conversion', BraftonOptions::getSingleOption('braftonApiDomain'));
        $form = sprintf('<iframe src="//%s/forms/lead-gen/%s/%s"></iframe>', $domain,$archId,$id);
        return $form;
    }
    
    static function popup_form($id, $content){
        $form = sprintf('<a href="javascript:void(0)" data-br-form-id="%s" class="br-form-link">%s</a>', $id, $content);
        return $form;
    }
}
?>