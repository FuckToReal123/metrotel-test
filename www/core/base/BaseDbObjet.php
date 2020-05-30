<?php


namespace core\base;


use core\application\Application;
use core\application\components\DbConnection;

abstract class BaseDbObjet extends BaseModel
{
    /** @var string Запрос к базе */
    private $query;
    /** @var DbConnection */
    private $connection;
    /** @var \PDOStatement */
    private $statement;

    /**
     * Получает первичный ключ
     *
     * @return string
     */
    abstract public function primaryKey();

    /**
     * Получает название таблицы
     *
     * @return string
     */
    abstract public function tableName();

    /**
     * Получает лейблы для полей
     *
     * @return array
     */
    abstract public function labels();

    /** @inheritDoc */
    public function init()
    {
        $this->connection = Application::getInstance()->db;
        $this->connection->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        parent::init();
    }

    /**
     * Подготовка запроса
     */
    public function prepareQuery()
    {
        $this->statement = $this->connection->connect->prepare($this->query);
    }

    /**
     * Биндит параметры в запрос
     *
     * @param $values
     */
    public function bindValues($values)
    {
        foreach ($values as $name) {
            $this->statement->bindValue(":{$name}", $this->$name);
        }
    }

    /**
     * Исполнение запроса
     *
     * @return \PDOStatement|bool
     */
    public function executeQuery()
    {
        if (!$this->statement->execute()) {
            $this->addError('db', $this->statement->errorInfo());
            return false;
        }

        return $this->statement;
    }

    /**
     * Собираем запрос
     *
     * @param string|array $condition
     * @return $this
     */
    public function find($condition = null)
    {
        $this->query = 'SELECT * FROM ' . $this->tableName();

        if (!empty($condition)) {
            $this->query .= ' WHERE ';

            if (is_array($condition)) {
                $attributes = array_keys($condition);
                $conditionArray = [];

                foreach ($attributes as $key) {
                    $conditionArray[] = "{$key} = :{$key}";
                }

                $conditionString = implode(' AND ', $conditionArray);

                $this->query .= "{$conditionString};";

                $this->prepareQuery();
                $this->bindValues($attributes);
            } else {
                $this->query .= "{$condition};";
            }
        }

        return $this;
    }

    /**
     * Получает один элемент
     *
     * @return bool|BaseModel
     */
    public function one()
    {
        if ($dbResult = $this->executeQuery()) {
            return $dbResult->fetchObject(static::class);
        }

        return false;
    }

    /**
     * Получает все элементы
     *
     * @return bool|BaseDbObjet
     */
    public function all()
    {
        if ($dbResult = $this->executeQuery()) {
            return $dbResult->fetchAll(PDO::FETCH_CLASS, static::class);
        }

        return false;
    }

    /**
     * Сохранение данных в базу
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $attributes = $this->getAttributes($this->getTableColumnNames());
        $insertedNames = array_keys(array_filter($attributes));

        $insertedFields = implode(', ', $insertedNames);
        $insertedValues = ':' . implode(', :', $insertedNames);

        $this->query = "INSERT INTO {$this->tableName()} ({$insertedFields}) VALUES ({$insertedValues});";

        $this->prepareQuery();
        $this->bindValues($insertedNames);
        $this->executeQuery();

        $this->{$this->primaryKey()} = $this->connection->connect->lastInsertId();

        return (bool)$this->statement;
    }

    /**
     * Обновление записи
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function update()
    {
        if (!$this->validate()) {
            return false;
        }

        $attributes = $this->getAttributes();

        $this->query = "UPDATE {$this->tableName()} SET";

        foreach ($attributes as $key => $attribute) {
            $this->query .= " {$key}={$attribute}";
        }

        $pk = $this->primaryKey();

        $this->query .= " WHERE {$pk} = {$this->$pk};";

        $this->prepareQuery();
        $this->executeQuery();

        return (bool)$this->statement;
    }

    /**
     * Удаление записи
     *
     * @return bool
     */
    public function delete()
    {
        $pk = $this->primaryKey();

        $this->query = "DELETE FROM {$this->tableName()}  WHERE {$pk} = {$this->$pk};";

        return (bool)$this->executeQuery();
    }

    /**
     * Получение правил валидации
     *
     * @return array
     */
    protected abstract function getRules();

    /**
     * Получение названий столбцов
     *
     * @return array
     */
    private function getTableColumnNames()
    {
        $this->query = "SELECT COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = '{$this->connection->getDbName()}'
                    AND TABLE_NAME = '{$this->tableName()}';
                 ";

        $this->prepareQuery();
        $this->executeQuery();

        return array_values(array_column($this->statement->fetchAll(),'COLUMN_NAME'));
    }
}
