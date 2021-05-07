<?php

namespace App\Framework;

class Colors {
    private $foreground_colors = [];
    private $background_colors = [];

    public function __construct() {
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';
    }

    public function write($string, $foreground_color = null, $background_color = null) {
        $colored_string = "";

        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        $colored_string .=  $string . "\033[0m";
        return $colored_string;
    }
    public function getForegroundColors() {
        return array_keys($this->foreground_colors);
    }
    public function getBackgroundColors() {
        return array_keys($this->background_colors);
    }
}

class Kernel {
    private $text = null;

    public function __construct() {
        $this->text = new Colors();
    }

    public function env($name) {
        $file = file_get_contents("./.env");
        $file = explode(PHP_EOL, $file);
        foreach ($file as $f => $config) {
            $c = explode("=", $config);
            if ($c[0] == $name) {
                return $c[1];
            }
        }
        return $file;
    }
    public function run($commands) {
        $removeArtisan = array_splice($commands, 0, 1);
        
        $firstCommand = explode(":", $commands[0]);
        if ($firstCommand[0] == "make") {
            $subjectName = $commands[1];
            if ($firstCommand[1] == "controller") {
                $makeController = self::makeController($subjectName);
            }else if ($firstCommand[1] == "model") {
                $makeModel = self::makeModel($subjectName);
            }else if ($firstCommand[1] == "middleware") {
                $makeMiddleware = self::makeMiddleware($subjectName);
            }
        }else if ($firstCommand[0] == "serve") {
            $baseUrl = $this->env('BASE_URL');
            $baseUrl = explode("//", $baseUrl)[1];

            echo exec("php -S ".$baseUrl." -t public/");
        }else {
            echo $this->text->write(" Sorry, I don't understand what you told me to do ", "black", "red") . "\n";
        }
        return $commands;
    }
    public function generateFile($fileName, $content) {
        $openFile = fopen($fileName, "w");
        fwrite($openFile, $content);
        fclose($openFile);
    }
    public function makeController($controllerName) {
        $content = "<?php

namespace App\Controllers;

use App\Framework\Request;

class ".$controllerName." {
    //
}
";
        $this->generateFile("./app/Controllers/" . $controllerName . ".php", $content);
        
        echo $this->text->write(" " . $controllerName . " ", "black", "green") . " has been created in /app/Controllers/\n";
    }
    public function makeModel($modelName) {
        $content = "<?php

namespace App\Models;

use App\Framework\Model;

class ".$modelName." extends Model {
    // 
}";
        $this->generateFile("./app/Models/" . $modelName . ".php", $content);

        echo "Model ". $this->text->write(" " . $modelName . " ", "black", "green") . " has been created in /app/Models/\n";
    }
    public function makeMiddleware($middlewareName) {
        $content = "<?php

namespace App\Middleware;

use App\Framework\Auth;

class ".$middlewareName." {
    public function handle() {
        // 
    }
}";
        $this->generateFile("./app/Middleware/" . $middlewareName . ".php", $content);
        echo "Middleware ". $this->text->write(" " . $middlewareName . " ", "black", "green") . " has been created in /app/Middleware/\n";
    }
}