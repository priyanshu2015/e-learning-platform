<?php
class VideoComment
{
    //db stuff
    private $conn;
    private $table = 'video_comments';

    //post properties
    public $id;
    public $body;
    public $create_at;
    public $user_id;
    public $video_id;

    //constructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
    //getting posts from our database
    public function read()
    {
        //create query
        $query = 'SELECT
        c.name as username,
        p.id,
        p.user_id,
        p.video_id,
        p.body,
        p.created_at
        FROM
        ' . $this->table . ' p
        LEFT JOIN users c ON p.user_id = c.id
        LEFT JOIN course_videos v ON p.video_id = v.id
        WHERE v.id = :id
        ORDER BY p.created_at DESC';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->video_id);
        //execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        //create query
        $query = 'SELECT
        c.name as category_name, 
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at
        FROM
        ' . $this->table . ' p
        LEFT JOIN
            categories c ON p.category_id = c.id
            WHERE p.id = ? LIMIT 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        //execute the query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    public function create()
    {
        //create query
        $query = 'INSERT INTO ' . $this->table . ' SET body = :body, user_id= :user_id, video_id= :video_id';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->video_id = htmlspecialchars(strip_tags($this->video_id));
        //binding of parameters
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':video_id', $this->video_id);
        //execute query
        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s. \n", $stmt->error);
        return false;
    }
}
