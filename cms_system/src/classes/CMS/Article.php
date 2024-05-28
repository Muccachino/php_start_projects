<?php

namespace EdvGraz\CMS;

class Article
{
  protected Database $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function getAll(int $cat_id = null, bool $published = true, int $user_id = null, int $limit = 1000): array
  {
    $sql = "SELECT a.id, a.title, a.summary, a.category_id, a.created, a.user_id, a.published, 
    c.name AS category,
    CONCAT(u.forename, ' ', u.surname) AS author,
    i.filename AS image_file, i.alttext AS image_alt
    FROM articles AS a 
    JOIN category AS c ON a.category_id = c.id
    JOIN user AS u ON a.user_id = u.id
    LEFT JOIN images AS i ON a.images_id = i.id
    WHERE (a.category_id = :cat_id OR :cat_id IS NULL)
    AND (a.user_id = :user_id OR :user_id IS NULL)";

    if ($published) {
      $sql .= " AND a.published = 1";
    }

    $sql .= " ORDER BY a.id DESC LIMIT :limit;";

    return $this->db->sql_execute($sql, ["cat_id" => $cat_id, "user_id" => $user_id, "limit" => $limit])->fetchAll();
  }

  public function getArticleCount(string $searchTerm): int
  {
    $sql = "SELECT COUNT(*) FROM articles
            WHERE published = 1 AND (title LIKE :search OR summary = :search OR content LIKE :search)";

    return $this->db->sql_execute($sql, ["search" => "%$searchTerm%"])->fetchColumn();
  }

  public function getSearchedArticles(string $searchTerm, int $per_page, int $offset): array
  {
    $sql = "SELECT a.id, a.title, a.summary, a.category_id, a.user_id, c.name AS category,
            CONCAT(u.forename, ' ', u.surname) AS author,
            i.filename AS image_file,
            i.alttext AS image_alt
            FROM articles AS a 
            JOIN category AS c ON a.category_id = c.id 
            JOIN user AS u ON a.user_id = u.id
            LEFT JOIN images AS i ON a.images_id = i.id
            WHERE a.published = 1 AND (a.title LIKE :search OR a.summary LIKE :search OR a.content LIKE :search)
            ORDER BY a.id DESC 
            LIMIT :per_page
            OFFSET :offset";

    return $this->db->sql_execute($sql, ["search" => "%$searchTerm%", "per_page" => $per_page, "offset" => $offset])->fetchAll();
  }

  public function setImageIdNull(int $id): bool
  {
    try {
      $sql = "UPDATE articles SET images_id = NULL WHERE id = :id";
      $this->db->sql_execute($sql, ["id" => $id]);
      return true;
    } catch (PDOException $e) {
      return false;
    }

  }

  public function imageInUse(int $id): bool
  {

    $sql = "SELECT COUNT(images_id) FROM articles 
            WHERE images_id = :id";
    $count = $this->db->sql_execute($sql, ["id" => $id])->fetch();

    return $count["COUNT(images_id)"] > 1 ? true : false;
  }

  public function fetch(int $id, bool $published = true): array
  {
    $sql = "SELECT a.id, a.title, a.summary, a.content, a.created, a.category_id, a.images_id, a.user_id, a.published, 
    c.name AS category,
    CONCAT(u.forename, ' ', u.surname) AS author,
    i.id AS image_id, i.filename AS image_file, i.alttext AS image_alt
    FROM articles AS a 
    JOIN category AS c ON a.category_id = c.id
    JOIN user AS u ON a.user_id = u.id
    LEFT JOIN images AS i ON a.images_id = i.id
    WHERE a.id = :id";

    if ($published) {
      $sql .= " AND a.published = 1";
    }
    $sql .= ";";

    return $this->db->sql_execute($sql, ["id" => $id])->fetch();
  }

  public function delete(int $id): bool
  {
    try {
      $sql = "DELETE FROM articles WHERE id = :id";
      $this->db->sql_execute($sql, ["id" => $id]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function update(array $data): bool
  {
    try {
      $sql = "UPDATE articles SET title = :title, summary = :summary, content = :content, category_id = :category_id,
                    user_id = :user_id, images_id = :images_id, published = :published WHERE id = :id";
      $test = $this->db->sql_execute($sql, $data);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function push(array $data): bool
  {
    try {
      $sql = "INSERT INTO articles (title, summary, content, category_id, user_id, images_id, published)
                VALUES (:title, :summary, :content, :category_id, :user_id, :images_id, :published)";
      $this->db->sql_execute($sql, $data);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

}