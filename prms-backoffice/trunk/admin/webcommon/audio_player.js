
function load_audio_player () {

    // configure the path to the XSPF button player
    var playerUrl = "/admin/webcommon/audio_player.swf";

    // get all the links in the page
    var page_links = document.links;

    // loop over all the links
    for (var i=0; i<page_links.length; i++){

        // if the link references an MP3 file
        if (page_links[i].href.match(/\.mp3$/i)) {
	   
            // insert a span tag that contains the code for embedding the player
            var span = document.createElement("span");
            var url = playerUrl;
          	var width = 290;
            var height = 24;
            code_str = "";
            code_str += " <object class=\"pixeloutmp3player\" type=\"application/x-shockwave-flash\"\n";
            code_str += "data=\""+url+"\" \n";
            code_str += "width=\""+width+"\" height=\""+height+"\">\n";
            code_str += "<param name=\"movie\" \n";
            code_str += "value=\""+url+"\" />\n";
            code_str += "<param name=\"FlashVars\" \n";
            code_str +=    "value=\"playerID=1&amp;soundFile="+escape(page_links[i].href)+"\" />\n";
            code_str += "<param name=\"quality\" value=\"high\">\n";
            code_str += "<param name=\"menu\" value=\"false\">\n";
            code_str += "<param name=\"wmode\" value=\"transparent\">\n";
            code_str += "</object>\n";
            span.innerHTML = code_str;
            page_links[i].parentNode.insertBefore(span, page_links[i].nextSibling);
        }
    }

}

function load_audio_player_by_ref (elementID) {
	
	elementID = elementID.slice(1);		// remove the # sign

	obj = document.getElementById(elementID);
	
    // configure the path to the XSPF button player
    var playerUrl = "/admin/webcommon/audio_player.swf";

    // insert a span tag that contains the code for embedding the player
    var span = document.createElement("span");
    var url = playerUrl;
  	var width = 290;
    var height = 24;
    code_str = "";
    code_str += " <object class=\"pixeloutmp3player\" type=\"application/x-shockwave-flash\"\n";
    code_str += "data=\""+url+"\" \n";
    code_str += "width=\""+width+"\" height=\""+height+"\">\n";
    code_str += "<param name=\"movie\" \n";
    code_str += "value=\""+url+"\" />\n";
    code_str += "<param name=\"FlashVars\" \n";
    code_str +=    "value=\"playerID=1&amp;soundFile="+escape(obj.href)+"\" />\n";
    code_str += "<param name=\"quality\" value=\"high\">\n";
    code_str += "<param name=\"menu\" value=\"false\">\n";
    code_str += "<param name=\"wmode\" value=\"transparent\">\n";
    code_str += "</object>\n";
    span.innerHTML = code_str;
    obj.parentNode.insertBefore(span, obj.nextSibling);

}

load_audio_player();
