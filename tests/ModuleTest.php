<?php

use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
       
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testFetchModules(): void
    {
        
        $_GET['student_id'] = 1;
        $_GET['current_year'] = 2024;
        $_GET['current_semester'] = 1;

        
        $stmt = $this->createMock(PDOStatement::class);

        $expectedModules = [
            [
                'module_id' => 1,
                'module_name' => 'Software Engineering',
                'module_code' => 'SE101',
                'year' => 2024,
                'semester' => 1,
                'grade' => 'A',
                'mid_marks' => 85,
            ],
            [
                'module_id' => 2,
                'module_name' => 'Data Structures',
                'module_code' => 'DS102',
                'year' => 2024,
                'semester' => 1,
                'grade' => 'B+',
                'mid_marks' => 78,
            ],
        ];

        
        $stmt->method('fetchAll')->willReturn($expectedModules);

        
        $this->pdo->method('prepare')->willReturn($stmt);

        
        $modules = $this->fetchModules($_GET['student_id']);

       
        $this->assertArrayHasKey(2024, $modules);
        $this->assertArrayHasKey(1, $modules[2024]);
        $this->assertCount(2, $modules[2024][1]);

        
        $this->assertEquals('SE101', $modules[2024][1][0]['module_code']);
        $this->assertEquals('A', $modules[2024][1][0]['grade']);
    }

    private function fetchModules($studentId): array
    {
        
        $sql = "SELECT m.id as module_id, m.module_name, m.module_code, sm.year, sm.semester, g.grade, g.mid_marks
                FROM student_modules sm
                INNER JOIN modules m ON sm.module_id = m.id
                LEFT JOIN grades g ON sm.student_id = g.student_id AND sm.module_id = g.module_id
                WHERE sm.student_id = :student_id
                ORDER BY sm.year, sm.semester";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();

        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedModules = [];
        foreach ($modules as $row) {
            $groupedModules[$row['year']][$row['semester']][] = $row;
        }

        return $groupedModules;
    }
}