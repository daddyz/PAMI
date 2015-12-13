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
class DBShowAction extends OutgoingMessage
{
    private $_param = '';

    public function __construct($db)
    {
        parent::__construct();

        $this->_param = $db;

        $command = 'database show';
        if (!empty($db)) {
            $command .= ' ' . str_replace(' ', '/', $db);
        }
        $this->setKey('Action', 'Command');
        $this->setKey('Command', $command);

        //$this->setKey('ActionID', microtime(true));

    }

    public function handleResponse($raw)
    {
        $raw = explode("\n", $raw);
        $result = array();
        foreach ($raw as $line) {
            if (strpos($line, $this->_param) !== false) {
                list($key, $value) = explode(':', $line);
                $result[trim($key)] = trim($value);
            }
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