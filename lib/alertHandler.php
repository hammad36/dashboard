<?php

namespace dash\lib;

class alertHandler
{
    private static $instance = null;
    private $alertTypes = [
        'add' => 'alert-success',
        'remove' => 'alert-danger',
        'edit' => 'alert-info',
        'error' => 'alert-warning'
    ];

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function sanitize($data)
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public function displayAlert($type, $message)
    {
        if (!isset($this->alertTypes[$type])) return;
        $alertType = $this->alertTypes[$type];
        $message = $this->sanitize($message);

        echo '<div class="alert ' . $alertType . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }

    public function handleAlert()
    {
        foreach ($this->alertTypes as $type => $alertClass) {
            if (isset($_GET['msg']) && $_GET['type'] === $type) {
                $this->displayAlert($type, $_GET['msg']);
            }
        }
    }

    public function redirectWithMessage($path, $type, $message)
    {
        session_write_close();
        $location = "{$path}?msg=" . urlencode($message) . "&type=" . urlencode($type);
        header("Location: $location");
        exit();
    }
}
