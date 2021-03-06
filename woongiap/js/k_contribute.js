/*
yuiImgUploader
http://allmybrain.com/2007/10/16/an-image-upload-extension-for-yui-rich-text-editor/
variables: 
 rte: The YAHOO.widget.Editor instance
 upload_url: the url to post the file to
 upload_image_name: the name of the post parameter to send the file as

Your server must handle the posted image.  You must return a JSON object
with the result url that the image can be viewed at on your server.  If 
the upload fails, you can return an error message.  For successful
uploads, the status must be set to UPLOADED.  All other status messages,
or the lack of a status message is interpreted as an error.  IE will 
try to open a new document window when the response is returned if your
content-type header on your response is not set to 'text/javascript'

Example Success:
{status:'UPLOADED', image_url:'/somedirectory/filename'}
Example Failure:
{status:'We only allow JPEG Images.'}
*/
function yuiImgUploader(rte, editor_name, upload_url, upload_image_name) {
   // customize the editor img button 
   YAHOO.log( "Adding Click Listener" ,'debug');
   rte.addListener('toolbarLoaded',function() {
       rte.toolbar.addListener ( 'insertimageClick', function(o) {
           try {
               var imgPanel=new YAHOO.util.Element(editor_name + '-panel');
               imgPanel.on ( 'contentReady', function() {
                   try {
                       var Dom=YAHOO.util.Dom;

                       if (! Dom.get(editor_name + '_insertimage_upload'))
                       {
                           var label=document.createElement('label');
                          label.innerHTML='<strong>Upload:</strong>'+
			         '<input type="file" id="' +
				  editor_name + '_insertimage_upload" name="'+upload_image_name+
			         '" size="10" style="width: 300px" />'+			         
			         '</label>';

                           var img_elem=Dom.get(editor_name + '_insertimage_url');
                           Dom.getAncestorByTagName(img_elem, 'form').encoding = 'multipart/form-data';

                           Dom.insertAfter(label, img_elem.parentNode);

                           YAHOO.util.Event.on ( editor_name + '_insertimage_upload', 'change', function(ev) {
                               YAHOO.util.Event.stopEvent(ev); // no default click action
                               YAHOO.util.Connect.setForm ( img_elem.form, true, true );
                               var c=YAHOO.util.Connect.asyncRequest(
                               'POST', upload_url, {
                                   upload:function(r){
                                       try {
                                           // strip pre tags if they got added somehow
                                           resp=r.responseText.replace( /<pre>/i, '').replace ( /<\/pre>/i, '');
                                           var o=eval('('+resp+')');
                                           if (o.status=='UPLOADED') {
                                               Dom.get(editor_name + '_insertimage_upload').value='';
                                               Dom.get(editor_name + '_insertimage_url').value=o.image_url;
                                               // tell the image panel the url changed
                                               // hack instead of fireEvent('blur')
                                               // which for some reason isn't working
                                               Dom.get(editor_name + '_insertimage_url').focus();
                                               Dom.get(editor_name + '_insertimage_upload').focus();
                                           } else {
                                               alert ( "Upload Failed: "+o.status );
                                           }

                                       } catch ( eee ) {
                                           YAHOO.log( eee.message, 'error' );
                                       }
                                   }
                               }
                               );
                               return false;
                           });
                       }
                   }
			catch ( ee ) { YAHOO.log( ee.message, 'error' ); }
		   
               });
           } catch ( e ) {
               YAHOO.log( e.message, 'error' );
           }
       });
   });
}

/**
 * javascript for contribute page
 */
$(document).ready(function() {
	var Dom = YAHOO.util.Dom, Event = YAHOO.util.Event,
	myConfig = {
	    height: '450px',
	    width: '680px',
	    animate: true,
	    dompath: true,
	    //handleSubmit: true,
	    focusAtStart: false,
	    removeLineBreaks: true
	};
	var myEditor = new YAHOO.widget.Editor('desc', myConfig);
	myEditor._defaultToolbar.titlebar = false;
	yuiImgUploader(myEditor, 'desc', 'img_up.php', 'image');	
	myEditor.render();
	$('#post_title').focus();
	$('#f-contribute').submit(function() {
		myEditor.saveHTML();
		var cango = true;
		$('.hint').empty().removeClass('ferr');
		if ($('#post_title').val() == '') {
			cango = false;
			$('#msg-title').addClass('ferr').text('Enter title');
		}	
		if ($('#location').val() == '') {
			cango = false;
			$('#msg-location').addClass('ferr').text('Enter location');
		}	
		if ($('#desc').val() == '') {
			cango = false;
			$('#msg-desc').addClass('ferr').text('Enter description');
		}
		return cango;
	});
	$("#suggest-link").click(function(event) {
		$('#suggest-box .msg-bar').removeClass('err info progress').empty();
		show_popup($("#suggest-box"));		
	});
	var suggested = function(responseText, statusText) {
		var response = eval('('+responseText+')');
		if (response.status == 1) {
			$('#suggest-box .msg-bar').removeClass("err progress").addClass('info').text(response.msg).show().fadeOut(4000).queue(function() {
				$('#suggest-box').hide();
				$(this).dequeue();
			});
		} else {
			$('#suggest-box .msg-bar').removeClass("info progress").addClass('err').text(response.msg).show();		
		}
	};
	var opts = { 
	        success: suggested,
	        url: "af.php"
	    };
	$('#f-sugcat').ajaxForm(opts);
	
	var preview_source = '<div id="preview-box" class="popup" style="width:700px;padding:0 26px 0 26px;font-family:Arial;">' +
	'<div style="text-align:center;"><a href="" onclick="close_popup();return false;">Close</a></div>' +
	'<h3 style="text-align:center;"></h3><div id="desc-preview"></div><div style="text-align:center;margin-top:30px;"><a href="" onclick="close_popup();return false;">Close</a></div></div>';
	$(document.body).append(preview_source);

	var preview = function() {
		myEditor.saveHTML();
		var post_title = $('#post_title').val().replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
		var post_desc = $('#desc').val();
		if (post_title == "" || post_desc == "") {
			alert("Please enter title and description.");
			return false;
		}		
		show_popup($("#preview-box"));
		$("#preview-box h3").empty().html(post_title);
		$("#preview-box #desc-preview").empty().html(post_desc);
		$("#preview-box a:first").focus();
	};
	$("form #preview").click(preview);
	$("#suggest-box").bgiframe();
	$("#preview-box").bgiframe();
});
