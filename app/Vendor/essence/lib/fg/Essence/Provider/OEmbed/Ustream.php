<?php

/**
 * Ustream provider class.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

namespace fg\Essence\Provider\OEmbed;

/**
 * 	Ustream provider (http://www.ustream.tv).
 *
 * 	@package fg.Essence.Provider.OEmbed
 */
class Ustream extends \fg\Essence\Provider\OEmbed {

    /**
     * 	{@inheritDoc}
     */
    protected $_pattern = '#ustream\.tv#i';

    /**
     * 	{@inheritDoc}
     */
    protected $_endpoint = 'http://www.ustream.tv/oembed?url=%s';

    /**
     * 	{@inheritDoc}
     */
    protected function _embed($url, $options) {
        $Media = parent::_embed($url, $options);
        return $Media;
    }
}