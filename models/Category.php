<?php
    class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // Category Properties
        public $id;
        public $category;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Categories

        public function read() {
            // Create query
            $query = 'SELECT 
                    id,
                    category
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

        //Get single Category

        public function read_single(){
            $query = 'SELECT 
                    id,
                    category
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

       // Create Category 

        public function create() {
            // PostgreSQL-compatible INSERT query
            $query = 'INSERT INTO ' . $this->table . ' (category) 
                    VALUES (:category)
                    RETURNING id, category';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data (optional but good practice)
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);

            // Execute query
            if ($stmt->execute()) {
                $newRecord = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($newRecord, JSON_PRETTY_PRINT);
                return true;
            }

            // Print error
            printf("Error: %s.\n", $stmt->errorInfo()[2]);

            return false;
        }




        //Update Category
        public function update() {
            //create query
            $query = 'UPDATE ' .
                $this->table . '
            SET
                category = :category
            WHERE
                id = :id
            RETURNING
                id,
                category';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind data
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);


        // Execute query

        if ($stmt->execute()) {
            $newRecord = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($newRecord, JSON_PRETTY_PRINT);
            return true;
        }

        //Print error
        printf("Error: %s.\n", $stmt->error);

        return false;
        }

       //Delete Category

       public function delete() {

        try {
            //Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id RETURNING id';
    
            //Prepare statement
            $stmt = $this->conn->prepare($query);
    
            //Clean id
            $this->id = htmlspecialchars(strip_tags($this->id));
    
            //Bind id
            $stmt->bindParam(':id', $this->id);
    
             //Execute query
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //echo json_encode(["success" => true, "message" => "Record deleted successfully."]);
                $newRecord = $stmt->fetch(PDO::FETCH_ASSOC);
               echo json_encode($newRecord, JSON_PRETTY_PRINT);
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