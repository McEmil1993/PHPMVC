<?php
require_once 'View.php';

class Controller {
    protected function renderView($view, $data = []) {
        View::render($view, $data);
    }

    
}
?>