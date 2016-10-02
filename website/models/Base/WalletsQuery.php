<?php

namespace Base;

use \Wallets as ChildWallets;
use \WalletsQuery as ChildWalletsQuery;
use \Exception;
use \PDO;
use Map\WalletsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'wallets' table.
 *
 *
 *
 * @method     ChildWalletsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildWalletsQuery orderByPln($order = Criteria::ASC) Order by the pln column
 * @method     ChildWalletsQuery orderByUsd($order = Criteria::ASC) Order by the usd column
 * @method     ChildWalletsQuery orderByEur($order = Criteria::ASC) Order by the eur column
 * @method     ChildWalletsQuery orderByChf($order = Criteria::ASC) Order by the chf column
 * @method     ChildWalletsQuery orderByRub($order = Criteria::ASC) Order by the rub column
 * @method     ChildWalletsQuery orderByCzk($order = Criteria::ASC) Order by the czk column
 * @method     ChildWalletsQuery orderByGbp($order = Criteria::ASC) Order by the gbp column
 *
 * @method     ChildWalletsQuery groupById() Group by the id column
 * @method     ChildWalletsQuery groupByPln() Group by the pln column
 * @method     ChildWalletsQuery groupByUsd() Group by the usd column
 * @method     ChildWalletsQuery groupByEur() Group by the eur column
 * @method     ChildWalletsQuery groupByChf() Group by the chf column
 * @method     ChildWalletsQuery groupByRub() Group by the rub column
 * @method     ChildWalletsQuery groupByCzk() Group by the czk column
 * @method     ChildWalletsQuery groupByGbp() Group by the gbp column
 *
 * @method     ChildWalletsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWalletsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWalletsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWalletsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWalletsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWalletsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWalletsQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildWalletsQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildWalletsQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildWalletsQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildWalletsQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildWalletsQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildWalletsQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     \UsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildWallets findOne(ConnectionInterface $con = null) Return the first ChildWallets matching the query
 * @method     ChildWallets findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWallets matching the query, or a new ChildWallets object populated from the query conditions when no match is found
 *
 * @method     ChildWallets findOneById(int $id) Return the first ChildWallets filtered by the id column
 * @method     ChildWallets findOneByPln(double $pln) Return the first ChildWallets filtered by the pln column
 * @method     ChildWallets findOneByUsd(double $usd) Return the first ChildWallets filtered by the usd column
 * @method     ChildWallets findOneByEur(double $eur) Return the first ChildWallets filtered by the eur column
 * @method     ChildWallets findOneByChf(double $chf) Return the first ChildWallets filtered by the chf column
 * @method     ChildWallets findOneByRub(double $rub) Return the first ChildWallets filtered by the rub column
 * @method     ChildWallets findOneByCzk(double $czk) Return the first ChildWallets filtered by the czk column
 * @method     ChildWallets findOneByGbp(double $gbp) Return the first ChildWallets filtered by the gbp column *

 * @method     ChildWallets requirePk($key, ConnectionInterface $con = null) Return the ChildWallets by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOne(ConnectionInterface $con = null) Return the first ChildWallets matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWallets requireOneById(int $id) Return the first ChildWallets filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByPln(double $pln) Return the first ChildWallets filtered by the pln column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByUsd(double $usd) Return the first ChildWallets filtered by the usd column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByEur(double $eur) Return the first ChildWallets filtered by the eur column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByChf(double $chf) Return the first ChildWallets filtered by the chf column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByRub(double $rub) Return the first ChildWallets filtered by the rub column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByCzk(double $czk) Return the first ChildWallets filtered by the czk column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWallets requireOneByGbp(double $gbp) Return the first ChildWallets filtered by the gbp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWallets[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWallets objects based on current ModelCriteria
 * @method     ChildWallets[]|ObjectCollection findById(int $id) Return ChildWallets objects filtered by the id column
 * @method     ChildWallets[]|ObjectCollection findByPln(double $pln) Return ChildWallets objects filtered by the pln column
 * @method     ChildWallets[]|ObjectCollection findByUsd(double $usd) Return ChildWallets objects filtered by the usd column
 * @method     ChildWallets[]|ObjectCollection findByEur(double $eur) Return ChildWallets objects filtered by the eur column
 * @method     ChildWallets[]|ObjectCollection findByChf(double $chf) Return ChildWallets objects filtered by the chf column
 * @method     ChildWallets[]|ObjectCollection findByRub(double $rub) Return ChildWallets objects filtered by the rub column
 * @method     ChildWallets[]|ObjectCollection findByCzk(double $czk) Return ChildWallets objects filtered by the czk column
 * @method     ChildWallets[]|ObjectCollection findByGbp(double $gbp) Return ChildWallets objects filtered by the gbp column
 * @method     ChildWallets[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WalletsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\WalletsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'exchange', $modelName = '\\Wallets', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWalletsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWalletsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWalletsQuery) {
            return $criteria;
        }
        $query = new ChildWalletsQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildWallets|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WalletsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = WalletsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWallets A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, pln, usd, eur, chf, rub, czk, gbp FROM wallets WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildWallets $obj */
            $obj = new ChildWallets();
            $obj->hydrate($row);
            WalletsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildWallets|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WalletsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WalletsTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the pln column
     *
     * Example usage:
     * <code>
     * $query->filterByPln(1234); // WHERE pln = 1234
     * $query->filterByPln(array(12, 34)); // WHERE pln IN (12, 34)
     * $query->filterByPln(array('min' => 12)); // WHERE pln > 12
     * </code>
     *
     * @param     mixed $pln The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByPln($pln = null, $comparison = null)
    {
        if (is_array($pln)) {
            $useMinMax = false;
            if (isset($pln['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_PLN, $pln['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pln['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_PLN, $pln['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_PLN, $pln, $comparison);
    }

    /**
     * Filter the query on the usd column
     *
     * Example usage:
     * <code>
     * $query->filterByUsd(1234); // WHERE usd = 1234
     * $query->filterByUsd(array(12, 34)); // WHERE usd IN (12, 34)
     * $query->filterByUsd(array('min' => 12)); // WHERE usd > 12
     * </code>
     *
     * @param     mixed $usd The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByUsd($usd = null, $comparison = null)
    {
        if (is_array($usd)) {
            $useMinMax = false;
            if (isset($usd['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_USD, $usd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($usd['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_USD, $usd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_USD, $usd, $comparison);
    }

    /**
     * Filter the query on the eur column
     *
     * Example usage:
     * <code>
     * $query->filterByEur(1234); // WHERE eur = 1234
     * $query->filterByEur(array(12, 34)); // WHERE eur IN (12, 34)
     * $query->filterByEur(array('min' => 12)); // WHERE eur > 12
     * </code>
     *
     * @param     mixed $eur The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByEur($eur = null, $comparison = null)
    {
        if (is_array($eur)) {
            $useMinMax = false;
            if (isset($eur['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_EUR, $eur['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eur['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_EUR, $eur['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_EUR, $eur, $comparison);
    }

    /**
     * Filter the query on the chf column
     *
     * Example usage:
     * <code>
     * $query->filterByChf(1234); // WHERE chf = 1234
     * $query->filterByChf(array(12, 34)); // WHERE chf IN (12, 34)
     * $query->filterByChf(array('min' => 12)); // WHERE chf > 12
     * </code>
     *
     * @param     mixed $chf The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByChf($chf = null, $comparison = null)
    {
        if (is_array($chf)) {
            $useMinMax = false;
            if (isset($chf['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_CHF, $chf['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($chf['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_CHF, $chf['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_CHF, $chf, $comparison);
    }

    /**
     * Filter the query on the rub column
     *
     * Example usage:
     * <code>
     * $query->filterByRub(1234); // WHERE rub = 1234
     * $query->filterByRub(array(12, 34)); // WHERE rub IN (12, 34)
     * $query->filterByRub(array('min' => 12)); // WHERE rub > 12
     * </code>
     *
     * @param     mixed $rub The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByRub($rub = null, $comparison = null)
    {
        if (is_array($rub)) {
            $useMinMax = false;
            if (isset($rub['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_RUB, $rub['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rub['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_RUB, $rub['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_RUB, $rub, $comparison);
    }

    /**
     * Filter the query on the czk column
     *
     * Example usage:
     * <code>
     * $query->filterByCzk(1234); // WHERE czk = 1234
     * $query->filterByCzk(array(12, 34)); // WHERE czk IN (12, 34)
     * $query->filterByCzk(array('min' => 12)); // WHERE czk > 12
     * </code>
     *
     * @param     mixed $czk The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByCzk($czk = null, $comparison = null)
    {
        if (is_array($czk)) {
            $useMinMax = false;
            if (isset($czk['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_CZK, $czk['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($czk['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_CZK, $czk['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_CZK, $czk, $comparison);
    }

    /**
     * Filter the query on the gbp column
     *
     * Example usage:
     * <code>
     * $query->filterByGbp(1234); // WHERE gbp = 1234
     * $query->filterByGbp(array(12, 34)); // WHERE gbp IN (12, 34)
     * $query->filterByGbp(array('min' => 12)); // WHERE gbp > 12
     * </code>
     *
     * @param     mixed $gbp The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByGbp($gbp = null, $comparison = null)
    {
        if (is_array($gbp)) {
            $useMinMax = false;
            if (isset($gbp['min'])) {
                $this->addUsingAlias(WalletsTableMap::COL_GBP, $gbp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gbp['max'])) {
                $this->addUsingAlias(WalletsTableMap::COL_GBP, $gbp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WalletsTableMap::COL_GBP, $gbp, $comparison);
    }

    /**
     * Filter the query by a related \Users object
     *
     * @param \Users|ObjectCollection $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWalletsQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \Users) {
            return $this
                ->addUsingAlias(WalletsTableMap::COL_ID, $users->getWalletId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            return $this
                ->useUsersQuery()
                ->filterByPrimaryKeys($users->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function joinUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Users');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Users');
        }

        return $this;
    }

    /**
     * Use the Users relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\UsersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWallets $wallets Object to remove from the list of results
     *
     * @return $this|ChildWalletsQuery The current query, for fluid interface
     */
    public function prune($wallets = null)
    {
        if ($wallets) {
            $this->addUsingAlias(WalletsTableMap::COL_ID, $wallets->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wallets table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WalletsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WalletsTableMap::clearInstancePool();
            WalletsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WalletsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WalletsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WalletsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WalletsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // WalletsQuery
