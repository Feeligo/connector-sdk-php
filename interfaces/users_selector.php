<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    API Connector SDK for PHP
 * @copyright  Copyright 2012 Feeligo
 * @license
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    FeeligoUsersSelector
 * @copyright  Copyright 2012 Feeligo
 * @license
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../lib/exceptions/not_found_exception.php');

/**
 * interface of the Users Selector class
 */

interface FeeligoUsersSelector {

  /**
   * returns an array containing all the Users
   *
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter array
   */
  public function all($limit = null, $offset = 0);


  /**
   * finds a specific User by its id
   *
   * @param mixed $id argument for the SQL id='$id' condition
   * @return FeeligoUserAdapter
   */
  public function find($id, $throw = true);


  /**
   * finds a list of Users by their id's
   *
   * @param mixed array $ids
   * @return FeeligoUserAdapter[] array
   */
  public function find_all($ids);


  /**
   * returns an array containing all the Users whose name matches the query
   *
   * @param string $query the search query, argument to a SQL LIKE '%$query%' clause
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */
  public function search_by_name($query, $limit = null, $offset = 0);


  /**
   * returns an array containing all the Users whose birth date matches the
   * arguments.
   * The $year number can be null, which should return all users whose birthday
   * is on the specified $day and $month, regardless of their birth year.
   * Implementation can safely assume $year, $month, $day is a valid date.
   *
   * @param int $day the day number, from 1 to 31
   * @param int $month the month number, from 1 = January to 12 = December
   * @param int $year the year number (as a 4-digit integer), such as 2013
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */
  public function search_by_birth_date($day, $month, $year = null, $limit = null, $offset = 0);

}