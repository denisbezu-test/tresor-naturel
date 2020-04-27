<?php


class Request
{
    /**
     * @var PDO $pdo
     */
    private $pdo;

    private $name;

    private $query;

    private $data;

    public function __construct($pdo, $name, $query)
    {
        $this->pdo = $pdo;
        $this->name = $name;
        $this->query = $query;
    }

    public function execute($doFetch = true)
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();
        if ($doFetch) {
            $this->data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function executeFetch()
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();
        $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getData()
    {
        return $this->data;
    }
}