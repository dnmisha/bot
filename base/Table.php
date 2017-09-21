<?php namespace bot\base;

use Bot;
use yii\db\Connection;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * A database table has its own unique name and consists
 * of columns and rows.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Table
 * @package bot\base
 */
abstract class Table extends ActiveRecord
{

    /**
     * Returns the database connection used by this AR class.
     * By default, the "db" application component is used as
     * the database connection. You may override this method if
     * you want to use a different database connection.
     *
     * @return Connection the database connection used
     * by this AR class.
     */
    public static function getDb()
    {
        return Bot::getDb();
    }

    /**
     * Declares the name of the database table associated
     * with this AR class.
     *
     * @return string the table name
     * @throws \yii\base\NotSupportedException
     */
    public static function tableName()
    {
        $tableName = self::correctTableName();
        $tableSchema = self::getDb()
            ->getSchema()
            ->getTableSchema($tableName);

        if ($tableSchema == null) {
            self::createTable();
        }

        return $tableName;
    }

    /**
     * this method returns the class name as the table name by
     * calling [[Inflector::camel2id()]] with prefix [[Connection::tablePrefix]].
     * For example if [[Connection::tablePrefix]] is `tbl_`, `Customer` becomes
     * `tbl_customer`, and `OrderItem` becomes `tbl_order_item`. You may override
     * this method if the table is not named after this convention.
     *
     * @return string the table name
     * @throws Exception
     */
    public static function correctTableName()
    {
        $className = self::className();
        $baseName = StringHelper::basename($className);
        $correctName = Inflector::camel2id($baseName, '_');

        if (is_int(Bot::$id)) {
            return Bot::$id . '_' . $correctName;
        }

        return $correctName;
    }

    /**
     * Creates a SQL command for dropping a DB table.
     * @return $this the command object itself
     */
    public static function dropTable()
    {
        self::getDb()->createCommand()
            ->dropTable(self::correctTableName())
            ->execute();
    }

    /**
     * Creates a SQL command for truncating a DB table.
     * @return $this the command object itself
     */
    public static function truncateTable()
    {
        self::getDb()->createCommand()
            ->truncateTable(self::correctTableName())
            ->execute();
    }

    /**
     * Creates a SQL command for creating a new DB table.
     *
     * The columns in the new table should be specified as name-definition
     * pairs (e.g. 'name' => 'string'), where name stands for a column name which
     * will be properly quoted by the method, and definition
     * stands for the column type which can contain an abstract DB type.
     * The method [[QueryBuilder::getColumnType()]] will be called
     * to convert the abstract column types to physical ones. For example, `string`
     * will be converted as `varchar(255)`, and `string not null` becomes `
     * varchar(255) not null`.
     *
     * If a column is specified with definition only (e.g. 'PRIMARY KEY (name, type)'),
     * it will be directly inserted into the generated SQL.
     *
     * @return $this the command object itself
     */
    public static function createTable()
    {
        $option = '';
        $tableName = self::correctTableName();
        $columns = (new static)->tableFields();

        if (isset($columns['__option'])) {
            $option = $columns['__option'];
            unset($columns['__option']);
        }

        $keys = '';
        foreach ($columns as $key => $value) {
            if (is_int($key)) {
                $keys .= ', ' . $value;
                unset($columns[$key]);
            }
        }

        if (!empty($keys)) {
            $lastKey = key(array_slice($columns, -1, 1, true));
            $columns[$lastKey] .= $keys;
        }

        self::getDb()->createCommand()
            ->createTable($tableName, $columns, $option)
            ->execute();
    }

    /**
     * when table not found, automatic create table
     * with fields.
     *
     * @return array of table fields
     */
    abstract public function tableFields();
}