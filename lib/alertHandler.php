<?php

namespace dash\lib;

class alertHandler
{
    public function displayAlert($type, $message)
    {
        $alertType = '';

        switch ($type) {
            case 'add':
                $alertType = 'alert-success';
                break;
            case 'remove':
                $alertType = 'alert-danger';
                break;
            case 'edit':
                $alertType = 'alert-info';
                break;
            case 'error':
                $alertType = 'alert-warning';
                break;
            default:
                return;
        }

        echo '<div class="alert ' . $alertType . ' alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($message) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }

    public function handleAlert()
    {
        if (isset($_GET['add'])) {
            $this->displayAlert('add', $_GET['add']);
        }

        if (isset($_GET['remove'])) {
            $this->displayAlert('remove', $_GET['remove']);
        }

        if (isset($_GET['edit'])) {
            $this->displayAlert('edit', $_GET['edit']);
        }
        if (isset($_GET['error'])) {
            $this->displayAlert('error', $_GET['error']);
        }
    }
}
