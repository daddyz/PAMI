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
class ShowChannelsAction extends ActionMessage
{
    public function __construct()
    {
        parent::__construct();

        $command = 'core show channels concise';

        $this->setKey('Action', 'Command');
        $this->setKey('Command', $command);
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

}