<?php
if(!function_exists('sg_load')){$__v=phpversion();$__x=explode('.',$__v);$__v2=$__x[0].'.'.(int)$__x[1];$__u=strtolower(substr(php_uname(),0,3));$__ts=(@constant('PHP_ZTS') || @constant('ZEND_THREAD_SAFE')?'ts':'');$__f=$__f0='ixed.'.$__v2.$__ts.'.'.$__u;$__ff=$__ff0='ixed.'.$__v2.'.'.(int)$__x[2].$__ts.'.'.$__u;$__ed=@ini_get('extension_dir');$__e=$__e0=@realpath($__ed);$__dl=function_exists('dl') && function_exists('file_exists') && @ini_get('enable_dl') && !@ini_get('safe_mode');if($__dl && $__e && version_compare($__v,'5.2.5','<') && function_exists('getcwd') && function_exists('dirname')){$__d=$__d0=getcwd();if(@$__d[1]==':') {$__d=str_replace('\\','/',substr($__d,2));$__e=str_replace('\\','/',substr($__e,2));}$__e.=($__h=str_repeat('/..',substr_count($__e,'/')));$__f='/ixed/'.$__f0;$__ff='/ixed/'.$__ff0;while(!file_exists($__e.$__d.$__ff) && !file_exists($__e.$__d.$__f) && strlen($__d)>1){$__d=dirname($__d);}if(file_exists($__e.$__d.$__ff)) dl($__h.$__d.$__ff); else if(file_exists($__e.$__d.$__f)) dl($__h.$__d.$__f);}if(!function_exists('sg_load') && $__dl && $__e0){if(file_exists($__e0.'/'.$__ff0)) dl($__ff0); else if(file_exists($__e0.'/'.$__f0)) dl($__f0);}if(!function_exists('sg_load')){$__ixedurl='http://www.sourceguardian.com/loaders/download.php?php_v='.urlencode($__v).'&php_ts='.($__ts?'1':'0').'&php_is='.@constant('PHP_INT_SIZE').'&os_s='.urlencode(php_uname('s')).'&os_r='.urlencode(php_uname('r')).'&os_m='.urlencode(php_uname('m'));$__sapi=php_sapi_name();if(!$__e0) $__e0=$__ed;if(function_exists('php_ini_loaded_file')) $__ini=php_ini_loaded_file(); else $__ini='php.ini';if((substr($__sapi,0,3)=='cgi')||($__sapi=='cli')||($__sapi=='embed')){$__msg="\nPHP script '".__FILE__."' is protected by SourceGuardian and requires a SourceGuardian loader '".$__f0."' to be installed.\n\n1) Download the required loader '".$__f0."' from the SourceGuardian site: ".$__ixedurl."\n2) Install the loader to ";if(isset($__d0)){$__msg.=$__d0.DIRECTORY_SEPARATOR.'ixed';}else{$__msg.=$__e0;if(!$__dl){$__msg.="\n3) Edit ".$__ini." and add 'extension=".$__f0."' directive";}}$__msg.="\n\n";}else{$__msg="<html><body>PHP script '".__FILE__."' is protected by <a href=\"http://www.sourceguardian.com/\">SourceGuardian</a> and requires a SourceGuardian loader '".$__f0."' to be installed.<br><br>1) <a href=\"".$__ixedurl."\" target=\"_blank\">Click here</a> to download the required '".$__f0."' loader from the SourceGuardian site<br>2) Install the loader to ";if(isset($__d0)){$__msg.=$__d0.DIRECTORY_SEPARATOR.'ixed';}else{$__msg.=$__e0;if(!$__dl){$__msg.="<br>3) Edit ".$__ini." and add 'extension=".$__f0."' directive<br>4) Restart the web server";}}$__msg.="</body></html>";}die($__msg);exit();}}return sg_load('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');
