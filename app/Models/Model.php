<?php

namespace app\Models;

use InvalidArgumentException;
use PDO;
use PDOException;
use ReflectionClass;
use ReflectionProperty;

abstract class Model
{
    protected static PDO $pdo;
    protected static string $table;

    public function __construct()
    {
        static::getPDO();
    }

    private static function getPDO(): void
    {
        if (!isset(static::$pdo)) {
            static::initializeDatabase();
        }
    }

    private static function initializeDatabase(): void
    {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            static::$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit; // Si la connexion échoue, on sort pour éviter des comportements inattendus
        }
    }

    public function find(int $id): array
    {
        static::getPDO();
        $statement = static::$pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC) ? : [];
    }

    public function findAll(): array
    {
        static::getPDO();
        $statement = static::$pdo->prepare("SELECT * FROM " . static::$table);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC) ? : [];
    }

    public function update(int $id, array $data): void
    {
        static::getPDO();
        $this->validateRequiredFields($data, array_keys($data));

        $fields = implode(', ', array_map(fn ($key) => "$key = :$key", array_keys($data)));

        $statement = static::$pdo->prepare("UPDATE " . static::$table . " SET $fields WHERE id = :id");
        $statement->execute(array_merge(['id' => $id], $data));
    }

    public function updateBy(string $column, array $data): void
    {
        static::getPDO();
        $this->validateRequiredFields($data, array_keys($data));

        $fields = implode(', ', array_map(fn ($key) => "$key = :$key", array_keys($data)));

        $statement = static::$pdo->prepare("UPDATE " . static::$table . " SET $fields WHERE $column = :$column");
        $statement->execute($data);
    }

    public static function create(array $data): Model
    {
        static::getPDO();
        static::validateRequiredFields($data, array_keys($data));

        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn ($key) => ":$key", array_keys($data)));

        $statement = static::$pdo->prepare("INSERT INTO " . static::$table . " ($fields) VALUES ($placeholders)");
        $statement->execute($data);

        $id = static::$pdo->lastInsertId();
        return static::getCreatedInstance($id);
    }

    public function delete(int $id): void
    {
        static::getPDO();
        $statement = static::$pdo->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        $statement->execute(['id' => $id]);
    }

    protected static function getModelAttributes(): array
    {
        $reflector = new ReflectionClass(new static);
        $properties = $reflector->getProperties(ReflectionProperty::IS_PROTECTED);

        $attributes = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($propertyName !== 'table' && isset(static::$$propertyName)) {
                $attributes[$propertyName] = static::$$propertyName;
            }
        }

        return $attributes;
    }

    protected static function validateRequiredFields(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Missing required field: $field");
            }
        }
    }

    private static function getCreatedInstance(int $id): Model
    {
        $instance = new static();
        $data = $instance->find($id);

        if ($data) {
            foreach ($data as $key => $value) {
                $instance->$key = $value;
            }
        }

        return $instance;
    }
}
