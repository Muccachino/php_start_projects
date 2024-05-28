<?php

namespace EdvGraz\CMS;

class User
{
  protected Database $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function fetchUserArticles(int $id): array
  {
    $sql = "SELECT a.id, a.title, a.summary, a.content, a.created, a.category_id, a.user_id, a.published, 
    c.name AS category,
    CONCAT(u.forename, ' ', u.surname) AS author,
    i.id AS image_id, i.filename AS image_file, i.alttext AS image_alt
    FROM articles AS a 
    JOIN category AS c ON a.category_id = c.id
    JOIN user AS u ON a.user_id = u.id
    LEFT JOIN images AS i ON a.images_id = i.id
    WHERE a.user_id = :id AND a.published = 1
    ORDER BY a.id DESC ";

    return $this->db->sql_execute($sql, ["id" => $id])->fetchAll();
  }

  public function fetch(int $id): array
  {
    $sql = "SELECT forename, surname, joined, profile_pic FROM user WHERE id = :id";

    return $this->db->sql_execute($sql, ["id" => $id])->fetch();
  }

  public function getAll(): array
  {
    $sql = "SELECT id, forename, surname, joined, profile_pic FROM user";

    return $this->db->sql_execute($sql)->fetchAll();
  }
}