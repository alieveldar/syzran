<?	
$file="_bottomblock-new_default"; if (RetCache($file, "cacheblock")=="true") { list($Page["BottomContent"], $cap)=GetCache($file, 0); } else { list($Page["BottomContent"], $cap)=CreateBottomBlock(); SetCache($file, $Page["BottomContent"], "", "cacheblock"); }	
//if ($link!="uncensored") { $Page["BottomContent"]=str_replace('<!--ADS-->', '<ins class="adsbygoogle" style="display:inline-block;width:970px;height:90px; margin:20px 0;" data-ad-client="ca-pub-2073806235209608" data-ad-slot="5752784216"></ins><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', $Page["BottomContent"]); }

function CreateBottomBlock() {
	global $Domains, $SubDomain, $GLOBAL, $C20; $text='';
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		
		$text.=$C.'<!-- Яндекс.Директ -->
<div id="yandex_ad"></div>
<script type="text/javascript">
(function(w, d, n, s, t) {
    w[n] = w[n] || [];
    w[n].push(function() {
        Ya.Direct.insertInto(126201, "yandex_ad", {
            ad_format: "direct",
            font_size: 0.9,
            font_family: "tahoma",
            type: "horizontal",
            limit: 3,
            title_font_size: 1,
            site_bg_color: "FFFFFF",
            title_color: "0000CC",
            url_color: "006600",
            text_color: "000000",
            hover_color: "0066FF",
            favicon: true,
            no_sitelinks: false
        });
    });
    t = d.getElementsByTagName("script")[0];
    s = d.createElement("script");
    s.src = "//an.yandex.ru/system/context.js";
    s.type = "text/javascript";
    s.async = true;
    t.parentNode.insertBefore(s, t);
})(window, document, "yandex_context_callbacks");
</script>';
		
		
						
		
		
		
		
		
		
		
		
		
		
		
	
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	$text.="<div class='C10'></div>"; return(array($text, ""));
}

// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


?>
