<?php
class BraftonMarpro {

    public function __construct(){
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
        if($static == 'on' && $marproId != ''){
            echo $pumpkin;   
        }
    }
    //add needed marpro css to wp_head()
    static function MarproHeadScripts(){
        
    }
}
?>