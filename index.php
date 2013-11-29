<?php 

require_once 'settings.php'; 

// url to this instance
$instanceurl = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://'.$siterootdomain.'/';

// if used as a webapp
if($usehistorypushstate) {
	define('INSTALLDIR', realpath(dirname(__FILE__) . '/../../..'));
	define('STATUSNET', true);
	define('LACONICA', true);
	require_once INSTALLDIR . '/lib/common.php';

	// if this is a users url we a http header
	if(substr_count($_SERVER['REQUEST_URI'], '/') == 1) { 
		$nickname = substr($_SERVER['REQUEST_URI'],1);
		if(preg_match("/^[a-zA-Z0-9]+$/", $nickname) == 1) {
			header('Link: <'.$instanceurl.'main/xrd?uri=acct:'.$nickname.'@'.$siterootdomain.'>; rel="lrdd"; type="application/xrd+xml"');
			}
		}	
	}

?><!--
  · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · ·  
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
  · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · ·
   
--><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>  
		<title><?php print $sitetitle; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">		
		<link rel="stylesheet" type="text/css" href="<?php print $qvitterpath; ?>css/7.css" />
		<link rel="stylesheet" type="text/css" href="<?php print $qvitterpath; ?>css/jquery.minicolors.css" />		
		<link rel="shortcut icon" type="image/x-icon" href="<?php print $qvitterpath; ?>favicon.ico?v=2">
		<?php

		// if qvitter is a webapp and this is a users url we add feeds
		if($usehistorypushstate && substr_count($_SERVER['REQUEST_URI'], '/') == 1) { 
			$nickname = substr($_SERVER['REQUEST_URI'],1);
			if(preg_match("/^[a-zA-Z0-9]+$/", $nickname) == 1) {
				$user = User::staticGet('nickname', $nickname); 
				if(!isset($user->id)) {
					error_log("QVITTER: Could not get user id for user with nickname: $nickname – REQUEST_URI: ".$_SERVER['REQUEST_URI']);
					}        
				else {
					print '<link title="Notice feed for '.$nickname.' (Activity Streams JSON)" type="application/stream+json" href="'.$apiroot.'statuses/user_timeline/'.$user->id.'.as" rel="alternate">'."\n";
					print '		<link title="Notice feed for '.$nickname.' (RSS 1.0)" type="application/rdf+xml" href="'.$instanceurl.$nickname.'/rss" rel="alternate">'."\n";
					print '		<link title="Notice feed for '.$nickname.' (RSS 2.0)" type="application/rss+xml" href="'.$instanceurl.'api/statuses/user_timeline/'.$user->id.'.rss" rel="alternate">'."\n";
					print '		<link title="Notice feed for '.$nickname.' (Atom)" type="application/atom+xml" href="'.$instanceurl.'api/statuses/user_timeline/'.$user->id.'.atom" rel="alternate">'."\n";
					print '		<link title="FOAF for '.$nickname.'" type="application/rdf+xml" href="'.$instanceurl.$nickname.'/foaf" rel="meta">'."\n";
					print '		<link href="'.$instanceurl.$nickname.'/microsummary" rel="microsummary">'."\n";		    
					}		
				}
			}
		elseif(substr($_SERVER['REQUEST_URI'],0,7) == '/group/') {
			$group_id_or_name = substr($_SERVER['REQUEST_URI'],7);
			if(stristr($group_id_or_name,'/id')) {
				$group_id_or_name = substr($group_id_or_name, 0, strpos($group_id_or_name,'/id'));
				$group = User_group::staticGet('id', $group_id_or_name);		
				$group_name = $group->nickname;
				$group_id = $group_id_or_name;				
				}
			else {
				$group = User_group::staticGet('nickname', $group_id_or_name);		
				$group_id = $group->id;				
				$group_name = $group_id_or_name;								
				}
			if(preg_match("/^[a-zA-Z0-9]+$/", $group_id_or_name) == 1) {
				print '<link rel="alternate" href="'.$apiroot.'statusnet/groups/timeline/'.$group_id.'.as" type="application/stream+json" title="Notice feed for '.$group_id_or_name.' group (Activity Streams JSON)"/>'."\n";
				print '		<link rel="alternate" href="'.$instanceurl.'group/'.$group_name.'/rss" type="application/rdf+xml" title="Notice feed for '.$group_id_or_name.' group (RSS 1.0)"/>'."\n";
				print '		<link rel="alternate" href="'.$instanceurl.'api/statusnet/groups/timeline/'.$group_id.'.rss" type="application/rss+xml" title="Notice feed for '.$group_id_or_name.' group (RSS 2.0)"/>'."\n";
				print '		<link rel="alternate" href="'.$instanceurl.'api/statusnet/groups/timeline/'.$group_id.'.atom" type="application/atom+xml" title="Notice feed for '.$group_id_or_name.' group (Atom)"/>'."\n";
				print '		<link rel="meta" href="'.$instanceurl.'group/'.$group_name.'/foaf" type="application/rdf+xml" title="FOAF for '.$group_id_or_name.' group"/>'."\n";						
				}
			}
			
		
		?>
		<script>
			window.timeBetweenPolling = <?php print $timebetweenpolling; ?>;
			window.fullUrlToThisQvitterApp = '<?php print $qvitterpath; ?>';
			window.siteRootDomain = '<?php print $siterootdomain; ?>';
			window.siteInstanceURL = '<?php print $instanceurl; ?>';			
			window.useHistoryPushState = <?php if($usehistorypushstate) print 'true'; else print 'false'; ?>;			
			window.defaultLinkColor = '<?php print $defaultlinkcolor; ?>';
			window.defaultBackgroundColor = '<?php print $defaultbackgroundcolor; ?>';
		</script>
		<style>
			a, a:visited, a:active,
			ul.stats li:hover a,
			ul.stats li:hover a strong,
			#user-body a:hover div strong,
			#user-body a:hover div div,
			.permalink-link:hover,
			.stream-item.expanded > .queet .stream-item-expand,
			.stream-item-footer .with-icn .requeet-text a b:hover,
			ul.queet-actions li .with-icn,
			.queet-text span.attachment.more,
			.stream-item-header .created-at a:hover,
			.stream-item-header a.account-group:hover .name,
			.queet:hover .stream-item-expand,
			.show-full-conversation:hover,
			#new-queets-bar,
			.menu-container div,	
			#user-header:hover #user-name,
			.cm-mention, .cm-tag, .cm-group, .cm-url, .cm-email {
			    color:#0084B4;/*COLOREND*/
				}	
			ul.queet-actions li .icon,			
			.topbar .global-nav,
			.menu-container {
				background-color:#0084B4;/*BACKGROUNDCOLOREND*/
				}			
		</style>
	</head>
	<body style="background-color:<?php print $defaultbackgroundcolor; ?>">
		<div class="topbar">
			<a href="<?php print $instanceurl; ?>"><div id="logo"></div></a>
			<a id="settingslink">
				<div class="dropdown-toggle">
					<i class="nav-session"></i>
					<b class="caret"></b>
				</div>
			</a>
			<div id="top-compose" class="hidden"></div>
			<ul class="quitter-settings dropdown-menu">
				<li class="dropdown-caret right">
					<span class="caret-outer"></span>
					<span class="caret-inner"></span>
				</li>
				<li><a id="settings"></a></li>
				<?php
				if($siterootdomain == 'quitter.se') { print '<li><a id="classic" href="https://old.quitter.se/">Classic Quitter</a></li>'; } // sry for this junk				
				?><li class="dropdown-divider"></li>				
				<li><a id="logout"></a></li>
				<li class="language dropdown-divider"></li>				
				<li class="language"><a class="language-link" title="Arabic" data-lang-code="ar">العربيّة</a></li>	
				<li class="language"><a class="language-link" title="German" data-lang-code="de">Deutsch</a></li>
				<li class="language"><a class="language-link" title="English" data-lang-code="en">English</a></li>
				<li class="language"><a class="language-link" title="Spanish" data-lang-code="es">Español</a></li>	
				<li class="language"><a class="language-link" title="Esperanto" data-lang-code="eo">Esperanto</a></li>													
				<li class="language"><a class="language-link" title="Farsi" data-lang-code="fa">فارسی</a></li>									
				<li class="language"><a class="language-link" title="French" data-lang-code="fr">français</a></li>									
				<li class="language"><a class="language-link" title="Italian" data-lang-code="it">Italiano</a></li>													
				<li class="language"><a class="language-link" title="Swedish" data-lang-code="sv">svenska</a></li>					
			</ul>			
			<div id="birds-top"></div>
			<div class="global-nav">
				<div class="global-nav-inner">
					<div class="container">				
						<div id="search">
						    <input type="text" spellcheck="false" autocomplete="off" name="q" placeholder="Sök" id="search-query" class="search-input">
						    <span class="search-icon">
								<button class="icon nav-search" type="submit" tabindex="-1">
									<span> Sök </span>
								</button>						    
						    </span>
							<input type="text" spellcheck="false" autocomplete="off" id="search-query-hint" class="search-input search-hinting-input" disabled="disabled">						    
						</div>	
						<ul class="language-dropdown">
							<li class="dropdown">
								<a class="dropdown-toggle">
									<small></small>
									<span class="current-language"></span>
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li class="dropdown-caret right">
										<span class="caret-outer"></span>
										<span class="caret-inner"></span>
									</li>
									<li><a class="language-link" title="Arabic" data-lang-code="ar">العربيّة</a></li>	
									<li><a class="language-link" title="German" data-lang-code="de">Deutsch</a></li>
									<li><a class="language-link" title="English" data-lang-code="en">English</a></li>
									<li><a class="language-link" title="Spanish" data-lang-code="es">Español</a></li>									
									<li><a class="language-link" title="Esperanto" data-lang-code="eo">Esperanto</a></li>																		
									<li><a class="language-link" title="Farsi" data-lang-code="fa">فارسی</a></li>									
									<li><a class="language-link" title="French" data-lang-code="fr">français</a></li>	
									<li><a class="language-link" title="Italian" data-lang-code="it">Italiano</a></li>																														
									<li><a class="language-link" title="Swedish" data-lang-code="sv">svenska</a></li>								
								</ul>
							</li>
						</ul>					
					</div>
				</div>
			</div>
		</div>
		<div id="page-container">
			<div class="front-welcome-text">
				<h1></h1>
				<p></p>
			</div>		
			<div id="user-container" style="display:none;">		
				<div id="login-content">
					<div id="username-container">
						<input id="username" type="text" value="" tabindex="1" />
					</div>
					<table class="password-signin"><tbody><tr>
						<td class="flex-table-primary">
							<div class="placeholding-input">
								<input id="password" type="password" tabindex="2" value="" />
							</div>
						</td>
						<td class="flex-table-secondary">
							<button class="submit" type="submit" id="submit-login" tabindex="4"></button>
						</td>
					</tr></tbody></table>
					<div id="remember-forgot">
						<input type="checkbox" id="rememberme" name="rememberme" value="yes" tabindex="3" checked="checked"> <span id="rememberme_label"></span> · <a href="<?php print $instanceurl ?>main/recoverpassword"></a>
					</div>
				</div>
				<div class="front-signup">
					<h2></h2>
					<div class="signup-input-container"><input placeholder="" type="text" name="user[name]" autocomplete="off" class="text-input" id="signup-user-name"></div>
					<div class="signup-input-container"><input placeholder="" type="text" name="user[email]" autocomplete="off" id="signup-user-email"></div>
					<div class="signup-input-container"><input placeholder="" type="password" name="user[user_password]" class="text-input" id="signup-user-password"></div>
					<button id="signup-btn-step1" class="signup-btn" type="submit"></button>
					<div id="other-servers-link"></div>
				</div>
				<div id="user-header">
					<img id="user-avatar" src="" />
					<div id="user-name"></div>
					<div id="user-screen-name"></div>					
					<div id="user-profile-link"></div>							
				</div>
				<div id="user-body">
					<a><div id="user-queets"><strong></strong><div class="label"></div></div></a>
					<a><div id="user-following"><strong></strong><div class="label"></div></div></a>
					<a><div id="user-followers"><strong></strong><div class="label"></div></div></a>				
					<a><div id="user-groups"><strong></strong><div class="label"></div></div></a>									
				</div>				
				<div id="user-footer">
					<textarea id="codemirror-queet-box"></textarea>
					<div id="queet-box" class="queet-box"></div>
					<div id="queet-toolbar">
						<div id="queet-box-extras"></div>
						<div id="queet-button">
							<span id="queet-counter"></span>
							<button id="queet"></button>
						</div>						
					</div>
				</div>										
				<div class="menu-container">
					<a class="stream-selection friends-timeline" data-stream-header="" data-stream-name="statuses/friends_timeline.json"><i class="chev-right"></i></a>
					<a class="stream-selection mentions" data-stream-header="" data-stream-name="statuses/mentions.json"><i class="chev-right"></i></a>				
					<a class="stream-selection my-timeline" data-stream-header="@statuses/user_timeline.json" data-stream-name="statuses/user_timeline.json"><i class="chev-right"></i></a>				
					<a class="stream-selection favorites" data-stream-header="" data-stream-name="favorites.json"><i class="chev-right"></i></a>									
					<a href="<?php print $instanceurl ?>" class="stream-selection public-timeline" data-stream-header="" data-stream-name="statuses/public_timeline.json"><i class="chev-right"></i></a>
				</div>
				<div class="menu-container" id="history-container"></div>				
			</div>						
			<div id="feed">
				<div id="feed-header">
					<div id="feed-header-inner">
						<h2></h2>
					</div>
				</div>
				<div class="stream-item hidden"><div id="new-queets-bar"></div></div>
				<div id="feed-body"></div>
			</div>
			
			<div id="footer"></div>
		</div>
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/codemirror.3.20.js"></script>
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/jquery-2.0.2.min.js"></script>
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/jquery-ui-1.10.3.min.js"></script>
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/jquery.easing.1.3.js"></script>	    
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/jquery.minicolors.min.js"></script>	    	    
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/dom-functions-7.js"></script>		    	
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/misc-functions-7.js"></script>		    		    
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/ajax-functions-4.js"></script>		    		    	    
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/lan-7.js"></script>	
	    <script type="text/javascript" src="<?php print $qvitterpath; ?>js/qvitter-7.js"></script>		
	</body>
</html>