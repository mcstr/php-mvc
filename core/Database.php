<?php


namespace app\core;


class Database
{
    public \PDO $pdo;


    public function  __construct(array $config)
    {
        $dsn = $config['dsn'];
        $user = $config['user'];
        $password = $config['password'];


        $this->pdo = new \PDO($dsn, $user, $password);
        // the following line allows PDO to throw an exception in case something goes wrong.
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR.'/migrations');

        // using diff we will take the $file (an array with all the files in the migration dir) and subtract the appliedMigrations. The remaining are save in toApplyMigrations
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration)  {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            // when in the toApplyMigrations array there are some file names we get the filename without the .php extension
            require_once Application::$ROOT_DIR. '/migrations/' .$migration;
            //this will give back the filename without the extension
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }

    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ) ENGINE=INNODB");

    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);

    }

        // this contain the file names
    public function saveMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));

         $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUE 
            $str
            ");
         $statement -> execute();
    }

    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}