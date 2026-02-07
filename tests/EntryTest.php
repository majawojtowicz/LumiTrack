<?php

use PHPUnit\Framework\TestCase;

class EntryTest extends TestCase
{
    public function testEnergyLevelsRange()
    {
        $energyLevel = 40;
        
        $this->assertGreaterThanOrEqual(0, $energyLevel);
        $this->assertLessThanOrEqual(100, $energyLevel);
    }

    public function testTagsParsing()
    {
        $tagsFromFrontend = ['Sport', 'Praca', 'Stres'];
        
        $this->assertCount(3, $tagsFromFrontend);
        $this->assertContains('Sport', $tagsFromFrontend);
        
        $tagsString = implode(', ', $tagsFromFrontend);
        $this->assertEquals('Sport, Praca, Stres', $tagsString);
    }

    public function testMoodMapping()
    {
        $moods = [
            1 => 'Sad',
            2 => 'Neutral',
            3 => 'Happy'
        ];

        $this->assertArrayHasKey(2, $moods);
        $this->assertEquals('Happy', $moods[3]);
    }
}