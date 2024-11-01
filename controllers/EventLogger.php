<?php namespace Yfktn\YfktnUtil\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class EventLogger extends Controller
{
    public $implement = [
        \Backend\Behaviors\ListController::class
    ];

    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'yfktn_util.event_logger.operator' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Yfktn.YfktnUtil', 'main-menu-util', 'submenu-eventlogger');
    }

}
