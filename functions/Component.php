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

    function loadTable2($data)
    {
        $layout_id = $data['layout_id'];
        $template_id = $data['template_id'];
        $row_id = $data['row_id'];

        $query = "SELECT * FROM uncertainty_budget_tempplate WHERE layout_id = :layoutId AND template_id = :templateId";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':layoutId', $layout_id, PDO::PARAM_INT);
        $statement->bindValue(':templateId', $template_id, PDO::PARAM_INT);
        $statement->execute();
        $components = $statement->fetchAll(PDO::FETCH_ASSOC);

        ob_start(); 
        foreach($components as $component): 
            
            $checkTemplateIdQuery  = "SELECT * FROM calculation_template_header WHERE layout_id = :layoutId AND template_id = :templateId";
            $checkStatement  = $this->conn->prepare($checkTemplateIdQuery );
            $checkStatement->bindParam(':layoutId', $layout_id, PDO::PARAM_INT);
            $checkStatement->bindParam(':templateId', $template_id, PDO::PARAM_INT);
            $checkStatement ->execute();
            $cth = $checkStatement ->fetch();
            
            if ($component['reference_column']) {
                $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE layout_id = :layoutId AND template_id = :templateId AND heading_id = :headingId AND row_id = :rowId";
                $statementCalculationTemplate = $this->conn->prepare($queryCalculationTemplate);
                $statementCalculationTemplate->bindParam(':layoutId', $layout_id, PDO::PARAM_INT);
                $statementCalculationTemplate->bindParam(':templateId', $template_id, PDO::PARAM_INT);
                $statementCalculationTemplate->bindParam(':headingId', $component['reference_column'], PDO::PARAM_INT);
                $statementCalculationTemplate->bindParam(':rowId', $row_id, PDO::PARAM_INT);
                $statementCalculationTemplate->execute();
                $ct = $statementCalculationTemplate->fetch(PDO::FETCH_ASSOC);
            } else {
                $ct = [];
            }
            
            $titleValue = !empty($ct) ? nl2br($ct['title_value']) : '';
            // $refResulation = $component
            echo '<tr>
                <td>'.$component['component'].'</td>
                <td>'.$titleValue.'</td>
                <td>Normal</td>
                <td>1</td>
                <td>1</td>
                <td></td>
                <td></td>
            </tr>';
        endforeach;

        $table2HTML = ob_get_clean();
        return ['status' => 'success', 'tableHTML' => $table2HTML];
    }
}
