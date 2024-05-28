<?php

namespace EdvGraz\CMS;

class Image
{
  protected Database $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function deleteArticleImage(int $id): bool
  {
    try {
      $sql = "DELETE FROM images WHERE id = :id";
      $this->db->sql_execute($sql, ["id" => $id]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function push(string $filename, string $alttext): bool
  {
    try {
      $sql = "INSERT INTO images (filename, alttext) VALUES (:filename, :alttext)";
      $this->db->sql_execute($sql, ["filename" => $filename, "alttext" => $alttext]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getLatestImageId(): array
  {
    $sql = "SELECT LAST_INSERT_ID() FROM images";
    return $this->db->sql_execute($sql)->fetch();
  }
}