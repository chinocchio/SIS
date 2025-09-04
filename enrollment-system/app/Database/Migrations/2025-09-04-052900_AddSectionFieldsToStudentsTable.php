<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionFieldsToStudentsTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Add missing columns only if they do not already exist
        $columnsToAdd = [];
        if (!$db->fieldExists('section_id', 'students')) {
            $columnsToAdd['section_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Current section assignment',
                'after'      => 'curriculum_id'
            ];
        }
        if (!$db->fieldExists('previous_section_id', 'students')) {
            $columnsToAdd['previous_section_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Previous section for tracking progression',
                'after'      => 'section_id'
            ];
        }
        if (!$db->fieldExists('previous_school_year', 'students')) {
            $columnsToAdd['previous_school_year'] = [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Previous school year attended',
                'after'      => 'previous_section_id'
            ];
        }

        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('students', $columnsToAdd);
        }

        // Add foreign keys with stable names; ignore errors if they already exist
        try {
            $db->query(
                "ALTER TABLE `students` \n"
                . "ADD CONSTRAINT `students_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL ON UPDATE CASCADE"
            );
        } catch (\Throwable $e) {
            // ignore if already exists
        }
        try {
            $db->query(
                "ALTER TABLE `students` \n"
                . "ADD CONSTRAINT `students_previous_section_id_foreign` FOREIGN KEY (`previous_section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL ON UPDATE CASCADE"
            );
        } catch (\Throwable $e) {
            // ignore if already exists
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        // Drop FKs if exist
        try { $this->forge->dropForeignKey('students', 'students_section_id_foreign'); } catch (\Throwable $e) {}
        try { $this->forge->dropForeignKey('students', 'students_previous_section_id_foreign'); } catch (\Throwable $e) {}

        // Drop columns if they exist
        $cols = [];
        if ($db->fieldExists('section_id', 'students')) $cols[] = 'section_id';
        if ($db->fieldExists('previous_section_id', 'students')) $cols[] = 'previous_section_id';
        if ($db->fieldExists('previous_school_year', 'students')) $cols[] = 'previous_school_year';
        if (!empty($cols)) {
            $this->forge->dropColumn('students', $cols);
        }
    }
}
