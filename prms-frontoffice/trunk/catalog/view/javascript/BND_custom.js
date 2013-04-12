$(document).ready(function(){

	$('#column_right').remove().insertBefore('#breadcrumb').show();

   // Use jQuery.support to check css3 opacity and assume no support for other css3 features; emulate
   if(!jQuery.support.opacity){
      $('li:last-child').addClass('li-last-child');
   }

   $('#category li:has(ul) > a').addClass('sf-with-ul');


     if ($.browser.msie && parseInt($.browser.version) == 7) {  
         $("#category ul ul li:has(ul)").hover(  
             function() {  
                 $(this).addClass("sfHover");
                 //$('ul',this).css({visibility:"visible"});  
             },  
             function() {  
                  $(this).removeClass("sfHover");
                  //$('ul',this).css({visibility:"hidden"});  
             });  
     }  

   if($.browser.msie && parseInt($.browser.version)< 7){
      $('<div id="ie6warning"><p>Warning: The version of Internet Explorer you are using may not allow you to take full advantage of some of the features of our website. If you would like to upgrade at no charge please <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx" style="color:red;">click here</a>.</p></div>').insertBefore('#container');
   }

});
