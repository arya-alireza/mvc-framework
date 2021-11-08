<?php

namespace Core;

use Database\CreateMigrationsTable;
use Composer\Script\Event;

class Command
{
    public static function migrate()
    {
        CreateMigrationsTable::up();
        $dir = realpath("database");
        $scans = array_diff(
            scandir($dir),
            array("...", "..", ".", "CreateMigrationsTable.php")
        );
        foreach ($scans as $item) {
            $obj = "Database\\" . str_replace(".php", "", $item);
            if (class_exists($obj)) {
                $obj::up();
                $obj::insertMigrate();
            }
        }
    }

    public static function migrateRefresh()
    {
        $dir = realpath("database");
        $scans = array_diff(
            scandir($dir),
            array("...", "..", ".", "CreateMigrationsTable.php")
        );
        foreach ($scans as $item) {
            $obj = "Database\\" . str_replace(".php", "", $item);
            if (class_exists($obj)) {
                $obj::down();
            }
        }
        CreateMigrationsTable::down();
        self::migrate();
    }

    public static function makeModel(Event $event)
    {
        $args = $event->getArguments();
        $name = $args[0];
        $model = fopen("app/Models/$name.php", "w");
        $txt = '<?php

namespace App\Models;

use Core\Model;

class '.$name.' extends Model
{
    
}';
        fwrite($model, $txt);
        fclose($model);
        if (isset($args[1]) && $args[1] == "m") self::makeMigration($event);
    }

    public static function makeMigration(Event $event)
    {
        $args = $event->getArguments();
        $name = $args[0];
        $migrate = fopen("database/Create$name"."Table.php", "w");
        $txt = '<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class Create'.$name.'Table extends Migration
{
    protected function up()
    {
        Schema::create('.strtolower($name).', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    protected function down()
    {
        Schema::drop('.strtolower($name).');
    }
}';
        fwrite($migrate, $txt);
        fclose($migrate);
    }

    public static function makeController(Event $event)
    {
        $args = $event->getArguments();
        $input = explode("/", $args[0]);
        if (count($input) > 1) {
            $dir = $input[0];
            $name= $input[1];
            $ctrl = fopen("app/Controllers/$dir/$name.php", "w");
            $txt = "<?php

namespace App\Controllers\/$dir;

";
        } else {
            $name= $input[0];
            $ctrl = fopen("app/Controllers/$name.php", "w");
            $txt = '<?php

namespace App\Controllers;

';
        }
$txt .= 'use Core\Controller;

class '.$name.' extends Controller
{
    
}';
        fwrite($ctrl, str_replace("/", "", $txt));
        fclose($ctrl);
    }
}