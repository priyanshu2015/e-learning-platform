<?php
class User{
    //db stuff
    private $conn;
    private $table = 'users';

    //post properties
    public $id;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $is_active;
    public $is_staff;
    public $is_superuser;
    public $create_at;
    public $is_instructor;

    //constructor with db connection
    public function __construct($db){
        $this->conn = $db;
    }
    //getting posts from our database
    public function read(){
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
        // insert query
    $query = 'INSERT INTO ' . $this->table . ' SET name = :name, email = :email, phone = :phone, password = :password';

    // prepare the query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    //$this->phone=htmlspecialchars(strip_tags($this->phone));
    $this->password=htmlspecialchars(strip_tags($this->password));

    // bind the values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':phone', $this->phone);

    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);

    // execute the query, also check if query was successful
    if($stmt->execute()){
    return true;
    }

    printf("Error %s. \n", $stmt->error);
    return false;
    }


    // check if given email exist in the database
    public function emailExists(){
    
        // query to check if email exists
        $query = 'SELECT id, name, phone, is_instructor, password FROM ' . $this->table . ' WHERE email = ? LIMIT 0,1';
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
    
        // bind given email value
        $stmt->bindParam(1, $this->email);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->phone = $row['phone'];
            $this->password = $row['password'];
            $this->is_instructor = $row['is_instructor'];
    
            // return true because email exists in the database
            return true;
        }
    
        // return false if email does not exist in the database
        return false;
    }
    
    // update() method will be here
}
