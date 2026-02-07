<?php

session_start();
class AppController {
    
    protected function isGet(): bool
        {
            return $_SERVER["REQUEST_METHOD"] === 'GET';
        }

        protected function isPost(): bool
        {
            return $_SERVER["REQUEST_METHOD"] === 'POST';
        }
    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
                 
        if(file_exists($templatePath)){
            // ["message" => "Błędne hasło!"]
            extract($variables);
           // $message = "Błędne hasło!"
           // echo $message
            
            ob_start();
            include $templatePath;
            return ob_get_clean();
        } else {
            ob_start();
            include $templatePath404;
            return ob_get_clean();
        }
    }

    protected function renderError(int $code) {
    http_response_code($code);
    $path = "public/views/{$code}.html";
    
    if (file_exists($path)) {
        include $path;
    } else {
        echo "Error {$code}";
    }
    exit;
}

}