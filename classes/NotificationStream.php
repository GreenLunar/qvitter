<?php
/**
 *
 * Notification stream
 *
 */

if (!defined('GNUSOCIAL') && !defined('STATUSNET')) { exit(1); }

class NotificationStream
{
    protected $target  = null;

    /**
     * Constructor
     *
     * @param Profile $target Profile to get a stream for
     */
    function __construct(Profile $target)
    {
        $this->target  = $target;
    }

    /**
     * Get IDs in a range
     *
     * @param int $offset   Offset from start
     * @param int $limit    Limit of number to get
     * @param int $since_id Since this notice
     * @param int $max_id   Before this notice
     *
     * @return Array IDs found
     */
    function getNotificationIds($offset, $limit, $since_id, $max_id)
    {

        $notification = new QvitterNotification();
        $notification->selectAdd();
        $notification->selectAdd('id');
        $notification->whereAdd(sprintf('qvitternotification.to_profile_id = "%s"', $notification->escape($this->target->id)));
        $notification->whereAdd(sprintf('qvitternotification.created >= "%s"', $notification->escape($this->target->created)));

        // the user might have opted out from certain notification types
        $current_profile = Profile::current();
        $disable_notify_replies_and_mentions = Profile_prefs::getConfigData($current_profile, 'qvitter', 'disable_notify_replies_and_mentions');
        $disable_notify_favs = Profile_prefs::getConfigData($current_profile, 'qvitter', 'disable_notify_favs');
        $disable_notify_repeats = Profile_prefs::getConfigData($current_profile, 'qvitter', 'disable_notify_repeats');
        $disable_notify_follows = Profile_prefs::getConfigData($current_profile, 'qvitter', 'disable_notify_follows');
        if($disable_notify_replies_and_mentions == '1') {
            $notification->whereAdd('qvitternotification.ntype != "mention"');
            $notification->whereAdd('qvitternotification.ntype != "reply"');
            }
        if($disable_notify_favs == '1') {
            $notification->whereAdd('qvitternotification.ntype != "like"');
            }
        if($disable_notify_repeats == '1') {
            $notification->whereAdd('qvitternotification.ntype != "repeat"');
            }
        if($disable_notify_follows == '1') {
            $notification->whereAdd('qvitternotification.ntype != "follow"');
            }

        $notification->limit($offset, $limit);
        $notification->orderBy('qvitternotification.created DESC');

		if($since_id) {
	        $notification->whereAdd(sprintf('qvitternotification.id > %d', $notification->escape($since_id)));
			}

		if($max_id) {
	        $notification->whereAdd(sprintf('qvitternotification.id <= %d', $notification->escape($max_id)));
			}

        if (!$notification->find()) {
            return array();
        }

        $ids = $notification->fetchAll('id');

        return $ids;
    }

    function getNotifications($offset, $limit, $sinceId, $maxId)
    {

        $all = array();

        do {

            $ids = $this->getNotificationIds($offset, $limit, $sinceId, $maxId);

            $notifications = QvitterNotification::pivotGet('id', $ids);

            // By default, takes out false values

            $notifications = array_filter($notifications);

            $all = array_merge($all, $notifications);

            if (count($notifications < count($ids))) {
                $offset += $limit;
                $limit  -= count($notifications);
            }

        } while (count($notifications) < count($ids) && count($ids) > 0);

        return new ArrayWrapper($all);
    }



}
