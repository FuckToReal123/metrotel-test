<?php


namespace core\base;


use core\application\components\DbConnection;

abstract class BaseDbObjet extends BaseModel
{
    /** @var string Запрос к базе */
    private $query;
    /** @var array */
    private $bindValues = [];

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
        parent::init();
    }

    /**
     * Исполнение запроса
     *
     * @return \PDOStatement|bool
     */
    public function executeQuery()
    {
        $statement = $this->prepareQuery();

        if (!$statement->execute()) {
            $this->addError('db', var_export($statement->errorInfo(), true));
            return false;
        }

        return $statement;
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

                $this->bindValues = $condition;
            } else {
                $this->query .= "{$condition};";
            }
        }

        return $this;
    }

    /**
     * Получает один элемент
     *
     * @return bool|static
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
     * @return bool|static[]
     */
    public function all()
    {
        if ($dbResult = $this->executeQuery()) {
            return $dbResult->fetchAll(\PDO::FETCH_CLASS, static::class);
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

        $this->bindValues = array_filter($attributes);

        $statement = $this->executeQuery();

        $this->{$this->primaryKey()} = $this->getConnection()->lastInsertId();

        return (bool)$statement;
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
        unset($attributes[$this->primaryKey()]);
        $attributes = array_filter($attributes);

        $this->query = "UPDATE {$this->tableName()} SET";

        end($attributes);
        $lastAttributesKey = key($attributes);
        reset($attributes);

        foreach ($attributes as $key => $attribute) {
            $this->query .= " {$key}=:{$key}";
            if ($key !== $lastAttributesKey) {
                $this->query .= ',';
            }
        }

        $this->bindValues = array_filter($attributes);

        $pk = $this->primaryKey();

        $this->query .= " WHERE {$pk} = {$this->$pk};";

        $statement = $this->executeQuery();

        return (bool)$statement;
    }

    /**
     * Удаление записи
     *
     * @return bool
     */
    public function deleteByPk($pk)
    {
        $pkName = $this->primaryKey();

        $this->query = "DELETE FROM {$this->tableName()}  WHERE {$pkName} = {$pk};";

        return (bool)$this->executeQuery();
    }

    /**
     * Возвращает объект класса
     *
     * @return static
     */
    public static function model()
    {
        return new static();
    }

    /**
     * Подготовка запроса
     *
     * @return bool|\PDOStatement
     */
    protected function prepareQuery()
    {
        $statement = $this->getConnection()->prepare($this->query);

        foreach ($this->bindValues as $name => $value) {
            $statement->bindValue(":{$name}", $value);
        }

        return $statement;
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
                    WHERE TABLE_SCHEMA = '{$this->getDbName()}'
                    AND TABLE_NAME = '{$this->tableName()}';
                 ";


        $statement = $this->executeQuery();

        return array_values(array_column($statement->fetchAll(),'COLUMN_NAME'));
    }

    /**
     * Получает название базы
     *
     * @return array|false|string
     */
    private function getDbName()
    {
        return getenv('MYSQL_DATABASE');
    }

    /**
     * Получает коннект
     *
     * @return DbConnection|\PDO
     */
    private function getConnection()
    {
        return DbConnection::getInstance();
    }
}
