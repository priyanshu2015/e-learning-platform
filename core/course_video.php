<?php
class CourseVideo{
    //db stuff
    private $conn;
    private $table = 'course_videos';

    //post properties
    public $id;
    public $title;
    public $description;
    public $course_id;
    public $created_at;
    public $url;
    public $image;

    //constructor with db connection
    public function __construct($db){
        $this->conn = $db;
    }
    //getting posts from our database
    public function read(){
        //create query
        $query = 'SELECT
        p.id,
        p.title,
        p.description,
        p.course_id,
        p.image,
        p.url,
        p.created_at
        
        
        FROM
        ' .$this->table . ' p
        LEFT JOIN courses c ON p.course_id = c.id 
        WHERE c.id = :id
        ORDER BY p.id';

    //prepare statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->course_id);
    //execute query
    $stmt->execute();

    return $stmt;

    }

    public function read_single(){
        //create query
        $query = 'SELECT
        p.id,
        p.title,
        p.description,
        p.course_id,
        p.image,
        p.url,
        p.created_at
        
        FROM
        ' .$this->table . ' p
        LEFT JOIN courses c ON p.course_id = c.id 
        WHERE p.id = :id && c.id = :c_id LIMIT 1';

        $query2 = 'SELECT
        p.id,
        p.title,
        p.description,
        p.course_id,
        p.image,
        p.url,
        p.created_at
        
        FROM
        ' .$this->table . ' p
        LEFT JOIN courses c ON p.course_id = c.id 
        WHERE c.id = ? LIMIT 1';

        //prepare statement
        
        if($this->id == 0){
            $stmt = $this->conn->prepare($query2);
            $stmt->bindParam(1, $this->course_id);
            $stmt->execute();
        }
        else{
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':c_id', $this->course_id);
            $stmt->execute();
        }
        //execute the query
        

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->image = $row['image'];
        $this->url = $row['url'];
        $this->course_id = $row['course_id'];
        $this->created_at = $row['created_at'];
    }

    public function create(){
        //create query
        $query = 'INSERT INTO ' . $this->table . ' SET title = :title, description = :description, course_id= :course_id, url=:url';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = $this->description;
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->url = htmlspecialchars(strip_tags($this->url));
        //binding of parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':url', $this->url);
        //execute query
        if($stmt->execute()){
            return true;
        }

        //print error if something goes wrong
        printf("Error %s. \n", $stmt->error);
        return false;

    }
}
?>