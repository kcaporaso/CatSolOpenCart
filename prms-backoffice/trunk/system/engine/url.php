<?php 
final class Url { 
  	public function http($route) {
		return HTTP_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
  	}

  	public function https($route) {
		if (HTTPS_SERVER != '') {

         // KMC
         // Let's grab the entire hostname: 
         //$svr = $_SERVER['HTTP_HOST'];
         //$pos = strpos($svr, '.');
         //$host = substr($svr, 0, $pos);
         //$pos2 = strpos($svr, '.', $pos+1);
         //$domain = substr($svr, $pos+1, ($pos2-$pos-1));
         //$https = str_replace('_HOSTNAME_', $domain, HTTPS_SERVER);
         //echo 'p:' . $pos . 'p2:' . $pos2 . 'ht:'. $https;
	  		$link = HTTPS_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
		} else {
	  		$link = HTTP_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
		}

     /*KMC TESTING FUNNEL CHECKOUT if (strstr($route, "checkout/") !== false) {
        $link = HTTPS_CHECKOUT . 'index.php?route=' . str_replace('&', '&amp;', $route); 
      }
      */
				
		return $link;
  	}

   public function https_catalog($route) {
      $link = HTTPS_CATALOG_SERVER . 'index.php?route=' . str_replace('&', '&amp;', $route);
      return $link;
   }
}
?>
