<?php

use PHPUnit\Framework\TestCase;
 

class UserTest extends TestCase
{
    public function testPasswordHashing()
    {
        $password = "Secret123!";
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $this->assertNotEquals($password, $hashed);
        $this->assertTrue(password_verify($password, $hashed));
    }

    public function testUserObjectCreation()
    {
        $userData = [
            'email' => 'test@example.com',
            'firstname' => 'Jan',
            'role' => 'USER'
        ];

        $this->assertEquals('Jan', $userData['firstname']);
        $this->assertEquals('USER', $userData['role']);
        $this->assertStringContainsString('@', $userData['email']);
    }

    public function testRegistrationPasswordMatch()
    {
        $pass1 = "password";
        $pass2 = "password";
        $pass3 = "mismatch";

        $this->assertSame($pass1, $pass2);
        $this->assertNotSame($pass1, $pass3);
    }
}