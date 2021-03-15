<?php


namespace app\core;


abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;


    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).")
            VALUES(".implode(',', $params).")");

        foreach ($attributes as $attribute) {
            // bind the values

            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    // a method for the pdo preprare the sql query

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

}