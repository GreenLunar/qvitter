<?php
 
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
  ·  Contact h@nnesmannerhe.im if you have any questions.                       ·
  ·                                                                             · 
  · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · */

if (!defined('GNUSOCIAL')) { exit(1); }

class ApiQvitterAction extends ApiAction 
{
    function showQvitterJsonTimeline($notice)
    {
        $this->initDocument('json');

        $statuses = array();

        if (is_array($notice)) {
            $notice = new ArrayWrapper($notice);
        }

        while ($notice->fetch()) {
            try {
                $twitter_status = $this->twitterStatusArray($notice);
                array_push($statuses, $twitter_status);
            } catch (Exception $e) {
                common_log(LOG_ERR, $e->getMessage());
                continue;
            }
        }
        
        
        $simplified_statuses = new stdClass();
        
        $i=0;
        foreach($statuses as &$s) {
			
			foreach($s as &$ss) {
				if($ss === false || $ss === null) {
					$ss=0;
					}
				elseif($ss === true) {
					$ss=1;
					}					
				}
			foreach($s['user'] as &$su) {
				if($su === false || $su === null) {
					$su=0;
					}
				elseif($su === true) {
					$su=1;
					}					
				}				

        	$simplified_statuses->s[$i][0] = $s['id'];
        	$simplified_statuses->s[$i][1] = strtotime($s['created_at']);
        	$simplified_statuses->s[$i][2] = $s['text'];
        	$simplified_statuses->s[$i][3] = $s['statusnet_html'];        	
        	$simplified_statuses->s[$i][4] = $s['in_reply_to_status_id'];
        	$simplified_statuses->s[$i][5] = $s['in_reply_to_user_id'];
        	$simplified_statuses->s[$i][6] = $s['in_reply_to_screen_name'];
        	$simplified_statuses->s[$i][7] = $s['favorited'];
        	$simplified_statuses->s[$i][8] = $s['repeated'];
        	$simplified_statuses->s[$i][9] = $s['statusnet_in_groups'];
        	$simplified_statuses->s[$i][10] = $s['user']['id'];        
        	$simplified_statuses->s[$i][11] = $s['statusnet_conversation_id'];
        	$simplified_statuses->s[$i][12] = $s['source'];

			$simplified_statuses->u[$s['user']['id']][0] = $s['user']['screen_name'];
			$simplified_statuses->u[$s['user']['id']][1] = $s['user']['name'];
			$simplified_statuses->u[$s['user']['id']][2] = $s['user']['location'];
			$simplified_statuses->u[$s['user']['id']][3] = $s['user']['description'];
			$simplified_statuses->u[$s['user']['id']][4] = $s['user']['profile_image_url_profile_size'];
			$simplified_statuses->u[$s['user']['id']][5] = $s['user']['profile_image_url_original'];
			$simplified_statuses->u[$s['user']['id']][6] = $s['user']['groups_count'];
			$simplified_statuses->u[$s['user']['id']][7] = $s['user']['linkcolor'];
			$simplified_statuses->u[$s['user']['id']][8] = $s['user']['backgroundcolor'];
			$simplified_statuses->u[$s['user']['id']][9] = $s['user']['url'];
			$simplified_statuses->u[$s['user']['id']][10] = $s['user']['followers_count'];
			$simplified_statuses->u[$s['user']['id']][11] = $s['user']['friends_count'];
			$simplified_statuses->u[$s['user']['id']][12] = $s['user']['favourites_count'];
			$simplified_statuses->u[$s['user']['id']][13] = $s['user']['statuses_count'];
			$simplified_statuses->u[$s['user']['id']][14] = $s['user']['following'];
			$simplified_statuses->u[$s['user']['id']][15] = $s['user']['statusnet_blocking'];
			$simplified_statuses->u[$s['user']['id']][16] = $s['user']['statusnet_profile_url'];
			$simplified_statuses->u[$s['user']['id']][17] = $s['user']['cover_photo'];			
			
        	if(isset($s['retweeted_status'])) {
				$simplified_statuses->s[$i][13][0] = $s['retweeted_status']['id'];
				$simplified_statuses->s[$i][13][1] = strtotime($s['retweeted_status']['created_at']);
				$simplified_statuses->s[$i][13][2] = $s['retweeted_status']['text'];
				$simplified_statuses->s[$i][13][3] = $s['retweeted_status']['statusnet_html'];        	
				$simplified_statuses->s[$i][13][4] = $s['retweeted_status']['in_reply_to_status_id'];
				$simplified_statuses->s[$i][13][5] = $s['retweeted_status']['in_reply_to_user_id'];
				$simplified_statuses->s[$i][13][6] = $s['retweeted_status']['in_reply_to_screen_name'];
				$simplified_statuses->s[$i][13][7] = $s['retweeted_status']['favorited'];
				$simplified_statuses->s[$i][13][8] = $s['retweeted_status']['repeated'];
				$simplified_statuses->s[$i][13][9] = $s['retweeted_status']['statusnet_in_groups'];
				$simplified_statuses->s[$i][13][10] = $s['retweeted_status']['user']['id'];        
				$simplified_statuses->s[$i][13][11] = $s['retweeted_status']['statusnet_conversation_id'];
				$simplified_statuses->s[$i][13][12] = $s['retweeted_status']['source'];
				
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][0] = $s['retweeted_status']['user']['screen_name'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][1] = $s['retweeted_status']['user']['name'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][2] = $s['retweeted_status']['user']['location'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][3] = $s['retweeted_status']['user']['description'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][4] = $s['retweeted_status']['user']['profile_image_url_profile_size'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][5] = $s['retweeted_status']['user']['profile_image_url_original'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][6] = $s['retweeted_status']['user']['groups_count'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][7] = $s['retweeted_status']['user']['linkcolor'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][8] = $s['retweeted_status']['user']['backgroundcolor'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][9] = $s['retweeted_status']['user']['url'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][10] = $s['retweeted_status']['user']['followers_count'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][11] = $s['retweeted_status']['user']['friends_count'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][12] = $s['retweeted_status']['user']['favourites_count'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][13] = $s['retweeted_status']['user']['statuses_count'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][14] = $s['retweeted_status']['user']['following'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][15] = $s['retweeted_status']['user']['statusnet_blocking'];
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][16] = $s['retweeted_status']['user']['statusnet_profile_url'];				
				$simplified_statuses->u[$s['retweeted_status']['user']['id']][17] = $s['retweeted_status']['user']['cover_photo'];								
        		}																		

        	$i++;
        }

        $this->showJsonObjects($simplified_statuses);

        $this->endDocument('json');
    }
}