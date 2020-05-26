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
    }

    /**
     * Подготовка запроса
     *
     * @param string $query
     * @return \PDOStatement
     */
    public function prepareQuery($query)
    {
        return $this->connection->prepare(empty($query) ? $this->query : $query);
    }

    /**
     * Исполнение запроса
     *
     * @param string $query
     * @return \PDOStatement|bool
     */
    public function executeQuery($query = '')
    {
        $statement = $this->prepareQuery($query);

        if ($statement->execute()) {
            return $statement;
        }

        return false;
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
            $this->query .= 'WHERE ';

            if (is_array($condition)) {
                end($condition);
                $lastConditionKey = key($condition);
                reset($condition);

                foreach ($condition as $key => $value) {
                    $this->query .= $key . '=' . $value;

                    if ($key !== $lastConditionKey) {
                        $this->query .= ' AND ';
                    }
                }
            } else {
                $this->query .= $condition;
            }
        }

        $this->query .= ';';

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
        $this->query = 'INSERT INTO ' . $this->tableName() . '(';
        $columnNames = $this->getTableColumnNames();

        end($columnNames);
        $lastColumnKey = key($columnNames);
        reset($columnNames);

        foreach ($this->getTableColumnNames() as $key => $colName) {
            $this->query .= $colName;

            if ($key !== $lastColumnKey) {
                $this->query .= ',';
            }

            $this->query .= ')';
        }

        $this->query .= ' VALUES (';

        $attributes = $this->getAttributes($columnNames);

        end($attributes);
        $lastAttributesKey = key($attributes);
        reset($attributes);


        foreach ($attributes as $key => $value) {
            $this->query .= $value;

            if ($key !== $lastAttributesKey) {
                $this->query .= ',';
            }
        }

        $this->query .= ');';

        return (bool)$this->executeQuery();
    }

    /**
     * Обновление данных в базе
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function update()
    {
        $this->query = 'UPDATE ' . $this->tableName() . ' SET ';

        $attributes = $this->getAttributes($this->getTableColumnNames());

        end($attributes);
        $lastAttributesKey = key($attributes);
        reset($attributes);

        foreach ($attributes as  $key => $attribute) {
            $this->query .= $key . ' = ' . $attribute;

            if ($key !== $lastAttributesKey) {
                $this->query .= ',';
            }
        }

        $this->query .= ';';

        return (bool)$this->executeQuery();
    }

    /**
     * Удаление записи
     *
     * @return bool
     */
    public function delete()
    {
        $pk = $this->primaryKey();

        $this->query = 'DELETE FROM ' . $this->tableName() . ' WHERE ' . $pk . ' = ' . $this->$pk . ';';

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
        $query = "SELECT COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = '{$this->connection->getDbName()}'
                    AND TABLE_NAME = '{$this->tableName()}'
                 ";

        return $this->executeQuery($query)->fetchAll();
    }
}
