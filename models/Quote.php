<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';

        // Quote Properties
        public $id;
        public $quote;
        public $author_id;
        public $author;
        public $category_id;
        public $category; 

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Quotes

        public function read() {
            // Create query
            $query = 'SELECT 
                    c.category as category,
                    a.author as author,
                    q.id, 
                    q.category_id,
                    q.quote,
                    q.author_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    authors a ON q.author_id = a.id
                LEFT JOIN
                    categories c ON q.category_id = c.id
                ORDER BY
                    q.id ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        //Get single Quote

        public function read_single(){
            $query = 'SELECT 
                    c.category as category,
                    a.author as author,
                    q.id, 
                    q.category_id,
                    q.quote,
                    q.author_id
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    authors a ON q.author_id = a.id
                LEFT JOIN
                    categories c ON q.category_id = c.id
                WHERE
                    q.id=?
                LIMIT 1';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Bind ID
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            // Fetch array
            $row = $stmt->fetch(PDO::FETCH_ASSOC);



            if($row){

                // Set Properties
                $this->quote = $row['quote'];
                $this->author_id = $row['author_id'];
                $this->author = $row['author'];
                $this->category_id = $row['category_id'];
                $this->category = $row['category'];
            } else {
                return false;
            }
        }

       // Create Quote 

        public function create() {
            // PostgreSQL-compatible INSERT query
            $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                    VALUES (:quote, :author_id, :category_id)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data (optional but good practice)
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = (int) $this->author_id; // Convert to integer
            $this->category_id = (int) $this->category_id; // Convert to integer

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);

            // Execute query
            if ($stmt->execute()) {
                return true;
            }

            // Print error
            printf("Error: %s.\n", $stmt->errorInfo()[2]);

            return false;
        }




        //Update Quote
        public function update() {
            //create query
            $query = 'UPDATE ' . 
                $this->table . '
            SET
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
            WHERE
                id = :id';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind data
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        //Execute query
        if($stmt->execute()){
            return true;
        }
        //Print error
        printf("Error: %s.\n", $stmt->error);

        return false;
        }

        //Delete Quote
        public function delete() {
            //Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean id
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind id
        $stmt->bindParam(':id', $this->id);

         //Execute query
         if($stmt->execute()){
            return true;
        }
        
        //Print error
        printf("Error: %s.\n", $stmt->error);

        return false;

        }
    }