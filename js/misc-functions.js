
 /* · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · ·  
  ·                                                                             ·
  ·                                                                             ·
  ·                             Q V I T T E R                                   ·
  ·                                                                             ·
  ·              http://github.com/hannesmannerheim/qvitter                     ·
  ·                                                                             ·
  ·                                                                             ·
  ·                                 <o)                                         ·
  ·                                  /_////                                     ·
  ·                                 (____/                                      ·
  ·                                          (o<                                ·
  ·                                   o> \\\\_\                                 ·
  ·                                 \\)   \____)                                ·
  ·                                                                             ·
  ·                                                                             ·    
  ·                                                                             ·
  ·  Qvitter is free  software:  you can  redistribute it  and / or  modify it  ·
  ·  under the  terms of the GNU Affero General Public License as published by  ·
  ·  the Free Software Foundation,  either version three of the License or (at  ·
  ·  your option) any later version.                                            ·
  ·                                                                             ·
  ·  Qvitter is distributed  in hope that  it will be  useful but  WITHOUT ANY  ·
  ·  WARRANTY;  without even the implied warranty of MERCHANTABILTY or FITNESS  ·
  ·  FOR A PARTICULAR PURPOSE.  See the  GNU Affero General Public License for  ·
  ·  more details.                                                              ·
  ·                                                                             ·
  ·  You should have received a copy of the  GNU Affero General Public License  ·
  ·  along with Qvitter. If not, see <http://www.gnu.org/licenses/>.            ·
  ·                                                                             ·
  ·   Contact h@nnesmannerhe.im if you have any questions.                      ·
  ·                                                                             · 
  · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · */
  

/* ·  
   · 
   ·   Checks if localstorage is availible
   ·
   ·   We can't just do if(typeof localStorage.selectedLanguage != 'undefined')
   ·   because firefox with cookies disabled then freaks out and stops executing js completely
   · 
   · · · · · · · · · */  

function localStorageIsEnabled() {
	var mod = 'test';
	try {
		localStorage.setItem(mod, mod);
		localStorage.removeItem(mod);
		return true;
		}
	catch(e) {
		return false;
		}
	}
	
	
/* ·  
   · 
   ·   Checks if register form is valid
   · 
   ·   @returns true or false
   ·
   · · · · · · · · · */  

