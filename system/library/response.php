<?php
class Response {
	private $headers = array();
	private $level = 0;
	private $output;
	public function addHeader($header) {
		$this->headers[] = $header;
	}
	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
		exit();
	}
	public function setCompression($level) {
		$this->level = $level;
	}
	public function getOutput() {
		return $this->output;
	}
	public function setOutput($output) {
		$this->output = $output;
	}
	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}
	public function output() {
		if ($this->output) {            $this->output = preg_replace("/(\n)+/", "\n", $this->output);            $this->output = preg_replace("/\r\n+/", "\n", $this->output);            $this->output = preg_replace("/\n(\t)+/", "\n", $this->output);            $this->output = preg_replace("/\n(\ )+/", "\n", $this->output);            $this->output = preg_replace("/\>(\n)+</", '><', $this->output);            $this->output = preg_replace("/\>\r\n</", '><', $this->output);						$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;						if (!headers_sent()) {				foreach ($this->headers as $header) {					header($header, true);				}			}						if (!defined('DIR_CATALOG')) {				if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/system/phpQuery.php')) {                    require_once $_SERVER['DOCUMENT_ROOT'] . '/system/phpQuery.php';                }			    				$url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == true) ? 's' : '') . '://';                $url = $url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    			$document = phpQuery::newDocument($output);					        	if ($document->find('a')->length()) {    	    	    foreach ($document->find('a') as $e) {                        $e = pq($e);                        if ($e->attr('href') == $url) {                            $e->removeAttr('href');                        }	        	    }	        	}								$script = [];				$scripts = '<script>document.addEventListener("DOMContentLoaded", function() {  var lazyloadImages;if ("IntersectionObserver" in window) {    lazyloadImages = document.querySelectorAll(".v_lazy");    var imageObserver = new IntersectionObserver(function(entries, observer) {entries.forEach(function(entry) {  if (entry.isIntersecting) {    var image = entry.target;    image.src = image.dataset.src;    image.classList.remove("v_lazy");  if (image.parentElement.querySelector(".loader")) { image.parentElement.querySelector(".loader").remove();} if (image.parentElement.parentElement.querySelector(".loader")) { image.parentElement.parentElement.querySelector(".loader").remove();}   imageObserver.unobserve(image);  }});    });    lazyloadImages.forEach(function(image) {imageObserver.observe(image);    });  } else {var lazyloadThrottleTimeout;    lazyloadImages = document.querySelectorAll(".v_lazy");  function lazyload () {if(lazyloadThrottleTimeout) {  clearTimeout(lazyloadThrottleTimeout);}    lazyloadThrottleTimeout = setTimeout(function() {  var scrollTop = window.pageYOffset;  lazyloadImages.forEach(function(img) {if(img.offsetTop < (window.innerHeight + scrollTop)) {  img.src = img.dataset.src;  img.classList.remove(\'v_lazy\');}  });  if(lazyloadImages.length == 0) {     document.removeEventListener("scroll", lazyload);    window.removeEventListener("resize", lazyload);    window.removeEventListener("orientationChange", lazyload);  }}, 20);    }    document.addEventListener("scroll", lazyload);    window.addEventListener("resize", lazyload);    window.addEventListener("orientationChange", lazyload);  }});</script>';				if ($document->find('body')->length()) {					foreach ($document->find('script') as $e) {						if (pq($e)->attr('src')) {                        	if (!in_array(pq($e)->attr('src'), $script)) {			        			$scripts .= pq($e);	        	        		$script[] = pq($e)->attr('src');    						}														pq($e)->remove();		    	    	}					}										foreach ($document->find('script') as $e) {						if (!pq($e)->attr('src')) {                        	$scripts .= '<script>' . str_replace(['<script><!--', '<script>','<script type="text/javascript"><!--', '<script type="text/javascript">', '--></script>', '</script>'], '', pq($e)) . '</script>';						    pq($e)->remove();						}					}				}								if ($scripts) {                			    $document->find('body')->append($scripts);                }								foreach ($document->find('img') as $i => $e) {                    if (pq($e)->attr('src')) {                        pq($e)->attr('data-src', pq($e)->attr('src'))->attr('src', '')->addClass('v_lazy vlz');		    			if (!pq($e)->attr('alt')) pq($e)->attr('alt', $document->find('title')->text());                    }                }								$document->find('head')->append('<style>.v_lazy{opacity:0;background: none !important;}.vlz{transition: 1000ms opacity ease;animation-play-state: running}</style>');				$document = str_replace('<script type="text/javascript"><!--', '<script>', $document);          		$document = str_replace(' type="text/javascript"', '', $document);		        $document = str_replace('//--></script>', '</script>', $document);				$output = str_replace('<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">', '', $document);			}			
			echo $output;
		}
	}
}