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
class DBShowAction extends ActionMessage
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

}