function validateRegisterForm(o) {
	
	var nickname 	= o.find('#signup-user-nickname-step2');
	var fullname 	= o.find('#signup-user-name-step2');	
	var email 		= o.find('#signup-user-email-step2');		
	var homepage 	= o.find('#signup-user-homepage-step2');
	var bio 		= o.find('#signup-user-bio-step2');
	var loc		 	= o.find('#signup-user-location-step2');
	var password1 	= o.find('#signup-user-password1-step2');
	var password2 	= o.find('#signup-user-password2-step2');
	var passwords 	= o.find('#signup-user-password1-step2,#signup-user-password2-step2');		
	
	var allFieldsValid = true;
	
	if(nickname.val().length>1 && /^[a-zA-Z0-9]+$/.test(nickname.val())) {
		nickname.removeClass('invalid'); } else { nickname.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }
		
	if(fullname.val().length < 255) {
		fullname.removeClass('invalid'); } else { fullname.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email.val())) {
		email.removeClass('invalid'); } else { email.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if($.trim(homepage.val()).length==0 || /^(ftp|http|https):\/\/[^ "]+$/.test(homepage.val())) {
		homepage.removeClass('invalid'); } else { homepage.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if(bio.val().length < 140) {
		bio.removeClass('invalid'); } else { bio.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if(loc.val().length < 255) {
		loc.removeClass('invalid'); } else { loc.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if(password1.val().length>5 && password2.val().length>5 && password1.val() == password2.val()) {
		passwords.removeClass('invalid'); } else { passwords.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		
		
	return allFieldsValid;
	}	

/* ·  
   · 
   ·   Checks if edit profile form is valid
   · 
   ·   @returns true or false
   ·
   · · · · · · · · · */  

function validateEditProfileForm(o) {
	
	var fullname 	= o.find('input.fullname');	
	var homepage 	= o.find('input.url');
	var bio 		= o.find('textarea.bio');
	var loc		 	= o.find('input.location');
	
	var allFieldsValid = true;

	if(fullname.val().length < 255) {
		fullname.removeClass('invalid'); } else { fullname.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if($.trim(homepage.val()).length==0 || /^(ftp|http|https):\/\/[^ "]+$/.test(homepage.val())) {
		homepage.removeClass('invalid'); } else { homepage.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if(bio.val().length < 140) {
		bio.removeClass('invalid'); } else { bio.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		

	if(loc.val().length < 255) {
		loc.removeClass('invalid'); } else { loc.addClass('invalid'); if(allFieldsValid)allFieldsValid=false; }		
		
	return allFieldsValid;
	}	
    

    
/* ·  
   · 
   ·   Change profile design
   ·
   ·   @param obj: user object that _might_ contain colors, or window object, that _might_ contain user settings 
   · 
   · · · · · · · · · */  

function changeDesign(obj) {
	
	// user object that might contains other user's colors
	if(typeof obj.linkcolor != 'undefined' &&
       typeof obj.backgroundcolor != 'undefined') {
		if(obj.linkcolor == null) {
			changeLinkColor(window.defaultLinkColor);
			}
		else if(obj.linkcolor.length == 6) {
			changeLinkColor('#' + obj.linkcolor);
			}
		else {
			changeLinkColor(window.defaultLinkColor);
			}	
		if(obj.backgroundcolor == null) {
			$('body').css('background-color',window.defaultBackgroundColor);
			}							   
		else if(obj.backgroundcolor.length == 6) {
			$('body').css('background-color','#' + obj.backgroundcolor);
			}
		else {
			$('body').css('background-color',window.defaultBackgroundColor);
			}							   
	   }
	   
	  // window object that might contain my colors
	  else if(typeof obj.userLinkColor != 'undefined' &&
              typeof obj.userBackgroundColor != 'undefined') {
		if(obj.userLinkColor == null) {
			changeLinkColor(window.defaultLinkColor);
			}
		else if(obj.userLinkColor.length == 6) {
			changeLinkColor('#' + obj.userLinkColor);
			}
		else {
			changeLinkColor(window.defaultLinkColor);
			}	
		if(obj.userBackgroundColor == null) {
			$('body').css('background-color',window.defaultBackgroundColor);
			}
		else if(obj.userBackgroundColor.length == 6) {
			$('body').css('background-color','#' + obj.userBackgroundColor);
			}
		else {
			$('body').css('background-color',window.defaultBackgroundColor);
			}		  	
	  	}
	  	
	  	// TODO BACKGROUND IMAGE!
	  	$('body').css('background-image','none');
	}

    
/* ·  
   · 
   ·   Change link color
   ·
   ·   @param newLinkColor: hex value with #
   · 
   · · · · · · · · · */  

function changeLinkColor(newLinkColor) {
	var linkstyle = $('style').text();
	$('style').text(linkstyle.substring(0,linkstyle.indexOf('color:')+6) + newLinkColor + linkstyle.substring(linkstyle.indexOf(';/*COLOREND*/')));
	var linkstyle = $('style').html();
	$('style').text(linkstyle.substring(0,linkstyle.indexOf('background-color:')+17) + newLinkColor + linkstyle.substring(linkstyle.indexOf(';/*BACKGROUNDCOLOREND*/')));		
	}


/* ·  
   · 
   ·   Right-to-left language detection                              <o
   ·                                                                  (//
   ·   @param s: the stream-item to detect rtl in 
   ·
   ·   @return a stream-item that might have rtl-class added  
   · 
   · · · · · · · · · */
   
function detectRTL(s) {           
	var $streamItem = $('<div>').append(s);
	var $queetText = $('<div>').append($streamItem.find('.queet-text').html()); // create an jquery object
	var $a = $queetText.find('a'); $a.remove(); // remove links
	var $vcard = $queetText.find('.vcard'); $vcard.remove(); // remove users, groups
	var $tag = $queetText.find('.tag'); $tag.remove(); // remove tags
	if($queetText.find('.rtl').length>0) { $queetText.html($queetText.find('.rtl').html()); } // remove rtl container if there is one
	// remove chars we're not interested in
	$queetText.html($queetText.html().replace(/\@/gi,'').replace(/\#/gi,'').replace(/\!/gi,'').replace(/\(/gi,'').replace(/\)/gi,'').replace(/\:D/gi,'').replace(/D\:/gi,'').replace(/\:/gi,'').replace(/\-/gi,'').replace(/\s/gi, ''));
	// count ltr and rtl chars	
    var ltrChars        = 'A-Za-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02B8\u0300-\u0590\u0800-\u1FFF'+'\u2C00-\uFB1C\uFDFE-\uFE6F\uFEFD-\uFFFF',
        rtlChars        = '\u0591-\u07FF\uFB1D-\uFDFD\uFE70-\uFEFC',
        rtlDirCheck     = new RegExp('^[^'+ltrChars+']*['+rtlChars+']'),
        RTLnum = 0,
        LTRnum = 0,
        RTLorLTR = $queetText.html();
	for (var i = 0, len = RTLorLTR.length; i < len; i++) {
		if(rtlDirCheck.test(RTLorLTR[i])) { RTLnum++; }
		else { LTRnum++; }
		} 
    // if there are more rtl chars than ltr
    if(RTLnum > LTRnum) { $streamItem.children('.stream-item').children('.queet').addClass('rtl'); }
    // if no chars (that we are interested, but body is set to rtl)
    else if ($queetText.html().length==0 && $('body').hasClass('rtl')) {
    	$streamItem.children('.stream-item').children('.queet').addClass('rtl');
    	}	    	    	    	
	return $streamItem.html().replace(/@<span class="vcard">/gi,'<span class="vcard">').replace(/!<span class="vcard">/gi,'<span class="vcard">').replace(/#<span class="tag">/gi,'<span class="tag">'); // hacky way to get @#! into mention tags to stop bidirection (css sets an @ with before content method)
	}
	
	
	
/* · 
   · 
   ·   Takes twitter style dates and converts them
   ·
   ·   @param tdate: date in the form of e.g. 'Mon Aug 05 16:30:22 +0200 2013' 
   · 
   ·   @return user friendly dates                                                      ..M_   
   ·                                                                                      W  
   ·   Needs global language object window.sL to be populated
   · 
   · · · · · · · · · · · · · */
   
function parseTwitterDate(tdate) {
	var month_names = new Array ();
	month_names[month_names.length] = window.sL.shortmonthsJanuary;
	month_names[month_names.length] = window.sL.shortmonthsFebruary
	month_names[month_names.length] = window.sL.shortmonthsMars
	month_names[month_names.length] = window.sL.shortmonthsApril
	month_names[month_names.length] = window.sL.shortmonthsMay
	month_names[month_names.length] = window.sL.shortmonthsJune
	month_names[month_names.length] = window.sL.shortmonthsJuly
	month_names[month_names.length] = window.sL.shortmonthsAugust
	month_names[month_names.length] = window.sL.shortmonthsSeptember
	month_names[month_names.length] = window.sL.shortmonthsOctober
	month_names[month_names.length] = window.sL.shortmonthsNovember
	month_names[month_names.length] = window.sL.shortmonthsDecember
    var system_date = new Date(Date.parse(tdate));
    var user_date = new Date();
    var diff = Math.floor((user_date - system_date) / 1000);
    if (diff <= 10) {return window.sL.now;}
    if (diff < 60) {return window.sL.shortDateFormatSeconds.replace('{seconds}',Math.round(diff/10)*10);}
    if (diff <= 3540) {return window.sL.shortDateFormatMinutes.replace('{minutes}',Math.round(diff / 60));}
    if (diff <= 86400) {return window.sL.shortDateFormatHours.replace('{hours}',Math.round(diff / 3600));}
    if (diff <= 31536000) {return window.sL.shortDateFormatDate.replace('{day}',system_date.getDate()).replace('{month}',month_names[system_date.getMonth()]);}
    if (diff > 31536000) {return window.sL.shortDateFormatDateAndY.replace('{day}',system_date.getDate()).replace('{month}',month_names[system_date.getMonth()]).replace('{year}',system_date.getFullYear());}
    return system_date;
	}
function parseTwitterLongDate(tdate) {
	var month_names = new Array ();
	month_names[month_names.length] = window.sL.longmonthsJanuary;
	month_names[month_names.length] = window.sL.longmonthsFebruary
	month_names[month_names.length] = window.sL.longmonthsMars
	month_names[month_names.length] = window.sL.longmonthsApril
	month_names[month_names.length] = window.sL.longmonthsMay
	month_names[month_names.length] = window.sL.longmonthsJune
	month_names[month_names.length] = window.sL.longmonthsJuly
	month_names[month_names.length] = window.sL.longmonthsAugust
	month_names[month_names.length] = window.sL.longmonthsSeptember
	month_names[month_names.length] = window.sL.longmonthsOctober
	month_names[month_names.length] = window.sL.longmonthsNovember
	month_names[month_names.length] = window.sL.longmonthsDecember
    var system_date = new Date(Date.parse(tdate));		
	var hours = system_date.getHours();
	var minutes = ('0'+system_date.getMinutes()).slice(-2);
	var ampm = hours >= 12 ? 'pm' : 'am';
	var time24hours = hours + ':' + minutes;
	var time12hours = hours % 12;
	time12hours = time12hours ? time12hours : 12; // the hour '0' should be '12'
	if(ampm == 'am') { time12hours = window.sL.time12am.replace('{time}',time12hours + ':' + minutes);}
	else { time12hours = window.sL.time12pm.replace('{time}',time12hours + ':' + minutes); }
	return window.sL.longDateFormat.replace('{time24}',time24hours).replace('{hours}',hours).replace('{minutes}',minutes).replace('{time12}',time12hours).replace('{day}',system_date.getDate()).replace('{month}',month_names[system_date.getMonth()]).replace('{year}',system_date.getFullYear());
	}	 
function timestampToTwitterDate(timestamp) {
	 var a = new Date(timestamp*1000);
	 var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	 var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];						 
	 var day = days[a.getUTCDay()];
	 var year = a.getUTCFullYear();
	 var month = months[a.getUTCMonth()];
	 var date = (a.getUTCDate()<10?'0':'')+a.getUTCDate();
	 var hour = (a.getUTCHours()<10?'0':'')+a.getUTCHours();
	 var min = (a.getUTCMinutes()<10?'0':'')+a.getUTCMinutes();
	 var sec = (a.getUTCSeconds()<10?'0':'')+a.getUTCSeconds();
	 return day+' '+month+' '+date+' '+hour+':'+min+':'+sec+' +0000 '+year;
	 }	 	
	
	
	
	
/* ·
   · 
   ·   Decode Qvitter's compact API reponse
   · 
   ·   @param data: the data returned from qvitter's compact api response
   ·
   ·   @return data formatted as the non-compact "[old] twitter style" api response
   · 
   · · · · · · · · · · */
   
function decodeQvitterCompactFormat(data) {	
	
	// empty object? return empty array instead...
	if($.isEmptyObject(data)) {
		return [];
		}
	// leave data unchanged if we don't recognize it
	else if(typeof data.s == 'undefined') {
		return data;
		}		
	// decode
	else {
		var users = new Object();						
		var i = 0;
		$.each(data.u, function(k,v){
			users[k] = new Object;							
			users[k].id = k;
			users[k].screen_name = v[0];
			users[k].name = (v[1]==0?null:v[1]);
			users[k].location = (v[2]==0?null:v[2]);
			users[k].description = (v[3]==0?null:v[3]);																																						
			users[k].profile_image_url_profile_size = v[4];																																						
			users[k].profile_image_url_original = v[5];																																						
			users[k].groups_count = v[6];																																						
			users[k].linkcolor = (v[7]==0?false:v[7]);																																						
			users[k].backgroundcolor = (v[8]==0?false:v[8]);																																						
			users[k].url = (v[9]==0?null:v[9]);																																						
			users[k].followers_count = v[10];																																						
			users[k].friends_count = v[11];																																						
			users[k].favourites_count = v[12];																																						
			users[k].statuses_count = v[13];																																						
			users[k].following = (v[14]==0?false:v[14]);																																						
			users[k].statusnet_blocking = (v[15]==0?false:v[15]);																																						
			users[k].statusnet_profile_url = v[16];
			users[k].cover_photo = (v[17]==0?false:v[17]);			
			i++;
			});								
		var unqvitter = Array();	
		var i = 0;		
		$.each(data.s, function(k,v){
			unqvitter[i] = new Object;							
			unqvitter[i].id = v[0];
			unqvitter[i].created_at = timestampToTwitterDate(v[1]);							
			unqvitter[i].text = v[2];
			unqvitter[i].statusnet_html = v[3];
			unqvitter[i].in_reply_to_status_id = (v[4]==0?null:v[4]);
			unqvitter[i].in_reply_to_user_id = (v[5]==0?null:v[5]);
			unqvitter[i].in_reply_to_screen_name = (v[6]==0?null:v[6]);
			unqvitter[i].favorited = (v[7]==0?false:v[7]);
			unqvitter[i].repeated = (v[8]==0?false:v[8]);
			unqvitter[i].statusnet_in_groups = (v[9]==0?false:v[9]);
			unqvitter[i].user = users[v[10]];
			unqvitter[i].statusnet_conversation_id = v[11];				
			unqvitter[i].uri = window.siteInstanceURL + 'notice/' + v[0];															
			unqvitter[i].source = (v[12]==0?null:v[12]);

			if(typeof v[13] != 'undefined') {
				unqvitter[i].retweeted_status = new Object;							
				unqvitter[i].retweeted_status.id = v[13][0];
				unqvitter[i].retweeted_status.created_at = timestampToTwitterDate(v[13][1]);							
				unqvitter[i].retweeted_status.text = v[13][2];
				unqvitter[i].retweeted_status.statusnet_html = v[13][3];
				unqvitter[i].retweeted_status.in_reply_to_status_id = (v[13][4]==0?null:v[13][4]);
				unqvitter[i].retweeted_status.in_reply_to_user_id = (v[13][5]==0?null:v[13][5]);
				unqvitter[i].retweeted_status.in_reply_to_screen_name = (v[13][6]==0?null:v[13][6]);
				unqvitter[i].retweeted_status.favorited = (v[13][7]==0?false:v[13][7]);
				unqvitter[i].retweeted_status.repeated = (v[13][8]==0?false:v[13][8]);
				unqvitter[i].retweeted_status.statusnet_in_groups = (v[13][9]==0?false:v[13][9]);
				unqvitter[i].retweeted_status.user = users[v[13][10]];
				unqvitter[i].retweeted_status.statusnet_conversation_id = v[13][11];				
				unqvitter[i].retweeted_status.uri = window.siteInstanceURL + 'notice/' + v[13][0];															
				unqvitter[i].retweeted_status.source = (v[13][12]==0?null:v[13][12]);				
				}

			i++;
			});
		return unqvitter;	
		}
	}
	
	
	
	
/* ·
   · 
   ·   Return all URL:s in a string
   · 
   ·   @param string: the string to search
   ·
   ·   @return an array with the found urls
   · 
   · · · · · · · · · · */
   
function findUrls(text) {
    var source = (text || '').toString();
    var urlArray = [];
    var url;
    var matchArray;
    var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)|((mailto:)?[_.\w-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})/g;
    while( (matchArray = regexToken.exec( source )) !== null ) {
        var token = matchArray[0];
        urlArray.push( token );
	    }
    return urlArray;
	}		



/* · 
   · 
   ·   Functions to show and remove the spinner
   · 
   · · · · · · · · · · · · */
   
function display_spinner() { 
	if($('.spinner-wrap').length==0) {
		$('body').prepend('<div class="spinner-wrap"><div class="spinner"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div></div>');
		}
	}	
function remove_spinner() {	
	$('.spinner-wrap').remove();
	}	
	
	

/* · 
   · 
   ·   Converts ...-attachment-links to spans
   ·
   ·   (Attachments are loaded when queets expand)
   · 
   · · · · · · · · · · · · · · · · · */

function convertAttachmentMoreHref() {
	$('a.attachment.more').each(function() {
		if(typeof $(this).attr('href') != 'undefined') {
			$(this).replaceWith($('<span class="attachment more" data-attachment-id="' + $(this).attr('href').substring(29) + '">…</span>'));			
			}
		});
	}



/* · 
   · 
   ·   Updates the local storage
   ·
   · · · · · · · · · · · · · */
   
function updateHistoryLocalStorage() {
	if(localStorageIsEnabled()) {
		var i=0;
		var localStorageName = window.loggedIn.screen_name + '-history-container-v2';
		var historyContainer = new Object();
		$.each($('#history-container .stream-selection'), function(key,obj) {
			historyContainer[i] = new Object();
			historyContainer[i].dataStreamHref = $(obj).attr('href');
			historyContainer[i].dataStreamHeader = $(obj).attr('data-stream-header');			
			i++;
			});
		localStorage[localStorageName] = JSON.stringify(historyContainer);
		if($('#history-container .stream-selection').length==0) {
			$('#history-container').css('display','none');
			}
		else {
			$('#history-container').css('display','block');
			}
		$('#history-container').sortable({delay: 100});
		$('#history-container').disableSelection();		
		}
	}


/* · 
   · 
   ·   Loads history from local storage to menu
   · 
   · · · · · · · · · · · · · */
   
function loadHistoryFromLocalStorage() {
	if(localStorageIsEnabled()) {
		var localStorageName = window.loggedIn.screen_name + '-history-container-v2';
		if(typeof localStorage[localStorageName] != "undefined") {
			$('#history-container').css('display','block');
			$('#history-container').html('');																										
			var historyContainer = $.parseJSON(localStorage[localStorageName]);
			$.each(historyContainer, function(key,obj) {
				$('#history-container').append('<a class="stream-selection" data-stream-header="' + obj.dataStreamHeader + '" href="' + obj.dataStreamHref + '">' + obj.dataStreamHeader + '</i><i class="chev-right"></i></a>');
				});
			}
		updateHistoryLocalStorage();
		}
	}	


	
/* · 
   · 
   ·   Does stream need a ? or a &
   · 
   · · · · · · · · · · · · · */
   
function qOrAmp(stream) {
	if(stream.substr(-5) == '.json') {
		return '?';
		}
	else {
		return '&';
		}		
	}
	
	
/* · 
   · 
   ·   Count chars in queet box
   ·
   ·   @param src: the queetbox's value
   ·   @param trgt: the counter
   ·   @param btn: the button 
   ·   
   · · · · · · · · · · · · · */ 

function countCharsInQueetBox(src,trgt,btn) {

	var $src_txt = $('<div/>').append($.trim(src.html()).replace(/&nbsp;/gi,' ').replace(/<br>/i,'').replace(/<br>/gi,"x"));
	var numchars = ($.trim($src_txt.text())).length;

	// limited
	if(window.textLimit > 0) {
		trgt.html(window.textLimit - numchars);

		// activate/deactivare button
		if(numchars > 0 && numchars < window.textLimit+1) {
			btn.removeClass('disabled');
			btn.addClass('enabled');

			// deactivate button if it's equal to the start text
			var queetBox = btn.closest('.inline-reply-queetbox').children('.queet-box-syntax');
			if(typeof queetBox.attr('data-replies-text') != 'undefined') {
				var $startText = $('<div/>').append(decodeURIComponent(queetBox.attr('data-replies-text')));
				if($.trim($startText.text()) == $.trim($src_txt.text())) {
					btn.removeClass('enabled');
					btn.addClass('disabled');			
					}
				}
			}
		else {
			btn.removeClass('enabled');
			btn.addClass('disabled');
			}	
	
		
		// counter color		
		if((window.textLimit-numchars) < 0) {
			trgt.css('color','#D40D12');
			}
		else {
			trgt.removeAttr('style');			
			}
		}
	// unlimited
	else {
		if(numchars > 0) {
			btn.removeClass('disabled');
			btn.addClass('enabled');		
			}
		else {
			btn.removeClass('enabled');
			btn.addClass('disabled');			
			}
		}
	}		
	

/* · 
   · 
   ·   Remember my scroll position
   ·
   ·   @param obj: jQuery object which position we want to remember
   ·   @param id: id for position to remember
   ·   @param offset: we might want to offset our remembered scroll, e.g. when stream-item gets margin after expand
   ·   
   · · · · · · · · · · · · · */ 

function rememberMyScrollPos(obj,id,offset) {
	if(typeof offset == 'undefined') {
		var offset = 0;
		}	
	if(typeof window.scrollpositions == 'undefined') { window.scrollpositions = new Object();}
	window.scrollpositions[id] = obj.offset().top - $(window).scrollTop() - offset;
	}	

	
/* · 
   · 
   ·   Go back to my scroll po
   ·
   ·   @param obj: jQuery object to put in the remebered position
   ·   @param id: id for remembered position   
   ·   @param animate: if we want to animate the scroll
   ·   @param callback: function to run when animation stops
   ·   
   · · · · · · · · · · · · · */ 

function backToMyScrollPos(obj,id,animate,callback) {
	var pos = obj.offset().top-window.scrollpositions[id];
	if(animate) {
		if(animate == 'animate' || animate === true) {
			animate = 1000;
			}
	   if(typeof callback !== 'undefined'){
			$('html, body').animate({ scrollTop: pos}, animate, 'linear',function(){
				callback();
				});		   	
		   	}
	    else {
	    	$('html, body').animate({ scrollTop: pos }, animate, 'linear');
	    	}
		}
	else {
		$('html, body').scrollTop(pos);
		}			
	}		
	


/* · 
   · 
   ·   Clean up user object, remove null etc
   ·   
   · · · · · · · · · · · · · */ 

function cleanUpUserObject(data) {
	data.name = data.name || '';
	data.profile_image_url = data.profile_image_url || '';
	data.profile_image_url_profile_size = data.profile_image_url_profile_size || '';
	data.profile_image_url_original = data.profile_image_url_original || '';						
	data.screen_name = data.screen_name || '';						
	data.description = data.description || '';
	data.location = data.location || '';
	data.url = data.url || '';
	data.statusnet_profile_url = data.statusnet_profile_url || '';
	data.statuses_count = data.statuses_count || 0;
	data.followers_count = data.followers_count || 0;
	data.groups_count = data.groups_count || 0;			
	data.friends_count = data.friends_count || 0;
	return data;		
	}		
	



/* · 
   · 
   ·   outerHTML
   ·   
   · · · · · · · · · · · · · */ 
	
jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};




/* · 
   · 
   ·   Stuff to get and set selection/caret in contenteditables 
   ·   
   · · · · · · · · · · · · · */ 
	
function getSelectionInElement(element) {
	var caretOffset = Array(0,0);
	var doc = element.ownerDocument || element.document;
	var win = doc.defaultView || doc.parentWindow;
	var sel;
	var range = win.getSelection().getRangeAt(0);
	var preCaretRangeEnd = range.cloneRange();
	preCaretRangeEnd.selectNodeContents(element);
	preCaretRangeEnd.setEnd(range.endContainer, range.endOffset);
	caretOffset[1] = preCaretRangeEnd.toString().length;
	var preCaretRangeStart = range.cloneRange();
	preCaretRangeStart.selectNodeContents(element);
	preCaretRangeStart.setEnd(range.startContainer, range.startOffset);
	caretOffset[0] = preCaretRangeStart.toString().length;			
	return caretOffset;
	}					
function getTextNodesIn(node) {
	var textNodes = [];
	if (node.nodeType == 3) {
		textNodes.push(node);
		}
	else {
		var children = node.childNodes;
		for (var i = 0, len = children.length; i < len; ++i) {
			textNodes.push.apply(textNodes, getTextNodesIn(children[i]));
			}
		}
	return textNodes;
	}

function setSelectionRange(el, start, end) {
    if (document.createRange && window.getSelection) {
        var range = document.createRange();
        range.selectNodeContents(el);
        var textNodes = getTextNodesIn(el);
        var foundStart = false;
        var charCount = 0, endCharCount;

        for (var i = 0, textNode; textNode = textNodes[i++]; ) {
            endCharCount = charCount + textNode.length;
			if(endCharCount == start && endCharCount == end) {
				endCharCount = endCharCount+1;
				}            
            if (!foundStart && start >= charCount
                    && (start < endCharCount ||
                    (start == endCharCount && i < textNodes.length))) {
                range.setStart(textNode, start - charCount);
                foundStart = true;
            }
            if (foundStart && end <= endCharCount) {
                range.setEnd(textNode, end - charCount);
                break;
            }
            charCount = endCharCount;
        }
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (document.selection && document.body.createTextRange) {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(true);
        textRange.moveEnd("character", end);
        textRange.moveStart("character", start);
        textRange.select();
    }
}
function createRangeFromCharacterIndices(containerEl, start, end) {
    var charIndex = 0, range = document.createRange(), foundStart = false, stop = {};
    range.setStart(containerEl, 0);
    range.collapse(true);

    function traverseTextNodes(node) {
        if (node.nodeType == 3) {
            var nextCharIndex = charIndex + node.length;
            if (!foundStart && start >= charIndex && start <= nextCharIndex) {
                range.setStart(node, start - charIndex);
                foundStart = true;
            }
            if (foundStart && end >= charIndex && end <= nextCharIndex) {
                range.setEnd(node, end - charIndex);
                throw stop;
            }
            charIndex = nextCharIndex;
        } else {
            for (var i = 0, len = node.childNodes.length; i < len; ++i) {
                traverseTextNodes(node.childNodes[i]);
            }
        }
    }

    try {
        traverseTextNodes(containerEl);
    } catch (ex) {
        if (ex == stop) {
            return range;
        } else {
            throw ex;
        }
    }
}

function deleteBetweenCharacterIndices(el, from, to) {
    var range = createRangeFromCharacterIndices(el, from, to);
    range.deleteContents();
}