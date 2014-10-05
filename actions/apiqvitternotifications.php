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

if (!defined('STATUSNET')) {
    exit(1);
}

class ApiQvitterNotificationsAction extends ApiPrivateAuthAction
{
    var $notifications = null;
    var $notices = null;
    var $profiles = null;

    /**
     * Take arguments for running
     *
     * @param array $args $_REQUEST args
     *
     * @return boolean success flag
     *
     */
    function prepare($args)
    {
        parent::prepare($args);

        $this->notifications = $this->getNotifications();

        return true;
    }

    /**
     * Handle the request
     *
     * Just show the notices
     *
     * @param array $args $_REQUEST data (unused)
     *
     * @return void
     */
    function handle($args)
    {
        parent::handle($args);
        $this->showTimeline();
    }

    /**
     * Show the timeline of notices
     *
     * @return void
     */
    function showTimeline()
    {
        
        $notice = null;        
        
        $notifications_populated = array();
        
        foreach($this->notifications as $notification) {
        	
        	// all but follow has an notice
        	if($notification->ntype != 'follow') {
        		$notice = self::twitterSimpleStatusArray(Notice::getKV($notification->notice_id));
        		}
        	
        	$notifications_populated[] = array(
        								'id'=> $notification->id,
        								'from_profile'=> self::twitterUserArray(Profile::getKV($notification->from_profile_id)),
        								'ntype'=> $notification->ntype,        								        								        								
        								'notice'=> $notice,
        								'created_at'=>self::dateTwitter($notification->created),
        								'is_seen'=>$notification->is_seen        								
        								);
        	}

		// mark as seen
        foreach($this->notifications as $notification) {
			if($notification->is_seen == 0) {
				$notification->is_seen = 1;
				$notification->update();
				}             
			}    

        $this->initDocument('json');
        $this->showJsonObjects($notifications_populated);
        $this->endDocument('json');
    }

    /**
     * Get notices
     *
     * @return array notices
     */
    function getNotifications()
    {
        $notices = array();

        $profile = ($this->auth_user) ? $this->auth_user->getProfile() : null;
		
		if(!$profile instanceof Profile) {
			return false;
			}
		
        $stream = new NotificationStream($profile);

        $notifications = $stream->getNotifications(($this->page - 1) * $this->count,
                                      $this->count,
                                      $this->since_id,
                                      $this->max_id);                        

        $notifications = $notifications->fetchAll();

        return $notifications;
    }


    /**
     * Is this action read only?
     *
     * @param array $args other arguments
     *
     * @return boolean true
     */
    function isReadOnly($args)
    {
        return true;
    }

    /**
     * When was this feed last modified?
     *
     * @return string datestamp of the latest notice in the stream
     */
    function lastModified()
    {
        if (!empty($this->notifications) && (count($this->notifications) > 0)) {
            return strtotime($this->notifications[0]->created);
        }

        return null;
    }

    /**
     * An entity tag for this stream
     *
     * Returns an Etag based on the action name, language, and
     * timestamps of the first and last notice in the timeline
     *
     * @return string etag
     */
    function etag()
    {
        if (!empty($this->notifications) && (count($this->notifications) > 0)) {

            $last = count($this->notifications) - 1;

            return '"' . implode(
                ':',
                array($this->arg('action'),
                      common_user_cache_hash($this->auth_user),
                      common_language(),
                      strtotime($this->notifications[0]->created),
                      strtotime($this->notifications[$last]->created))
            )
            . '"';
        }

        return null;
    }
      
}
