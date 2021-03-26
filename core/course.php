<?php
class Course{
    //db stuff
    private $conn;
    private $table = 'courses';

    //post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $description;
    public $instructor_id;
    public $instructor_name;
    public $created_at;
    public $language_medium_id;
    public $language_name;
    public $image;

    //constructor with db connection
    public function __construct($db){
        $this->conn = $db;
    }
    //getting posts from our database
    public function read(){
        //create query
        $query = 'SELECT
        c.name as category_name,
        l.name as language_name,
        i.name as instructor_name, 
        p.id,
        p.title,
        p.category_id,
        p.description,
        p.language_medium_id,
        p.image,
        p.created_at
        p.instructor_id,
        
        FROM
        ' .$this->table . ' p  
        LEFT JOIN
            course_categories c ON p.category_id = c.id
            user i ON p.instructor_id = i.id
            language_mediums l ON p.language_medium_id = l.id
            ORDER BY p.created_at DESC';

    //prepare statement
    $stmt = $this->conn->prepare($query);
    //execute query
    $stmt->execute();

    return $stmt;

    }

    public function read_single(){
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
        ' .$this->table . ' p
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

    public function create(){
        //create query
        $query = 'INSERT INTO ' . $this->table . ' SET title = :title, description = :description, instructor_id= :instructor_id, category_id= :category_id, language_medium_id= :language_medium_id, image= :image';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->instructor_id = htmlspecialchars(strip_tags($this->instructor_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->language_medium_id = htmlspecialchars(strip_tags($this->language_medium_id));
        $this->image = htmlspecialchars(strip_tags($this->image));
        //binding of parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':instructor_id', $this->instructor_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':language_medium_id', $this->language_medium_id);
        $stmt->bindParam(':image', $this->image);
        //execute query
        if($stmt->execute()){
            return true;
        }

        //print error if something goes wrong
        printf("Error %s. \n", $stmt->error);
        return false;

    }

}
