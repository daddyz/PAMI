<?php

namespace PAMI\Message\Action;

use PAMI\Message\OutgoingMessage;
use PAMI\Exception\PAMIException;

/**
 * DBShow action message.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Action
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class ShowChannelsAction extends OutgoingMessage
{
    public function __construct()
    {
        parent::__construct();

        $command = 'core show channels concise';

        $this->setKey('Action', 'Command');
        $this->setKey('Command', $command);

        $this->setKey('ActionID', microtime(true));

    }

    public function handleResponse($raw)
    {
        $raw = explode("\n", $raw);
        $result = array();

        foreach ($raw as $line) {
            if (strpos($line, 'Response: Follows') !== false || strpos($line, 'Privilege: Command') !== false || strpos($line, '--END COMMAND--') !== false) {
                continue;
            }

            $result[] = $line;
        }

        return $result;
    }

    /**
     * Sets Action ID.
     *
     * The ActionID can be at most 69 characters long, according to
     * {@link https://issues.asterisk.org/jira/browse/14847 Asterisk Issue 14847}.
     *
     * Therefore we'll throw an exception when the ActionID is too long.
     *
     * @param $actionID The Action ID to have this action known by
     *
     * @return void
     * @throws PAMIException When the ActionID is more then 69 characters long
     */
    public function setActionID($actionID)
    {
        if (0 == strlen($actionID)) {
            throw new PAMIException('ActionID cannot be empty.');
        }

        if (strlen($actionID) > 69) {
            throw new PAMIException('ActionID can be at most 69 characters long.');
        }

        $this->setKey('ActionID', $actionID);
    }
}