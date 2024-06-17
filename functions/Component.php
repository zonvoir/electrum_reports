<?php
require_once('../database.php');

class Component
{
    private $conn;

    public function __construct()
    {
        // Connect to the database 
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function loadComponents($layput_id, $template_id)
    {
        $tableHTML = $this->getTableHTML($layput_id, $template_id);

        return ['status' => 'success', 'tableHTML' => $tableHTML];
    }

    private function getTableHTML($layput_id, $template_id)
    {
        ob_start(); // Start output buffering to capture HTML
        $this->drawTable($layput_id, $template_id); // Call your drawTable method to generate the HTML
        $tableHTML = ob_get_clean(); // Get the contents of the buffer and clean it
        return $tableHTML;
    }

    public function drawTable($layput_id, $template_id)
    {
        $query = "SELECT * FROM uncertainty_budget_tempplate WHERE layout_id = :layoutId AND template_id = :templateId";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':layoutId', $layput_id, PDO::PARAM_INT);
        $statement->bindValue(':templateId', $template_id, PDO::PARAM_INT);
        $statement->execute();
        $components = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($components as $component) {
            $heading_id = $component['reference_column'];
            $heading = $this->getHeadingById($heading_id);
            echo '<tr>
                <td>'. $component['component'] .'</td>
                <td>'. $heading['title'] .'</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
        }
    }

    function getHeadingById($heading_id)
    {
        $query = "SELECT title FROM headings WHERE id = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':id', $heading_id, PDO::PARAM_INT);
        $statement->execute();
        $heading = $statement->fetch(PDO::FETCH_ASSOC);
        return $heading;
    }
}
