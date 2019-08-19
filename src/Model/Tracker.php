<?php

namespace Yasaie\Tracker\Model;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

/**
 * @author Payam Yasaie <payam@yasaie.ir>
 *
 * Class Tracker
 * @package App
 * @mixin \Eloquent
 */
class Tracker extends Model
{
    /**
     * @package getAttribute
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param string $key
     *
     * @return Carbon|Verta|mixed
     * @throws \Exception
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (class_exists('\Hekmatinasser\Verta\Verta')
            and in_array($key, ['updated_at', 'created_at'])) {
            \Hekmatinasser\Verta\Verta::setStringFormat('Y-m-d - H:i');
            $date = app()->getLocale() == 'fa'
                ? new \Hekmatinasser\Verta\Verta($value)
                : new \Carbon\Carbon($value);
            return $date;
        }

        return $value;
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-19
     *
     * @return Agent
     */
    public function agent()
    {
        $agent = new Agent();
        $agent->setUserAgent($this->agent);
        return $agent;
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-19
     *
     * @param $value
     */
    public function setAgentAttribute($value)
    {
        $agent = new Agent();
        $agent->setUserAgent($value);

        $this->attributes['agent'] = $value;

        # Platform
        $item = $this->agent()->platform();
        $version = $this->agent()->version($item);
        $this->attributes['platform'] = "$item $version";

        # Browser
        $item = $this->agent()->browser();
        $version = $this->agent()->version($item);
        $this->attributes['browser'] = "$item $version";

        # Robot
        $this->attributes['robot'] = $this->agent()->robot();

        # Device
        $this->attributes['robot'] = $this->agent()->device();
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-19
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
