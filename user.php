<?php
/**
 * User.php - OOP User Model Definition
 * Second Chance - University Portal
 * 
 * Matches Lab 2 lecture slides and phpMyAdmin `users` table:
 * id, fname, lname, email, password, phone, role, created_at
 */

class User {
    private $id;
    private $fname;
    private $lname;
    private $email;
    private $password;
    private $phone;
    private $role;        // 'user' or 'admin'
    private $created_at;

    public function __construct($fname, $lname, $email, $password, $phone = null, $role = 'user', $id = null, $created_at = null) {
        $this->fname      = $fname;
        $this->lname      = $lname;
        $this->email      = $email;
        $this->password   = $password;
        $this->phone      = $phone;
        $this->role       = $role;
        $this->id         = $id ? (int)$id : null;
        $this->created_at = $created_at;
    }

    // Getters matching lecture reference
    public function getId()        { return $this->id; }
    public function getFname()     { return $this->fname; }
    public function getLname()     { return $this->lname; }
    public function getName()      { return trim($this->fname . ' ' . $this->lname); }
    public function getEmail()     { return $this->email; }
    public function getPhone()     { return $this->phone; }
    public function getPassword()  { return $this->password; }
    public function getRole()      { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }

    public function isAdmin() {
        return $this->role === 'admin';
    }

    /**
     * Find user by Email
     */
    public static function findByEmail($conn, $email) {
        $cleanEmail = mysqli_real_escape_string($conn, trim($email));
        $sql = "SELECT * FROM users WHERE email = '$cleanEmail' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return new User(
                $row['fname'],
                $row['lname'],
                $row['email'],
                $row['password'],
                $row['phone'] ?? null,
                $row['role'] ?? 'user',
                $row['id'],
                $row['created_at'] ?? null
            );
        }
        return null;
    }

    /**
     * Find user by ID
     */
    public static function findById($conn, $id) {
        $cleanId = (int)$id;
        $sql = "SELECT * FROM users WHERE id = $cleanId LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return new User(
                $row['fname'],
                $row['lname'],
                $row['email'],
                $row['password'],
                $row['phone'] ?? null,
                $row['role'] ?? 'user',
                $row['id'],
                $row['created_at'] ?? null
            );
        }
        return null;
    }
}
?>
