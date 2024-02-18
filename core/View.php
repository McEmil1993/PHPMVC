<?php

class View {
    public static function render($view, $data = []) {
        extract($data);
        $view = str_replace('.', '/', $view); // Convert view name to path
        require_once "../app/views/{$view}.php";
    }
    

}
?>