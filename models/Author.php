<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // Author Properties
        public $id;
        public $author;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Authors

        public function read() {
            // Create query
            $query = 'SELECT 
                    id,
                    author
                FROM
                    ' . $this->table . ' 
                ORDER BY
                    id ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        //Get single Author

        public function read_single(){
            $query = 'SELECT 
                    id,
                    author
                FROM
                    ' . $this->table . ' 
                WHERE
                    id=?
                LIMIT 1';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            return $stmt;

        }

       // Create Author 

        public function create() {
            // PostgreSQL-compatible INSERT query
            $query = 'INSERT INTO ' . $this->table . ' (author) 
                    VALUES (:author)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data (optional but good practice)
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error
            printf("Error: %s.\n", $stmt->errorInfo()[2]);

            return false;
        }




        //Update Author
        public function update() {
            //create query
            $query = 'UPDATE ' .
                $this->table . '
            SET
                author = :author
            WHERE
                id = :id';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind data
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        //Execute query
        if($stmt->execute()){
            return true;
        }
        //Print error
        printf("Error: %s.\n", $stmt->error);

        return false;
        }



        //Delete Author

        public function delete() {

        try {
            //Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
            //Prepare statement
            $stmt = $this->conn->prepare($query);
    
            //Clean id
            $this->id = htmlspecialchars(strip_tags($this->id));
    
            //Bind id
            $stmt->bindParam(':id', $this->id);
    
             //Execute query
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => true, "message" => "Record deleted successfully."]);
            } else {
                echo json_encode(["error" => "Error deleting record:", "message" => "No record found with the provided ID."]);
            }



        } catch (PDOException $e) {
            
            if ($e->getCode() == '23503') {
                http_response_code(400); // Bad Request
                echo json_encode([
                    "error" => "Error deleting record:",
                    "message" => "Cannot delete this record because it is referenced in another table."
                ]);
                return;
            }
    
            // General database error
            http_response_code(500); // Internal Server Error
            echo json_encode([
                "error" => true,
                "message" => "A database error occurred.",
                "details" => $e->getMessage() // Remove in production for security
            ]);
        }
    }
}

