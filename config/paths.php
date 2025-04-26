<?php
// config/paths.php
define('ROOT_PATH', dirname(__DIR__)); // Apunta a htdocs/proyecto_estudiantes
define('APP_PATH', ROOT_PATH);         // Puedes usar ROOT_PATH directamente o definir APP_PATH si necesitas flexibilidad
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('API_PATH', ROOT_PATH . '/api');
define('CONFIG_PATH', ROOT_PATH . '/config');