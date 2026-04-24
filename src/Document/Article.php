<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: "articles")]
class Article
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: "string")]
    private string $title;

    #[MongoDB\Field(type: "string")]
    private string $desc;

    #[MongoDB\Field(type: "string")]
    private string $author;

    #[MongoDB\Field(type: "string")]
    private string $imgPath;

    public function getId(): string { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getDesc(): string { return $this->desc; }
    public function setDesc(string $desc): self { $this->desc = $desc; return $this; }
    public function getAuthor(): string { return $this->author; }
    public function setAuthor(string $author): self { $this->author = $author; return $this; }
    public function getImgPath(): string { return $this->imgPath; }
    public function setImgPath(string $imgPath): self { $this->imgPath = $imgPath; return $this; }
}
