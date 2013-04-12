

function load_video_player () {

    // get all the links in the page
    var page_links = document.links;

    // loop over all the links
    for (var i=0; i<page_links.length; i++){

        // if the link references a media file
        if (page_links[i].href.match(/\.wmv$/i) || page_links[i].href.match(/\.avi$/i)) {	   
            code_str = "<img src='/image/system/video-placeholder.jpg' />";
			page_links[i].innerHTML = code_str;
        }
    }
	
}

load_video_player();