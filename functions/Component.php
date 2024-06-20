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
            
            if ($component['reference_column'] == '-1') {

                $magnitude = 'Resolution Ref';

            } elseif ($component['reference_column'] == '-2') {

                $magnitude = 'Resolution UUC';

            } elseif ($component['reference_column'] == '-3') {

                $magnitude = 'Ref Uncert';

            } else {
                $heading_id = $component['reference_column'];
                $heading = $this->getHeadingById($heading_id);
                $magnitude = $heading['title'];
            }
            
            echo '<tr>
                <td>'. $component['component'] .'</td>
                <td>'. $magnitude .'</td>
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

    public function storeAndUpdateComponent($data)
    {
        $layout_id = $data['layout_id'];
        $template_id = $data['template_id'];
        $component_name = $data['component_name'];
        $heading_id = $data['heading_id'];
        
        $query = "SELECT * FROM uncertainty_budget_tempplate WHERE layout_id = :layoutId AND template_id = :templateId AND reference_column = :headingId";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':layoutId', $layout_id, PDO::PARAM_INT);
        $statement->bindValue(':templateId', $template_id, PDO::PARAM_INT);
        $statement->bindValue(':headingId', $heading_id, PDO::PARAM_INT);
        $statement->execute();
        $existsUBT = $statement->fetch(PDO::FETCH_ASSOC);

        if ($existsUBT) {
            $query = "UPDATE uncertainty_budget_tempplate SET layout_id = :layoutId, template_id = :templateId, component = :componentName, reference_column = :headingId WHERE layout_id = :layoutId AND template_id = :templateId AND reference_column = :headingId";
        } else {
            $query = "INSERT INTO uncertainty_budget_tempplate (layout_id,template_id,component,reference_column) VALUES (:layoutId,:templateId,:componentName,:headingId)";
        }

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layoutId', $layout_id);
        $statement->bindParam(':templateId', $template_id);
        $statement->bindParam(':componentName', $component_name);
        $statement->bindParam(':headingId', $heading_id);

        if ($statement->execute()) {
            return ['status' => 'success', 'message' => 'Component has been created successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Error inserting component.'];
        }
    }

    public function deleteComponent($data)
    {
        $layout_id = $data['layout_id'];
        $template_id = $data['template_id'];
        $heading_id = $data['heading_id'];

        $query = "SELECT * FROM uncertainty_budget_tempplate WHERE layout_id = :layoutId AND template_id = :templateId AND reference_column = :headingId";
        $statement = $this->conn->prepare($query);
        $statement->bindValue(':layoutId', $layout_id, PDO::PARAM_INT);
        $statement->bindValue(':templateId', $template_id, PDO::PARAM_INT);
        $statement->bindValue(':headingId', $heading_id, PDO::PARAM_INT);
        $statement->execute();
        $existsUBT = $statement->fetch(PDO::FETCH_ASSOC);

        if ($existsUBT) {
            $query = "DELETE FROM uncertainty_budget_tempplate WHERE layout_id = :layoutId AND template_id = :templateId AND reference_column = :headingId";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':layoutId', $layout_id);
            $statement->bindParam(':templateId', $template_id);
            $statement->bindParam(':headingId', $heading_id);

            if ($statement->execute()) {
                return ['status' => 'success', 'message' => 'Component has been deleted successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Error deleting component.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Component is not created for selected Magnitude.'];
        }
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
            
            if ($component['reference_column'] == '-1') {

                $magnitude = $cth['resolution_ref'];

            } elseif ($component['reference_column'] == '-2') {

                $magnitude = $cth['resolution_uuc'];

            } elseif ($component['reference_column'] == '-3') {

                $magnitude = $cth['ref_uncert'];

            } else {
                $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE layout_id = :layoutId AND template_id = :templateId AND heading_id = :headingId AND row_id = :rowId";
                $statementCalculationTemplate = $this->conn->prepare($queryCalculationTemplate);
                $statementCalculationTemplate->bindParam(':layoutId', $layout_id, PDO::PARAM_INT);
                $statementCalculationTemplate->bindParam(':templateId', $template_id, PDO::PARAM_INT);
                $statementCalculationTemplate->bindParam(':headingId', $component['reference_column'], PDO::PARAM_INT);
                $statementCalculationTemplate->bindParam(':rowId', $row_id, PDO::PARAM_INT);
                $statementCalculationTemplate->execute();
                $ct = $statementCalculationTemplate->fetch(PDO::FETCH_ASSOC);
                $magnitude = nl2br($ct['title_value']);
            }

            echo '<tr>
                <td class="text-nowrap">'.$component['component'].'</td>
                <td>
                    <!--textare name="magnitude" class="form-control magnitude">'.$magnitude.'</textare-->
                    <input type="text" name="magnitude" class="form-control magnitude" value="'.$magnitude.'" readonly />
                </td>
                <td>
                    <select name="distribution" class="form-control distribution-field-validation distribution">
                        <option value="">Select</option>
                        <option value="1">Normal</option>
                        <option value="2">Normal (k)</option>
                        <option value="1.732050808">Rectangular</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="divisor" class="form-control divisor" readonly />
                </td>
                <td>
                    <input type="number" name="sensitivity" class="form-control sensitivity-field-validation sensitivity" />
                </td>
                <td>
                    <input type="text" name="std_uncert" class="form-control std_uncert" readonly />
                </td>
                <td>
                    <input type="text" name="dof" class="form-control dof" />
                </td>
            </tr>';
        endforeach;

        $table2HTML = ob_get_clean();
        return ['status' => 'success', 'tableHTML' => $table2HTML];
    }
}
