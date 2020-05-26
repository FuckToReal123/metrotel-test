<?php


namespace core\base;

use core\application\Application;
use core\application\components\DbConnection;
use core\validators\Validator;
use PDO;

/**
 * Class BaseModel
 */
abstract class BaseModel extends BaseObject
{
    /** @var array[] Массив ошибок */
    private $errors = [];
    /** @var array Массив валидаторов */
    private $validators;
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

    /**
     * Подготовка запроса
     *
     * @return \PDOStatement
     */
    public function prepareQuery()
    {
        return $this->connection->prepare($this->query);
    }

    /**
     * Исполнение запроса
     *
     * @return bool
     */
    public function executeQuery()
    {
        $statement = $this->prepareQuery();

        return $statement->execute();
    }

    /**
     * Собираем запрос
     *
     * @param string|array $condition
     * @return $this
     */
    public function find($condition = null)
    {
        $this->query = 'SELECT * FROM' . $this->tableName();

        if (!empty($condition)) {
            $this->query .= 'WHERE ';

            if (is_array($condition)) {
                $lastConditionKey = end($condition);
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
     * @return bool|BaseModel
     */
    public function all()
    {
        if ($dbResult = $this->executeQuery()) {
            return $dbResult->fetchAll(PDO::FETCH_CLASS, static::class);
        }

        return false;
    }

    /**
     * Cjplf
     */
    public function save()
    {

    }

    /** @inheritDoc */
    public function init()
    {
        $this->connection = Application::getInstance()->db;
        $this->createValidators();
    }

    /**
     * Получение лейбля для поля
     *
     * @param string $key
     * @return mixed
     */
    public function getLabel($key)
    {
        $labels = $this->labels();

        if (isset($labels[$key])) {
            return $labels[$key];
        }

        return $key;
    }

    /**
     * Валидация полей
     *
     * @return bool
     */
    public function validate()
    {
        foreach ($this->validators as $attribute => $validator) {
            if (!$validator->validate()) {
                $this->addError($attribute, $validator->getMessage());
            }
        }

        return !$this->hasErrors();
    }

    /**
     * Есть ли ошибки?
     *
     * @return bool
     */
    public function hasErrors()
    {
        return empty($this->errors);
    }

    /**
     * Получить ошибку для атрибута
     *
     * @param $attribute
     * @return string
     */
    public function getError($attribute)
    {
        return isset($this->errors[$attribute]) ? implode('. ', $this->errors[$attribute]) : null;
    }

    /**
     * Получить все ошибки
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Добавить ошибку для поля
     *
     * @param $attribute
     * @param $message
     */
    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    /**
     * Получение правил валидации
     *
     * @return array
     */
    protected abstract function getRules();

    /**
     * Создаёт валидаторы
     *
     * @throws \Exception
     */
    private function createValidators()
    {
        foreach ($this->getRules() as $rule) {
            $validatorClass = 'core\Validators\\' . ucfirst($rule['validator']) . 'Validator';

            if ($validatorClass instanceof Validator) {
                foreach ($rule['attributes'] as $attribute) {
                    $this->validators[$attribute][] = Validator::createValidator(
                        $rule['validator'],
                        $this->$attribute,
                        $rule['params']
                    );
                }
            } else {
                throw new \Exception('Не существующий валидатор: ' . $rule['validator']);
            }
        }
    }
}
