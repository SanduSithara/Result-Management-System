// tests/RegistrationTest.php
<?php

use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        // test database connection
        $this->db = new mysqli('localhost', 'test_user', 'test_password', 'test_database');
        
        if ($this->db->connect_error) {
            $this->fail("Database connection failed: " . $this->db->connect_error);
        }

        // Clear td
        $this->db->query("DELETE FROM users WHERE username = 'testuser'");
    }

    protected function tearDown(): void
    {
        // Close dc
        $this->db->close();
    }

    public function testRegistrationSuccess()
    {
        // POST data
        $_POST['name'] = 'Test User';
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'testpassword';
        $_POST['role'] = 'teacher'; 

        // registration script
        ob_start(); 
        include 'register.php'; 
        $output = ob_get_clean(); 

        // registration was successful
        $result = $this->db->query("SELECT * FROM users WHERE username = 'testuser'");
        $this->assertEquals(1, $result->num_rows, 'User should be registered.');

        // contains the success message
        $this->assertStringContainsString('Registration successful!', $output);
    }

    public function testUsernameAlreadyExists()
    {
        // 
        $this->db->query("INSERT INTO users (name, username, password, role) VALUES ('Existing User', 'testuser', 'hashedpassword', 'teacher')");

        //  POST data
        $_POST['name'] = 'Another User';
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'anotherpassword';
        $_POST['role'] = 'teacher'; 

       
        ob_start(); 
        include 'register.php'; 
        $output = ob_get_clean(); 

        
        $this->assertStringContainsString('Username already exists!', $output);
    }

    public function testMissingFields()
    {
        // POST data with missing fields
        $_POST['name'] = '';
        $_POST['username'] = '';
        $_POST['password'] = '';
        $_POST['role'] = 'teacher'; 

       
        ob_start(); 
        include 'register.php'; 
        $output = ob_get_clean(); 

      // error message for missing fields
        $this->assertStringContainsString('All fields are required.', $output);
    }
}
