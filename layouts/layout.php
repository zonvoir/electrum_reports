<?php
require_once('../database.php');

class Layout
{
    private $conn;

    public function __construct()
    {
        // Connect to the database 
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function handleaddTitle($postData)
    {
        $title = $postData['title'];
        $templateID = $postData['layout_template_id'];
        $level = $postData['level'];
        $layoutID = $postData['layout_id'];
        $column_type = $postData['column_type'];
        $column_function = $postData['column_function'];

        $tableHTML = $this->getTableHTML($layoutID, $templateID);
        $queryHeadings = "INSERT INTO headings (title,layout_template_id,level,column_type) 
                          VALUES (:title,:templateID,:level,:column_type)";

        $statementHeadings = $this->conn->prepare($queryHeadings);
        $statementHeadings->bindParam(':title', $title);
        $statementHeadings->bindParam(':templateID', $templateID);
        $statementHeadings->bindParam(':level', $level);
        $statementHeadings->bindParam(':column_type', $column_type);

        if ($statementHeadings->execute()) {
            $headingsId = $this->conn->lastInsertId(); // Get the last inserted ID from headings table


            if ($column_type != "DATA") {
                // Insert into column_functions table
                $queryFunctions = "INSERT INTO column_functions (headings_id, column_function) 
                               VALUES (:headingsId, :column_function)";
                $statementFunctions = $this->conn->prepare($queryFunctions);
                $statementFunctions->bindParam(':headingsId', $headingsId);
                $statementFunctions->bindParam(':column_function', $column_function);

                if ($statementFunctions->execute()) {
                    return ['status' => 'success', 'tableHTML' => $tableHTML];
                } else {
                    return ['status' => 'error', 'message' => 'Error inserting column function into column_functions table.', 'tableHTML' => $tableHTML];
                }
            }
        } else {
            return ['status' => 'error', 'message' => 'Error inserting title.', 'tableHTML' => $tableHTML];
        }
    }

    // public function handleaddTitle($postData)
    // {
    //     $title = $postData['title'];
    //     $templateID = $postData['layout_template_id'];
    //     $level = $postData['level'];
    //     $layoutID = $postData['layout_id'];
    //     $column_type = $postData['column_type'];
    //     $column_function = $postData['column_function'];

    //     $tableHTML = $this->getTableHTML($layoutID, $templateID);
    //     $query = "INSERT INTO headings (title,layout_template_id,level,column_type,column_function) VALUES (:title,:templateID,:level,:column_type,:column_function)";

    //     $statement = $this->conn->prepare($query);
    //     $statement->bindParam(':title', $title);
    //     $statement->bindParam(':templateID', $templateID);
    //     $statement->bindParam(':level', $level);
    //     $statement->bindParam(':column_type', $column_type);
    //     $statement->bindParam(':column_function', $column_function);

    //     if ($statement->execute()) {
    //         return ['status' => 'success', 'tableHTML' => $tableHTML];
    //     } else {
    //         return ['status' => 'error', 'message' => 'Error inserting title.', 'tableHTML' => $tableHTML];
    //     }
    // }

    public function handleaddSubTitle($postData)
    {
        $title = $postData['title'];
        $headingsId = $postData['headings_id'];
        $level = $postData['level'];
        $templateID = $postData['template_id'];
        $column_type = $postData['column_type'];
        $column_function = $postData['column_function'];
        $layoutID = $postData['layoutID'];

        $query = "INSERT INTO headings (parent_id, title, level, layout_template_id,column_type) VALUES (:headingsId, :title, :level,:templateID,:column_type)";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':headingsId', $headingsId);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':level', $level);
        $statement->bindParam(':templateID', $templateID);
        $statement->bindParam(':column_type', $column_type);

        if ($statement->execute()) {

            $lastInsertedId = $this->conn->lastInsertId(); // Get the last inserted ID from headings table
            $this->updateColspan($lastInsertedId, $headingsId, $level, $templateID, $column_type, $column_function);

            if ($column_function !=null && $column_function !="" ) {
                // Insert into column_functions table
                $queryFunctions = "INSERT INTO column_functions (headings_id, column_function) 
                               VALUES (:headingsId, :column_function)";
                $statementFunctions = $this->conn->prepare($queryFunctions);
                $statementFunctions->bindParam(':headingsId', $lastInsertedId);
                $statementFunctions->bindParam(':column_function', $column_function);

                if ($statementFunctions->execute()) {
                    $tableHTML = $this->getTableHTML($layoutID, $templateID);
                    return ['status' => 'success', 'tableHTML' => $tableHTML];
                } else {
                    $tableHTML = $this->getTableHTML($layoutID, $templateID);
                    return ['status' => 'error', 'message' => 'Error inserting column function into column_functions table.', 'tableHTML' => $tableHTML];
                }
            } else {
                $tableHTML = $this->getTableHTML($layoutID, $templateID);
                return ['status' => 'success', 'tableHTML' => $tableHTML];
            }
        } else {
            return ['status' => 'error', 'message' => 'Error inserting title.'];
        }

        // if ($statement->execute()) {
        //     $this->updateColspan($this->conn->lastInsertId(), $headingsId, $level, $templateID, $column_type, $column_function);
        //     return ['status' => 'success'];
        // } else {
        //     return ['status' => 'error', 'message' => 'Error 454545 inserting title.'];
        // }
    }

    public function updateColspan($headerId, $parent, $level, $layoutTemplateID)
    {
        $updateCols = "UPDATE headings SET colspan = colspan + 1 WHERE id = :id";

        $updateCol = $this->conn->prepare($updateCols);
        $updateCol->bindParam(':id', $headerId, PDO::PARAM_INT);
        $updateCol->execute();

        if ($level == 2) {
            $query = "SELECT sum(colspan) as colspan FROM headings WHERE level = 2 AND layout_template_id = :layoutTemplateId  AND parent_id = :parent";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
            $statement->bindParam(':parent', $parent, PDO::PARAM_INT);
            $statement->execute();
            $headings =  $statement->fetchAll(PDO::FETCH_ASSOC);

            $updatequery = "UPDATE headings SET colspan = :colspan WHERE id = :parent";
            $update = $this->conn->prepare($updatequery);
            $update->bindParam(':colspan', $headings[0]['colspan'], PDO::PARAM_INT);
            $update->bindParam(':parent', $parent, PDO::PARAM_INT);
            $update->execute();
        }

        if ($level == 3) {
            $query = "SELECT sum(colspan) as colspan FROM headings WHERE level = 3 AND layout_template_id = :layoutTemplateId  AND parent_id = :parent";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
            $statement->bindParam(':parent', $parent, PDO::PARAM_INT);
            $statement->execute();
            $headings =  $statement->fetchAll(PDO::FETCH_ASSOC);
            $updatequery = "UPDATE headings SET colspan = :colspan WHERE id = :parent";

            $update = $this->conn->prepare($updatequery);
            $update->bindParam(':colspan', $headings[0]['colspan'], PDO::PARAM_INT);
            $update->bindParam(':parent', $parent, PDO::PARAM_INT);
            $update->execute();

            // update first level (supper parent)

            $select = "SELECT parent_id FROM headings WHERE level = 2 AND layout_template_id = :layoutTemplateId  AND id = :parent";
            $selectstatement = $this->conn->prepare($select);
            $selectstatement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
            $selectstatement->bindParam(':parent', $parent, PDO::PARAM_INT);
            $selectstatement->execute();
            $selectheadings =  $selectstatement->fetchAll(PDO::FETCH_ASSOC);
            $this->updateFirstLevel($layoutTemplateID, $selectheadings[0]['parent_id']);
        }
    }
    public function updateFirstLevel($layoutTemplateID, $parent)
    {
        $query = "SELECT sum(colspan) as colspan FROM headings WHERE level = 2 AND layout_template_id = :layoutTemplateId  AND parent_id = :parent";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
        $statement->bindParam(':parent', $parent, PDO::PARAM_INT);
        $statement->execute();
        $headings =  $statement->fetchAll(PDO::FETCH_ASSOC);

        $updatequery = "UPDATE headings SET colspan = :colspan WHERE id = :parent";
        $update = $this->conn->prepare($updatequery);
        $update->bindParam(':colspan', $headings[0]['colspan'], PDO::PARAM_INT);
        $update->bindParam(':parent', $parent, PDO::PARAM_INT);
        $update->execute();
    }

    public function loadLayouts()
    {
        try {
            $query = "SELECT id, layout_name FROM layouts";
            $statement = $this->conn->prepare($query);
            $statement->execute();
            $templates = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $templates;
        } catch (PDOException $e) {
            // Log or handle the exception
            return ['error' => $e->getMessage()];
        }
    }


    public function handleLoadTemplates()
    {
        try {
            $query = "SELECT id, template_name FROM layout_template";
            $statement = $this->conn->prepare($query);
            $statement->execute();
            $templates = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $templates;
        } catch (PDOException $e) {
            // Log or handle the exception
            return ['error' => $e->getMessage()];
        }
    }

    public function handleloadTitles($postData)
    {
        $layoutID = $postData['layout_id'];
        $layoutTemplateID = $postData['layout_template_id'];
        $tableHTML = $this->getTableHTML($layoutID, $layoutTemplateID);

        return ['status' => 'success', 'tableHTML' => $tableHTML];
    }

    // public function drawTable($layoutID, $layoutTemplateID)
    // {

    //     $query = "SELECT * FROM layouts WHERE id = :layoutId";
    //     $statement = $this->conn->prepare($query);
    //     $statement->bindParam(':layoutId', $layoutID, PDO::PARAM_INT);
    //     $statement->execute();
    //     $layout =  $statement->fetchAll(PDO::FETCH_ASSOC);

    //     $layoutTemplateID = $layout[0]['layout_template_id'];

    //     //load headings
    //     $query1 = "SELECT h.*, c.column_function 
    //     FROM headings h 
    //     JOIN column_functions c ON h.id = c.headings_id 
    //     WHERE h.parent_id = 0 
    //     AND h.layout_template_id = :layoutTemplateId 
    //     ORDER BY h.parent_id";


    //     $statement = $this->conn->prepare($query1);
    //     $statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    //     // $statement->execute();
    //     // $headings =  $statement->fetchAll(PDO::FETCH_ASSOC);

    //     if ($statement->execute()) {
    //         $headings = $statement->fetchAll(PDO::FETCH_ASSOC);
    //         // Process $headings data
    //     } else {
    //         $errorInfo = $statement->errorInfo();
    //         // Handle SQL error (e.g., log error, return error response, etc.)
    //         echo "SQL Error: " . $errorInfo[2];
    //     }


    //     $query2 = "SELECT h.*, c.column_function 
    //        FROM headings h 
    //        JOIN column_functions c ON h.id = c.headings_id 
    //        WHERE h.level = 2 
    //        AND h.layout_template_id = :layoutTemplateId 
    //        ORDER BY h.parent_id";

    //     $statement2 = $this->conn->prepare($query2);
    //     $statement2->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    //     // $statement2->execute();
    //     // $headings2 =  $statement2->fetchAll(PDO::FETCH_ASSOC);

    //     if ($statement2->execute()) {
    //         $headings2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
    //         // Process $headings data
    //     } else {
    //         $errorInfo = $statement2->errorInfo();
    //         // Handle SQL error (e.g., log error, return error response, etc.)
    //         echo "SQL Error: " . $errorInfo[2];
    //     }


    //     $query3 = "SELECT h.*, c.column_function 
    //     FROM headings h 
    //     JOIN column_functions c ON h.id = c.headings_id 
    //     WHERE h.level = 3 
    //     AND h.layout_template_id = :layoutTemplateId 
    //     ORDER BY h.parent_id";

    //     $statement3 = $this->conn->prepare($query3);
    //     $statement3->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
    //     // $statement3->execute();
    //     // $headings3 = $statement3->fetchAll(PDO::FETCH_ASSOC);

    //     if ($statement3->execute()) {
    //         $headings3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
    //         // Process $headings data
    //     } else {
    //         $errorInfo = $statement3->errorInfo();
    //         // Handle SQL error (e.g., log error, return error response, etc.)
    //         echo "SQL Error: " . $errorInfo[2];
    //     }

    //     if ($headings) {
    //         echo  '<thead>';

    //         echo '<tr>';
    //         foreach ($headings as $heading) {
    //             $colspan = 0;

    //             foreach ($headings2 as $value) {
    //                 if ($value['parent_id'] === $heading['id']) {
    //                     $colspan = $colspan + 1;
    //                 }
    //             }

    //             echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading['column_type'] != "DATA" ? $heading['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading['column_type'] != "DATA" ? "#ccc" : "") . '"   colspan="' . $heading['colspan'] . '"  column_function="' . ($heading['column_function'] ? $heading['column_function'] : 'null') . '" column_type="' . $heading['column_type'] . '">' . $heading['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading['id'] . '" data-text="' . $heading['title'] . '"></i><i  type="button"  data-bs-toggle="modal" data-bs-target="#titleModal" data-id="' . $heading['id'] . '" data-text="' . $heading['title'] . '" id="" class="fa-solid fa-caret-down add-sub-title-modal"></i></th>';
    //         }

    //         echo '</tr>';


    //         if ($headings2) {
    //             echo '<tr>';
    //             foreach ($headings2 as $heading2) {
    //                 $colspan = 1;
    //                 foreach ($headings3 as $value) {
    //                     if ($value['parent_id'] === $heading2['id']) {
    //                         $colspan = $colspan + 1;
    //                     }
    //                 }
    //                 echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading['column_type'] != "DATA" ? $heading['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading['column_type'] != "DATA" ? "#ccc" : "") . '"  colspan="' . $heading2['colspan'] . '"  column_function="' . ($heading2['column_function'] ? $heading2['column_function'] : 'null') . '" column_type="' . $heading2['column_type'] . '">' . $heading2['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading2['id'] . '"  data-text="' . $heading['title'] . '"></i><i  type="button"  data-bs-toggle="modal" data-bs-target="#subtitleModal" data-id="' . $heading2['id'] . '" data-text="' . $heading2['title'] . '" id="" class="fa-solid fa-caret-down add-second-sub-title-modal"></i></th>';
    //             }
    //             echo '</tr>';
    //         }


    //         if ($headings3) {
    //             echo '<tr>';
    //             foreach ($headings3 as $heading3) {
    //                 echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading['column_type'] != "DATA" ? $heading['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading['column_type'] != "DATA" ? "#ccc" : "") . '"   column_function="' . ($heading3['column_function'] ? $heading3['column_function'] : 'null') . '" column_type="' . $heading3['column_type'] . '">' . $heading3['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading3['id'] . '"  data-text="' . $heading['title'] . '"></i></th>';
    //             }
    //             echo '</tr>';
    //         }
    //     }
    // }
    public function drawTable($layoutID, $layoutTemplateID)
    {
        // Fetch layout information
        $queryLayout = "SELECT * FROM layouts WHERE id = :layoutId";
        $statementLayout = $this->conn->prepare($queryLayout);
        $statementLayout->bindParam(':layoutId', $layoutID, PDO::PARAM_INT);
        $statementLayout->execute();
        $layout =  $statementLayout->fetch(PDO::FETCH_ASSOC);

        if (!$layout) {
            echo "Layout not found.";
            return;
        }

        $layoutTemplateID = $layout['layout_template_id'];

        $queryTemplate = "SELECT levels FROM layout_template WHERE id = :templateID";
        $statementTemplate = $this->conn->prepare($queryTemplate);
        $statementTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
        $statementTemplate->execute();
        $template =  $statementTemplate->fetch(PDO::FETCH_ASSOC);
        // check header column type is functions and restrict to add sub heading
        $funtionCol = $template ? $template['levels'] : 1;


        // Query for level 1 headings
        $query1 = "SELECT h.*, c.column_function 
               FROM headings h 
               LEFT JOIN column_functions c ON h.id = c.headings_id 
               WHERE h.parent_id = 0 
               AND h.layout_template_id = :layoutTemplateId 
               ORDER BY h.parent_id";

        $statement1 = $this->conn->prepare($query1);
        $statement1->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);

        if ($statement1->execute()) {
            $headings = $statement1->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errorInfo = $statement1->errorInfo();
            echo "SQL Error 1: " . $errorInfo[2];
            return;
        }

        // Query for level 2 headings
        $query2 = "SELECT h.*, c.column_function 
               FROM headings h 
               LEFT JOIN column_functions c ON h.id = c.headings_id 
               WHERE h.level = 2 
               AND h.layout_template_id = :layoutTemplateId 
               ORDER BY h.parent_id";

        $statement2 = $this->conn->prepare($query2);
        $statement2->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);

        if ($statement2->execute()) {
            $headings2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errorInfo = $statement2->errorInfo();
            echo "SQL Error 2: " . $errorInfo[2];
            return;
        }

        // Query for level 3 headings
        $query3 = "SELECT h.*, c.column_function 
               FROM headings h 
               LEFT JOIN column_functions c ON h.id = c.headings_id 
               WHERE h.level = 3 
               AND h.layout_template_id = :layoutTemplateId 
               ORDER BY h.parent_id";

        $statement3 = $this->conn->prepare($query3);
        $statement3->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);

        if ($statement3->execute()) {
            $headings3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errorInfo = $statement3->errorInfo();
            echo "SQL Error 3: " . $errorInfo[2];
            return;
        }

        // Display table headings and data
        if ($headings) {
            // HTML code to display table headings
            $hasFuntionCol = $funtionCol == 2 ? 1 : 0;
            echo '<thead>';
            echo '<tr>';
            foreach ($headings as $heading) {
                // Display each heading
                if ($funtionCol > 1) {
                    echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading['column_type'] != "DATA" ? $heading['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading['column_type'] != "DATA" ? "#ccc" : "") . '"   colspan="' . $heading['colspan'] . '"  column_function="' . ($heading['column_function'] ? $heading['column_function'] : 'null') . '" column_type="' . $heading['column_type'] . '">' . $heading['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading['id'] . '" data-text="' . $heading['title'] . '"></i><i  type="button"  data-bs-toggle="modal" data-bs-target="#titleModal" data-has-function="' . $hasFuntionCol . '" data-id="' . $heading['id'] . '" data-text="' . $heading['title'] . '" id="" class="fa-solid fa-caret-down add-sub-title-modal"></i></th>';
                } else {
                    echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading['column_type'] != "DATA" ? $heading['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading['column_type'] != "DATA" ? "#ccc" : "") . '"   colspan="' . $heading['colspan'] . '"  column_function="' . ($heading['column_function'] ? $heading['column_function'] : 'null') . '" column_type="' . $heading['column_type'] . '">' . $heading['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading['id'] . '" data-text="' . $heading['title'] . '"></i></th>';
                }
            }
            echo '</tr>';
            echo '</thead>';

            // HTML code to display table data
            echo '<tbody>';

            // Loop through level 2 headings
            if ($headings2) {
                $hasFuntionCol = $funtionCol == 3 ? 1 : 0;
                echo '<tr>';
                foreach ($headings2 as $heading2) {
                    // Display each level 2 heading
                    if ($funtionCol > 2) {
                        echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading2['column_type'] != "DATA" ? $heading2['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading2['column_type'] != "DATA" ? "#ccc" : "") . '"  colspan="' . $heading2['colspan'] . '"  column_function="' . ($heading2['column_function'] ? $heading2['column_function'] : 'null') . '" column_type="' . $heading2['column_type'] . '">' . $heading2['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading2['id'] . '"  data-text="' . $heading2['title'] . '"></i><i  type="button"  data-bs-toggle="modal" data-bs-target="#subtitleModal" data-has-function="' . $hasFuntionCol . '" data-id="' . $heading2['id'] . '" data-text="' . $heading2['title'] . '" id="" class="fa-solid fa-caret-down add-second-sub-title-modal"></i></th>';
                    } else {
                        echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading2['column_type'] != "DATA" ? $heading2['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading2['column_type'] != "DATA" ? "#ccc" : "") . '"  colspan="' . $heading2['colspan'] . '"  column_function="' . ($heading2['column_function'] ? $heading2['column_function'] : 'null') . '" column_type="' . $heading2['column_type'] . '">' . $heading2['title'] . '<i class="fa-solid fa-gear heading-edit"  data-id="' . $heading2['id'] . '"  data-text="' . $heading2['title'] . '"></i></th>';
                    }
                    // Display data for each level 2 heading
                    // For example:
                    // echo '<td>' . $data_for_heading2 . '</td>';


                }
                echo '</tr>';
            }

            // Loop through level 3 headings
            if ($headings3) {
                $hasFuntionCol = 1;
                echo '<tr>';
                foreach ($headings3 as $heading3) {

                    // Display each level 3 heading

                    echo '<th tabindex="0" data-toggle="tooltip" title="' . ($heading3['column_type'] != "DATA" ? $heading3['column_function'] : "") . '" style="border:1px solid #000;cursor:pointer; background-color: ' . ($heading3['column_type'] != "DATA" ? "#ccc" : "") . '"   column_function="' . ($heading3['column_function'] ? $heading3['column_function'] : 'null') . '" column_type="' . $heading3['column_type'] . '">' . $heading3['title'] . '<i class="fa-solid fa-gear heading-edit"  data-has-function="' . $hasFuntionCol . '" data-id="' . $heading3['id'] . '"  data-text="' . $heading3['title'] . '"></i></th>';

                    // Display data for each level 3 heading
                    // For example:
                    // echo '<td>' . $data_for_heading3 . '</td>';


                }
                echo '</tr>';
            }

            echo '</tbody>';
        }
    }


    // Helper method to get headings from the database
    private function getHeadings($layoutTemplateID)
    {
        $query = "SELECT * FROM headings WHERE layout_template_id = :layoutTemplateId";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getSubHeadingsCount($heddingId)
    {
        $query = "SELECT COUNT(id) AS subheadings FROM headings WHERE headings_id = :heddingId AND level = ";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':heddingId', $heddingId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result['subheadings'];
    }


    private function getSubHeadings($heddingId)
    {
        $query = "SELECT * FROM sub_headings WHERE headings_id = :heddingId";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':heddingId', $heddingId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    private function getTableHTML($layoutID, $layoutTemplateID)
    {
        ob_start(); // Start output buffering to capture HTML
        $this->drawTable($layoutID, $layoutTemplateID); // Call your drawTable method to generate the HTML
        $tableHTML = ob_get_clean(); // Get the contents of the buffer and clean it
        return $tableHTML;
    }

    private function getNameById($id, $table, $columnName)
    {
        $query = "SELECT $columnName FROM $table WHERE id = :Id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':Id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handlelinkTemplate($postData)
    {
        $layoutID = $postData['layout_id'];
        $templateID = $postData['template_id'];
        $query = "UPDATE layouts SET layout_template_id = :template_id WHERE id = :layout_id";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layout_id', $layoutID, PDO::PARAM_INT);
        $statement->bindParam(':template_id', $templateID, PDO::PARAM_INT);

        $layout = $this->getNameById($layoutID, 'layouts', 'layout_name');
        $template = $this->getNameById($templateID, 'layout_template', 'template_name');

        if ($statement->execute()) {
            $layoutName = $layout[0]['layout_name'];
            $templateName = $template[0]['template_name'];
            return ['status' => 'success', 'message' => $layoutName . ' Successfully reassigned with ' . $templateName];
        } else {
            return ['status' => 'error', 'message' => 'Error linking template to layout.'];
        }
    }



    public function handleaddNewLayout($postData)
    {
        $layoutName = $postData['layout_name'];
        $templateID = $postData['template'];
        $query = "INSERT INTO layouts (layout_name,layout_template_id) VALUES (:layout_name,:layout_template_id)";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layout_name', $layoutName);
        $statement->bindParam(':layout_template_id', $templateID);

        if ($statement->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Error inserting Layout Name.'];
        }
    }


    public function handleaddNewTemplate($postData)
    {
        $templateName = $postData['template_name'];
        $levels = $postData['levels'];
        $query = "INSERT INTO layout_template (template_name,levels) VALUES (:template_name,:levels)";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':template_name', $templateName);
        $statement->bindParam(':levels',  $levels);

        if ($statement->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Error inserting Layout Name.'];
        }
    }



    public function handleaddComponent($postData)
    {
        $component_text = $postData['component_text'];
        $reference_column = $postData['heading_id'];
        $template_id = $postData['template_id'];
        $layout_id = $postData['layout_id'];

        $query = "INSERT INTO uncertainty_budget_tempplate (layout_id,template_id,component,reference_column) VALUES (:layout_id,:template_id,:component,:reference_column)";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':component', $component_text);
        $statement->bindParam(':layout_id', $layout_id);
        $statement->bindParam(':template_id', $template_id);
        $statement->bindParam(':reference_column', $reference_column);

        if ($statement->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Error inserting Layout Name.'];
        }
    }




    public function addNewTemplate($templateName)
    {
        $query = "INSERT INTO layout_template (template_name) VALUES (:template_name)";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':template_name', $templateName);

        if ($statement->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Error inserting Template Name.'];
        }
    }
    public function removeHeddingValues($templateID)
    {
        $query = "DELETE FROM `value` WHERE layout_template_id = :layout_template_id";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layout_template_id', $templateID, PDO::PARAM_INT);

        if ($statement->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Error deleting values.'];
        }
    }


    public function handleGetbasicdata($postData)
    {
        // Check if $_POST array has values
        if (empty($postData['parameter']) || empty($postData['eq_id']) || empty($postData['sensor_id']) || empty($postData['cal_date']) || empty($postData['min']) || empty($postData['max'])) {
            return ['status' => 'error', 'message' => 'Form data is incomplete.'];
        }

        try {
            $parameter = $postData['parameter'];
            $equipmentId = $postData['eq_id'];
            $sensorId = $postData['sensor_id'];
            $calDate = $postData['cal_date'];
            $min = $postData['min'];
            $max = $postData['max'];

            // Convert the date format from YYYY-MM-DD to DD/MM/YYYY
            $calDate = date("d/m/Y", strtotime($calDate));

            // Adjusted SQL query with corrected date format
            $query = "SELECT * FROM si_ref_eq_info WHERE eq_id = :eq_id AND sensor_id = :sensor_id AND STR_TO_DATE(cal_date, '%d/%m/%Y') BETWEEN :range_min AND :range_max";

            $statement = $this->conn->prepare($query);
            $statement->bindParam(':eq_id', $equipmentId, PDO::PARAM_STR);
            $statement->bindParam(':sensor_id', $sensorId, PDO::PARAM_STR);
            $statement->bindParam(':range_min', $minDate, PDO::PARAM_STR);
            $statement->bindParam(':range_max', $maxDate, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $result];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function getMultipleValueArrays($postData)
    {
        try {
            $function_column_id = $postData['heading_id']; // Corrected variable name

            $valuesResult = []; // Initialize the final result array

            // Find reference columns
            $query = "SELECT reference_column_id FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':function_column_id', $function_column_id, PDO::PARAM_INT);
            $statement->execute();

            // Fetch all the reference_column_id values
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Loop through each reference_column_id and fetch the corresponding values
            foreach ($result as $row) {
                $reference_column_id = $row['reference_column_id'];

                // Fetch values from the values table for the current reference_column_id
                $valueQuery = "SELECT id,row_id, `value`, value_collection FROM `value` WHERE headings_id = :reference_column_id";
                $valueStatement = $this->conn->prepare($valueQuery);
                $valueStatement->bindParam(':reference_column_id', $reference_column_id, PDO::PARAM_INT);
                $valueStatement->execute();
                $values = $valueStatement->fetchAll(PDO::FETCH_ASSOC);
                $valuesResult[] = $values;
            }

            return $valuesResult;
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return ['error' => 'Database error: ' . $e->getMessage()];
        } catch (Exception $e) {
            // Handle other exceptions
            return ['error' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    // public function getMultipleValueArrays($postData)
    // {
    //     $function_column_id = $postData['heding_id'];
    //     $valuesResult = []; // Initialize the final result array

    //     // Find reference columns
    //     $query = "SELECT reference_column_id FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
    //     $statement = $this->conn->prepare($query);
    //     $statement->bindParam(':function_column_id', $function_column_id, PDO::PARAM_INT);
    //     $statement->execute();

    //     // Fetch all the reference_column_id values
    //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    //     // Loop through each reference_column_id and fetch the corresponding values
    //     foreach ($result as $row) {
    //         $reference_column_id = $row['reference_column_id'];

    //         // Fetch values from the values table for the current reference_column_id
    //         $valueQuery = "SELECT value FROM values WHERE headings_id = :reference_column_id";
    //         $valueStatement = $this->conn->prepare($valueQuery);
    //         $valueStatement->bindParam(':reference_column_id', $reference_column_id, PDO::PARAM_INT);
    //         $valueStatement->execute();
    //         $values = $valueStatement->fetchAll(PDO::FETCH_COLUMN);

    //         // Check if values were fetched
    //         if (!empty($values)) {
    //             // Push the fetched values into $valuesResult array
    //             $valuesResult[] = ['id' => $reference_column_id, 'values' => $values];
    //         }
    //     }

    //     return $valuesResult;
    // }




    public function updateComponentData($template_id, $heading_id, $rowId, $magnitude)
    {
        // Assuming $templateID, $referenceColumn, and $headingsId are defined earlier in your code
        $query_ubt = "SELECT id FROM uncertainty_budget_tempplate WHERE template_id = :templateId AND reference_column = :referenceColumn";
        $statement_ubt = $this->conn->prepare($query_ubt);
        $statement_ubt->bindParam(':templateId', $template_id, PDO::PARAM_INT);
        $statement_ubt->bindParam(':referenceColumn', $heading_id, PDO::PARAM_INT);
        $statement_ubt->execute();
        $results_ubt = $statement_ubt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results_ubt) > 0) {

            // Assuming $rowId, $uncertaintyBudgetTemplateId, $headingsId, and $magnitude are defined earlier in your code
            foreach ($results_ubt as $ubt) {
                // Assuming $rowId, $headingsId, and $values[$i] are defined earlier in your code
                $query_cd = "UPDATE component_data SET magnitude = :magnitude WHERE row_id = :rowId AND uncertainty_budget_tempplate_id = :uncertaintyBudgetTemplateId AND headings_id = :headingsId";
                $statement_cd = $this->conn->prepare($query_cd);
                $statement_cd->bindParam(':rowId', $rowId, PDO::PARAM_INT);
                $statement_cd->bindParam(':uncertaintyBudgetTemplateId', $ubt['id'], PDO::PARAM_INT);
                $statement_cd->bindParam(':headingsId', $heading_id, PDO::PARAM_INT);
                $statement_cd->bindParam(':magnitude', $magnitude, PDO::PARAM_STR);
                $result_cd = $statement_cd->execute();

                if ($result_cd) {
                    echo "Values inserted successfully into component_data table.";
                } else {
                    echo "Error inserting values into component_data table.";
                }
            }
        } else {
            // Record does not exist
            echo "Record does not exist in uncertainty_budget_template table.";
        }
    }


    //COLUMN FUNCTIONS
    public function handleCorrection($postData)
    {
        $heading_id = $postData['heading_id'];
        $template_id = $postData['template_id'];

        $result = $this->getMultipleValueArrays($postData);
        $subtractionResults = [];

        // Check if the result array has at least two sets of arrays
        if (count($result) >= 2 && is_array($result[0]) && is_array($result[1])) {
            $numRows = min(count($result[0]), count($result[1])); // Get the minimum number of rows in both arrays

            for ($i = 0; $i < $numRows; $i++) {
                var_dump($result[0][$i]['value']);
                var_dump($result[1][$i]['value']);


                $subtractionResult = $result[0][$i]['value'] - $result[1][$i]['value'];
                $subtractionResults[] = $subtractionResult;

                // Build the value_collection string
                $valueCollection = $result[0][$i]['value'] . ', ' . $result[1][$i]['value'];

                try {
                    // Update the value table
                    $valueQuery = "UPDATE value SET `value`= :valueData, `value_collection` = :value_collection WHERE row_id = :rowid AND headings_id = :heading_id AND layout_template_id = :template_id";
                    $valueStatement = $this->conn->prepare($valueQuery);
                    $valueStatement->bindParam(':heading_id', $heading_id, PDO::PARAM_INT);
                    $valueStatement->bindParam(':template_id', $template_id, PDO::PARAM_INT);
                    $valueStatement->bindParam(':valueData', $subtractionResult, PDO::PARAM_STR);
                    $valueStatement->bindParam(':value_collection', $valueCollection, PDO::PARAM_STR);
                    $valueStatement->bindParam(':rowid', $result[0][$i]['row_id'], PDO::PARAM_INT);
                    $valueStatement->execute();

                    $this->updateComponentData($template_id, $heading_id, $result[0][$i]['row_id'], $subtractionResult);
                } catch (PDOException $e) {
                    // Handle PDO exceptions
                    // return ['error' => 'Database error: ' . $e->getMessage()];
                }
            }
        }

        return $subtractionResults;
    }


    public function handleStdev($postData)
    {
        $result = $this->getMultipleValueArrays($postData);
        $valueCollections = []; // Initialize an array to store 'value_collection' values
        $heading_id = $postData['heading_id'];
        $template_id = $postData['template_id'];

        foreach ($result as $rows) {
            foreach ($rows as $row) {
                $valueCollections[] = $row;
            }
        }
        $standardDeviations = [];
        foreach ($valueCollections  as $collection) {
            $dataArray = explode(',', $collection['value_collection']);
            $scores = array_map('intval', $dataArray);
            $count = count($scores);

            if ($count > 1) { // Check if there are at least two scores
                // Calculate mean
                $mean = array_sum($scores) / $count;

                // Calculate squared differences from the mean
                $squaredDifferences = array_map(function ($score) use ($mean) {
                    return pow($score - $mean, 2);
                }, $scores);

                // Calculate mean of squared differences
                $meanOfSquaredDifferences = array_sum($squaredDifferences) / $count;

                // Calculate standard deviation
                $standardDeviation = sqrt($meanOfSquaredDifferences);

                // Store standard deviation for this row
                $standardDeviations[] = $standardDeviation;

                try {
                    // Update the value table
                    $valueQuery = "UPDATE value SET `value`= :valueData, `value_collection` = :value_collection WHERE row_id = :rowid AND headings_id = :heading_id AND layout_template_id = :template_id";
                    $valueStatement = $this->conn->prepare($valueQuery);
                    $valueStatement->bindParam(':heading_id', $heading_id, PDO::PARAM_INT);
                    $valueStatement->bindParam(':template_id', $template_id, PDO::PARAM_INT);
                    $valueStatement->bindParam(':valueData', $standardDeviation, PDO::PARAM_STR);
                    $valueStatement->bindParam(':value_collection', $collection['value_collection'], PDO::PARAM_STR);
                    $valueStatement->bindParam(':rowid', $collection['row_id'], PDO::PARAM_INT);
                    $valueStatement->execute();

                    // update component_data table
                    $this->updateComponentData($template_id, $heading_id, $collection['row_id'], $standardDeviation);
                } catch (PDOException $e) {
                    // Handle PDO exceptions
                    // return ['error' => 'Database error: ' . $e->getMessage()];
                }
            } else {
                // Handle case where there are not enough scores for standard deviation
                // For example, log an error or skip this row
            }
        }
        return $standardDeviations;
    }

    public function handleTuc($postData)
    {
    }

    // test count
    public function handleTc($postData)
    {
        //not clear
    }


    //Average
    public function handleAv($postData)
    {
        $numbers = $this->getMultipleValueArrays($postData);
        // Check if the input is a non-empty array
        if (!is_array($numbers) || empty($numbers)) {
            return false; // Return false if input is invalid or empty
        }

        // Calculate the sum of the numbers in the array
        $sum = array_sum($numbers);

        // Calculate the average by dividing the sum by the number of elements
        $average = $sum / count($numbers);

        return $average;
    }


    // REF STDEV
    public function handleRs($postData)
    {
        $result = $this->getMultipleValueArrays($postData);
        $valueCollections = []; // Initialize an array to store 'value_collection' values
        $heading_id = $postData['heading_id'];
        $template_id = $postData['template_id'];

        foreach ($result as $rows) {
            foreach ($rows as $row) {
                $valueCollections[] = $row;
            }
        }
        $standardDeviations = [];
        foreach ($valueCollections  as $collection) {
            $dataArray = explode(',', $collection['value_collection']);
            $scores = array_map('intval', $dataArray);
            $count = count($scores);

            if ($count > 1) { // Check if there are at least two scores
                // Calculate mean
                $mean = array_sum($scores) / $count;

                // Calculate squared differences from the mean
                $squaredDifferences = array_map(function ($score) use ($mean) {
                    return pow($score - $mean, 2);
                }, $scores);

                // Calculate mean of squared differences
                $meanOfSquaredDifferences = array_sum($squaredDifferences) / $count;

                // Calculate standard deviation
                $standardDeviation = sqrt($meanOfSquaredDifferences);

                // Store standard deviation for this row
                $standardDeviations[] = $standardDeviation;

                try {
                    // Update the value table
                    $valueQuery = "UPDATE value SET `value`= :valueData, `value_collection` = :value_collection WHERE row_id = :rowid AND headings_id = :heading_id AND layout_template_id = :template_id";
                    $valueStatement = $this->conn->prepare($valueQuery);
                    $valueStatement->bindParam(':heading_id', $heading_id, PDO::PARAM_INT);
                    $valueStatement->bindParam(':template_id', $template_id, PDO::PARAM_INT);
                    $valueStatement->bindParam(':valueData', $standardDeviation, PDO::PARAM_STR);
                    $valueStatement->bindParam(':value_collection', $collection['value_collection'], PDO::PARAM_STR);
                    $valueStatement->bindParam(':rowid', $collection['row_id'], PDO::PARAM_INT);
                    $valueStatement->execute();

                    // update component_data table
                    $this->updateComponentData($template_id, $heading_id, $collection['row_id'], $standardDeviation);
                } catch (PDOException $e) {
                    // Handle PDO exceptions
                    // return ['error' => 'Database error: ' . $e->getMessage()];
                }
            } else {
                // Handle case where there are not enough scores for standard deviation
                // For example, log an error or skip this row
            }
        }
        return $standardDeviations;
    }



    public function handleprocess_ub_table($postData)
    {
        try {
            $templateID = $postData['template_id'];
            $layoutID = $postData['layout_id'];
            $row_id = $postData['row_id'];

            // Prepare the SQL query to select the id and reference column from uncertainty_budget_template table
            $query = "SELECT id, reference_column FROM uncertainty_budget_tempplate WHERE layout_id = :layoutID AND template_id = :templateID";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':layoutID', $layoutID, PDO::PARAM_INT);
            $statement->bindParam(':templateID', $templateID, PDO::PARAM_INT);

            // Execute the query
            $statement->execute();

            // Fetch the result as an associative array
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // Check if the result is not empty
            if ($result) {
                $uncertainty_budget_tempplateID = $result['id'];
                $reference_column_id = $result['reference_column'];

                // Check if a record already exists for the given uncertainty_budget_template_id and row_id
                $checkQuery = "SELECT COUNT(*) as count FROM component_data WHERE uncertainty_budget_tempplate_id = :uncertainty_budget_template_id AND row_id = :row_id AND headings_id = :headingsID";
                $checkStatement = $this->conn->prepare($checkQuery);
                $checkStatement->bindParam(':uncertainty_budget_template_id', $uncertainty_budget_tempplateID, PDO::PARAM_INT);
                $checkStatement->bindParam(':row_id', $row_id, PDO::PARAM_INT);
                $checkStatement->bindParam(':headingsID', $reference_column_id, PDO::PARAM_INT);
                $checkStatement->execute();
                $countResult = $checkStatement->fetch(PDO::FETCH_ASSOC);

                if ($countResult['count'] == 0) {
                    // Record does not exist, proceed with the insert
                    // Find magnitude
                    $queryMagnitude = "SELECT `value` FROM `value` WHERE row_id = :rowID AND headings_id = :headingsID AND layout_template_id = :layoutTemplateId";
                    $statementMagnitude = $this->conn->prepare($queryMagnitude);
                    $statementMagnitude->bindParam(':rowID', $row_id, PDO::PARAM_INT);
                    $statementMagnitude->bindParam(':headingsID', $reference_column_id, PDO::PARAM_INT);
                    $statementMagnitude->bindParam(':layoutTemplateId', $templateID, PDO::PARAM_INT);

                    // Execute the query
                    $statementMagnitude->execute();

                    // Fetch the magnitude result as an associative array
                    $resultMagnitude = $statementMagnitude->fetch(PDO::FETCH_ASSOC);

                    if ($resultMagnitude) {
                        $magnitude = $resultMagnitude['value'];

                        // Insert into component_data table
                        $insertQuery = "INSERT INTO component_data (uncertainty_budget_tempplate_id, row_id, magnitude, headings_id) VALUES (:uncertainty_budget_template_id, :row_id, :magnitude, :headingsID)";
                        $insertStatement = $this->conn->prepare($insertQuery);
                        $insertStatement->bindParam(':uncertainty_budget_template_id', $uncertainty_budget_tempplateID, PDO::PARAM_INT);
                        $insertStatement->bindParam(':row_id', $row_id, PDO::PARAM_INT);
                        $insertStatement->bindParam(':headingsID', $reference_column_id, PDO::PARAM_INT);
                        $insertStatement->bindParam(':magnitude', $magnitude, PDO::PARAM_STR);

                        // Execute the insert query
                        $insertStatement->execute();

                        $this->update_values_as_processed($templateID, $row_id, $reference_column_id);

                        // Optionally, you can return a success message or status here
                        return ['status' => 'success'];
                    }
                } else {
                    // Record already exists, proceed with the update
                    // Fetch the existing magnitude value
                    $fetchMagnitudeQuery = "SELECT magnitude FROM component_data WHERE uncertainty_budget_tempplate_id = :uncertainty_budget_template_id AND row_id = :row_id AND headings_id = :headingsID";
                    $fetchMagnitudeStatement = $this->conn->prepare($fetchMagnitudeQuery);
                    $fetchMagnitudeStatement->bindParam(':uncertainty_budget_template_id', $uncertainty_budget_tempplateID, PDO::PARAM_INT);
                    $fetchMagnitudeStatement->bindParam(':row_id', $row_id, PDO::PARAM_INT);
                    $fetchMagnitudeStatement->bindParam(':headingsID', $reference_column_id, PDO::PARAM_INT);
                    $fetchMagnitudeStatement->execute();
                    $existingMagnitude = $fetchMagnitudeStatement->fetchColumn();

                    // Update the magnitude in component_data table
                    $updateQuery = "UPDATE component_data SET magnitude = :magnitude WHERE uncertainty_budget_tempplate_id = :uncertainty_budget_template_id AND row_id = :row_id AND headings_id = :headingsID";
                    $updateStatement = $this->conn->prepare($updateQuery);
                    $updateStatement->bindParam(':uncertainty_budget_template_id', $uncertainty_budget_tempplateID, PDO::PARAM_INT);
                    $updateStatement->bindParam(':row_id', $row_id, PDO::PARAM_INT);
                    $updateStatement->bindParam(':headingsID', $reference_column_id, PDO::PARAM_INT);
                    $updateStatement->bindParam(':magnitude', $existingMagnitude, PDO::PARAM_STR); // Use the fetched magnitude value

                    // Execute the update query
                    $updateStatement->execute();

                    // Optionally, you can return a success message or status here
                    return ['status' => 'success'];
                }
            }
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return ['error' => 'Database error: ' . $e->getMessage()];
        } catch (Exception $e) {
            // Handle other exceptions
            return ['error' => 'An error occurred: ' . $e->getMessage()];
        }

        // If no data was inserted or updated, return an error status
        return ['status' => 'error', 'message' => 'No data inserted or updated.'];
    }

    public function update_values_as_processed($template, $row_id, $heddings_id)
    {
        $processed = 1;
        $updateQuery = "UPDATE `value` SET row_processed = :processed WHERE row_id = :row_id AND headings_id = :headings_id AND layout_template_id = :template_id";
        $updateStatement = $this->conn->prepare($updateQuery);

        $updateStatement->bindParam(':row_id', $row_id, PDO::PARAM_INT);
        $updateStatement->bindParam(':headings_id', $heddings_id, PDO::PARAM_INT);
        $updateStatement->bindParam(':template_id', $template, PDO::PARAM_INT);
        $updateStatement->bindParam(':processed', $processed, PDO::PARAM_INT);

        // Execute the update query
        $updateStatement->execute();
    }

    public function handleget_table2_template($postData)
    {
        // Initialize an empty string to hold the HTML table
        $template_id = $postData['template_id'];
        $value_id = $postData['value_id'];
        $row_id = $postData['row_id'];
        $headings_id = $postData['headings_id'];
        $layout_id = $postData['layout_id'];
        $htmlTable = '<div id="data-div" template_id="' . $template_id . '" value_id="' . $value_id . '" row_id="' . $row_id . '" headings_id="' . $headings_id . '" layout_id="' . $layout_id . '"> </div>';

        $htmlTable .= '<table>';

        // Add the table headers to the HTML string
        $htmlTable .= '<thead>';
        $htmlTable .= '<tr>';
        $htmlTable .= '<th colspan="7" class="text-center">Uncertainty Budget</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '<tr>';
        $htmlTable .= '<th width="25%">Component</th>';
        $htmlTable .= '<th width="10%">Magnitude</th>';
        $htmlTable .= '<th width="25%">Distribution</th>';
        $htmlTable .= '<th width="10%">Divisor</th>';
        $htmlTable .= '<th width="10%">Sensitivity</th>';
        $htmlTable .= '<th width="10%">Std uncert</th>';
        $htmlTable .= '<th width="10%">DOF</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead>';
        $htmlTable .= '<tbody>';

        try {
            if (isset($postData['layout_id'], $postData['template_id'])) {
                $query = "SELECT *, cd.id as cid  FROM component_data cd JOIN uncertainty_budget_tempplate as ubt on cd.uncertainty_budget_tempplate_id = ubt.id WHERE ubt.layout_id = :layout_id AND ubt.template_id = :template_id AND cd.row_id = :row_id";
                $statement = $this->conn->prepare($query);
                $statement->bindValue(':layout_id', $postData['layout_id'], PDO::PARAM_INT);
                $statement->bindValue(':template_id', $postData['template_id'], PDO::PARAM_INT);
                $statement->bindValue(':row_id', $postData['row_id'], PDO::PARAM_INT);
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $result) {
                    $htmlTable .= '<tr>';
                    $htmlTable .= '<td>' . $result['component'] . '</td>';
                    $htmlTable .= '<td>' . $result['magnitude'] . '</td>';
                    $htmlTable .= '<td><input class="input-data" type="hidden" value="' . $result['cid'] . '" name="dataids[]"/><input class="input-data" name="distribution[]" value="' . $result['distribution'] . '" type="text" id="distribution" data-template-id="' . $result['uncertainty_budget_tempplate_id'] . '" data-row-id="' . $result['row_id'] . '" data-headings-id="' . $result['headings_id'] . '" data-id="' . $result['id'] . '"/></td>';
                    $htmlTable .= '<td><input class="input-data" name="divisor[]" value="' . $result['divisor'] . '" type="text" id="divisor" data-template-id="' . $result['uncertainty_budget_tempplate_id'] . '" data-row-id="' . $result['row_id'] . '" data-headings-id="' . $result['headings_id'] . '" data-id="' . $result['id'] . '"/></td>';
                    $htmlTable .= '<td><input class="input-data" name="sensitivity[]" value="' . $result['sensitivity'] . '" type="text" id="sensitivity" data-template-id="' . $result['uncertainty_budget_tempplate_id'] . '" data-row-id="' . $result['row_id'] . '" data-headings-id="' . $result['headings_id'] . '" data-id="' . $result['id'] . '"/></td>';
                    $htmlTable .= '<td data-template-id="' . $result['uncertainty_budget_tempplate_id'] . '" data-row-id="' . $result['row_id'] . '" data-headings-id="' . $result['headings_id'] . '" >' . $result['std_uncert'] . '</td>';
                    $htmlTable .= '<td data-template-id="' . $result['uncertainty_budget_tempplate_id'] . '" data-row-id="' . $result['row_id'] . '" data-headings-id="' . $result['headings_id'] . '" ></td>';
                    $htmlTable .= '</tr>';
                }
            }
        } catch (PDOException $e) {
            $htmlTable .= "Error fetching magnitude: " . $e->getMessage();
        }

        $htmlTable .= '</tbody>';
        $htmlTable .= '</table>';

        $htmlTable2 = '<table style="margin-top: 60px; width: 50% !important; margin-left: 25%">';

        // Add the table headers to the HTML string
        $htmlTable2 .= '<thead>';
        $htmlTable2 .= '<tr>';
        $htmlTable2 .= '<th colspan="2" class="text-center">Expanded uncertainty reporting</th>';
        $htmlTable2 .= '</tr>';
        $htmlTable2 .= '</thead>';
        $htmlTable2 .= '<tbody>';

        try {
            if (isset($postData['row_id'], $postData['template_id'])) {
                $queryEur = "SELECT * FROM expanded_uncertainty_reporting WHERE uncertainty_budget_tempplate_id = :template_id AND row_id = :row_id";
                $statementEur = $this->conn->prepare($queryEur);
                $statementEur->bindValue(':template_id', $postData['template_id'], PDO::PARAM_INT);
                $statementEur->bindValue(':row_id', $postData['row_id'], PDO::PARAM_INT);
                $statementEur->execute();
                $resultEur = $statementEur->fetch(PDO::FETCH_ASSOC);

                if ($resultEur) {
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td>Combined Standard Uncertainty</td>';
                    $htmlTable2 .= '<td>' . $resultEur['combined_standard_uncertainty'] . '</td>';
                    $htmlTable2 .= '</tr>';
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td>Effective Degree of Freedom</td>';
                    $htmlTable2 .= '<td>' . $resultEur['effective_degree_of_freedom'] . '</td>';
                    $htmlTable2 .= '</tr>';
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td>Coverage factor (K) at 95% C.L.</td>';
                    $htmlTable2 .= '<td>' . $resultEur['coverage_factor'] . '</td>';
                    $htmlTable2 .= '</tr>';
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td></td>';
                    $htmlTable2 .= '<td></td>';
                    $htmlTable2 .= '</tr>';
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td>Expanded Uncertainty</td>';
                    $htmlTable2 .= '<td>' . $resultEur['expanded_uncertainty'] . '</td>';
                    $htmlTable2 .= '</tr>';
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td>CMC Claimed</td>';
                    $htmlTable2 .= '<td>' . $resultEur['cmc_claimed'] . '</td>';
                    $htmlTable2 .= '</tr>';
                    $htmlTable2 .= '<tr>';
                    $htmlTable2 .= '<td>Expanded Uncertainty - to report</td>';
                    $htmlTable2 .= '<td>' . $resultEur['eu_to_report'] . '</td>';
                    $htmlTable2 .= '</tr>';
                }
            }
        } catch (PDOException $e) {
            $htmlTable2 .= "Error fetching magnitude: " . $e->getMessage();
        }


        $htmlTable2 .= '</tbody>';
        $htmlTable2 .= '</table>';

        // Echo the HTML table
        $response = [
            'status' => 'success',
            'result' => $htmlTable . $htmlTable2,
            'draw' => null,
            'recordsTotal' => null,
            'recordsFiltered' => null
        ];

        return  $htmlTable . $htmlTable2;
    }



    // save / update componet data
    public function handleaddComponentData($postData)
    {
        $dataId = $postData['dataids'];
        $distributionValues = $postData['distributionValues'];
        $divisorValues = $postData['divisorValues'];
        $sensitivityValues = $postData['sensitivityValues'];
        $std_uncert_sum = 0;
        try {
            for ($i = 0; $i < count($dataId); $i++) {
                $stdUncert = $this->getStdUncert($dataId[$i], $divisorValues[$i], $sensitivityValues[$i]);
                $updateQuery = "UPDATE component_data SET `distribution` = :distributionValues, divisor = :divisorValues, sensitivity = :sensitivityValues, std_uncert = :stdUncert WHERE id = :dataId";
                $updateStatement = $this->conn->prepare($updateQuery);
                $updateStatement->bindParam(':distributionValues', $distributionValues[$i], PDO::PARAM_INT);
                $updateStatement->bindParam(':divisorValues', $divisorValues[$i], PDO::PARAM_INT);
                $updateStatement->bindParam(':sensitivityValues', $sensitivityValues[$i], PDO::PARAM_INT);
                $updateStatement->bindParam(':dataId', $dataId[$i], PDO::PARAM_INT);
                $updateStatement->bindParam(':stdUncert', $stdUncert, PDO::PARAM_INT);
                $updateStatement->execute();
                $std_uncert_sum += $stdUncert;
            }
            $this->updateExpandedUncertainty($dataId, $std_uncert_sum);
            return true;
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    public function getStdUncert($dataId, $divisorValues, $sensitivityValues)
    {
        $query = "SELECT magnitude FROM component_data WHERE id = :dataId";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':dataId', $dataId, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $std_uncert = '';
        if ($results && $divisorValues && $sensitivityValues) {
            $std_uncert = ($results['magnitude'] / $divisorValues) * $sensitivityValues;
        }

        return $std_uncert;
    }

    public function updateExpandedUncertainty($dataId, $std_uncert_sum)
    {

        if ($dataId) {
            $query = "SELECT row_id, uncertainty_budget_tempplate_id  FROM component_data WHERE id = :dataId";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':dataId', $dataId[0], PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetch(PDO::FETCH_ASSOC);

            $expanded =  $std_uncert_sum * 2;
            $euToReport = ($expanded >= 0.5) ? $expanded : 0.5;
            $coverageFactor = 2;
            $cmcClaimed = 0.5;

            // check record already exist
            $queryCheck = "SELECT * FROM expanded_uncertainty_reporting WHERE row_id = :rowId AND uncertainty_budget_tempplate_id = :uncertaintyTempplateId";
            $statementCheck = $this->conn->prepare($queryCheck);
            $statementCheck->bindParam(':rowId', $results['row_id'], PDO::PARAM_INT);
            $statementCheck->bindParam(':uncertaintyTempplateId', $results['uncertainty_budget_tempplate_id'], PDO::PARAM_INT);
            $statementCheck->execute();
            $resultsCheck = $statementCheck->fetch(PDO::FETCH_ASSOC);

            if ($resultsCheck) {
                $upadateQuery = "UPDATE expanded_uncertainty_reporting SET `row_id` = :rowId, `uncertainty_budget_tempplate_id` = :uncertaintyTempplateId,`combined_standard_uncertainty` = :combinedStandard, `coverage_factor` = :coverageFactor, `expanded_uncertainty` = :expandedUncertainty, `cmc_claimed` = :cmcClaimed, `eu_to_report` = :euToReport WHERE id = :id";
                $upadateStatement = $this->conn->prepare($upadateQuery);
                $upadateStatement->bindParam(':rowId', $results['row_id'], PDO::PARAM_INT);
                $upadateStatement->bindParam(':uncertaintyTempplateId', $results['uncertainty_budget_tempplate_id'], PDO::PARAM_INT);
                $upadateStatement->bindParam(':combinedStandard', $std_uncert_sum, PDO::PARAM_INT);
                $upadateStatement->bindParam(':coverageFactor', $coverageFactor, PDO::PARAM_INT);
                $upadateStatement->bindParam(':expandedUncertainty', $expanded, PDO::PARAM_INT);
                $upadateStatement->bindParam(':cmcClaimed', $cmcClaimed, PDO::PARAM_INT);
                $upadateStatement->bindParam(':euToReport', $euToReport, PDO::PARAM_INT);
                $upadateStatement->bindParam(':id', $resultsCheck['id'], PDO::PARAM_INT);

                $upadateStatement->execute();
            } else {
                $insertQuery = "INSERT INTO expanded_uncertainty_reporting (`row_id`, `uncertainty_budget_tempplate_id`,`combined_standard_uncertainty`,`coverage_factor`, `expanded_uncertainty`, `cmc_claimed`, `eu_to_report` ) 
                VALUES (:rowId, :uncertaintyTempplateId, :combinedStandard, :coverageFactor, :expandedUncertainty, :cmcClaimed, :euToReport)";
                $insertStatement = $this->conn->prepare($insertQuery);
                $insertStatement->bindParam(':rowId', $results['row_id'], PDO::PARAM_INT);
                $insertStatement->bindParam(':uncertaintyTempplateId', $results['uncertainty_budget_tempplate_id'], PDO::PARAM_INT);
                $insertStatement->bindParam(':combinedStandard', $std_uncert_sum, PDO::PARAM_INT);
                $insertStatement->bindParam(':coverageFactor', $coverageFactor, PDO::PARAM_INT);
                $insertStatement->bindParam(':expandedUncertainty', $expanded, PDO::PARAM_INT);
                $insertStatement->bindParam(':cmcClaimed', $cmcClaimed, PDO::PARAM_INT);
                $insertStatement->bindParam(':euToReport', $euToReport, PDO::PARAM_INT);

                $insertStatement->execute();
            }
        }
    }



    public function handleaddHedingValues($postData)
    {
        $level = $postData['level'];
        $values = $postData['values'];
        $ids = $postData['ids'];
        $templateID = $postData['template_id'];
        $strs = $postData['strs'];

        try {
            if (count($values) !== count($ids)) {
                // Handle error or return an appropriate response
                return false;
            }
            // get max id and create row id
            $maxquery = "SELECT MAX(row_id) max_id FROM value WHERE layout_template_id = :layoutTemplateId";
            $statementMax = $this->conn->prepare($maxquery);
            $statementMax->bindParam(':layoutTemplateId', $templateID, PDO::PARAM_INT);
            $statementMax->execute();
            $result =  $statementMax->fetch(PDO::FETCH_ASSOC);
            $rowId = $result['max_id'] + 1;

            $query = "INSERT INTO value (`headings_id`, `value`,`row_id`,`layout_template_id`, `value_collection` ) VALUES (:headings_id, :value_data, :row_id, :templateID, :valueCollection)";
            $statement = $this->conn->prepare($query);
            $lastInsertedId = null; // Initialize variable to store last inserted ID
            $fixedColumn = 0;
            for ($i = 0; $i < count($values); $i++) {
                $statement->bindParam(':headings_id', $ids[$i], PDO::PARAM_INT);
                $statement->bindParam(':value_data', $values[$i], PDO::PARAM_STR);
                $statement->bindParam(':row_id', $rowId, PDO::PARAM_STR);
                $statement->bindParam(':templateID', $templateID, PDO::PARAM_STR);
                $statement->bindParam(':valueCollection', $strs[$i], PDO::PARAM_STR);
                $statement->execute();
                // Get the last inserted ID after each iteration
                $lastInsertedId = $this->conn->lastInsertId();


                // Assuming $templateID, $referenceColumn, and $headingsId are defined earlier in your code
                $query_ubt = "SELECT id, reference_column FROM uncertainty_budget_tempplate WHERE template_id = :templateId AND reference_column = :referenceColumn";
                $statement_ubt = $this->conn->prepare($query_ubt);
                $statement_ubt->bindParam(':templateId', $templateID, PDO::PARAM_INT);
                $statement_ubt->bindParam(':referenceColumn', $ids[$i], PDO::PARAM_INT);
                $statement_ubt->execute();
                $results_ubt = $statement_ubt->fetchAll(PDO::FETCH_ASSOC);

                if (count($results_ubt) > 0) {

                    $magnitude_data = "";

                    // Assuming $rowId, $uncertaintyBudgetTemplateId, $headingsId, and $magnitude are defined earlier in your code
                    foreach ($results_ubt as $ubt) {
                        // Assuming $rowId, $headingsId, and $values[$i] are defined earlier in your code
                        switch ($ubt['reference_column']) {
                            case -1:
                                $magnitude_data = $postData['resolution_ref'];
                                break;
                            case -2:
                                $magnitude_data = $postData['resolution_uuc'];
                                break;
                            case -3:
                                $magnitude_data = $postData['resolution_uncert'];
                                break;
                            default:
                                $magnitude_data = $values[$i];
                                break;
                        }
                        $query_cd = "INSERT INTO component_data (row_id, uncertainty_budget_tempplate_id, headings_id, magnitude) VALUES (:rowId, :uncertaintyBudgetTemplateId, :headingsId, :magnitude)";
                        $statement_cd = $this->conn->prepare($query_cd);
                        $statement_cd->bindParam(':rowId', $rowId, PDO::PARAM_INT);
                        $statement_cd->bindParam(':uncertaintyBudgetTemplateId', $ubt['id'], PDO::PARAM_INT);
                        $statement_cd->bindParam(':headingsId', $ids[$i], PDO::PARAM_INT);
                        $statement_cd->bindParam(':magnitude', $magnitude_data, PDO::PARAM_STR);
                        $result_cd = $statement_cd->execute();

                        if ($result_cd) {
                            echo "Values inserted successfully into component_data table.";
                        } else {
                            echo "Error inserting values into component_data table.";
                        }
                    }
                } else {
                    // Record does not exist
                    echo "Record does not exist in uncertainty_budget_template table.";
                }
            }


            // insert fixed magnitude data
            $query_ubt_fixed = "SELECT id, reference_column FROM uncertainty_budget_tempplate WHERE template_id = :templateId AND reference_column < 0";
            $statement_ubt_fixed = $this->conn->prepare($query_ubt_fixed);
            $statement_ubt_fixed->bindParam(':templateId', $templateID, PDO::PARAM_INT);
            $statement_ubt_fixed->execute();
            $results_ubt_fixed = $statement_ubt_fixed->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results_ubt_fixed as $ubt) {
                switch ($ubt['reference_column']) {
                    case -1:
                        $magnitude_data = $postData['resolution_ref'];
                        break;
                    case -2:
                        $magnitude_data = $postData['resolution_uuc'];
                        break;
                    case -3:
                        $magnitude_data = $postData['resolution_uncert'];
                        break;
                    default:
                        $magnitude_data = 0;
                        break;
                }
                $query_fixed = "INSERT INTO component_data (row_id, uncertainty_budget_tempplate_id, headings_id, magnitude) VALUES (:rowId, :uncertaintyBudgetTemplateId, :headingsId, :magnitude)";
                $statement_fixed = $this->conn->prepare($query_fixed);
                $statement_fixed->bindParam(':rowId', $rowId, PDO::PARAM_INT);
                $statement_fixed->bindParam(':uncertaintyBudgetTemplateId', $ubt['id'], PDO::PARAM_INT);
                $statement_fixed->bindParam(':headingsId', $ubt['reference_column'], PDO::PARAM_INT);
                $statement_fixed->bindParam(':magnitude', $magnitude_data, PDO::PARAM_STR);
                $result_fixed = $statement_fixed->execute();
            }

            return true; // or provide any other success indicator
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    public function handleremoveLayout($postData)
    {
        $layoutID = $postData['layout_id'];
        $query = "DELETE FROM `layouts` WHERE id = :layout_id";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':layout_id', $layoutID, PDO::PARAM_INT);

        try {
            if ($statement->execute()) {
                // echo ['status' => 'success'];
            } else {
                //   echo ['status' => 'error', 'message' => 'Error deleting values.'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    }


    public function handleRemoveTemplate($postData)
    {
        $templateId = $postData['template_id'];
        try {
            // Step 1: Delete records from the `value` table by layout_template_id
            $deleteValueQuery = "DELETE FROM `value` WHERE layout_template_id = :template_id";
            $deleteValueStatement = $this->conn->prepare($deleteValueQuery);
            $deleteValueStatement->bindParam(':template_id', $templateId, PDO::PARAM_INT);
            $deleteValueStatement->execute();
    
            // Step 2: Select id from headings where layout_template_id matches
            $selectHeadingIdQuery = "SELECT id FROM `headings` WHERE layout_template_id = :template_id";
            $selectHeadingIdStatement = $this->conn->prepare($selectHeadingIdQuery);
            $selectHeadingIdStatement->bindParam(':template_id', $templateId, PDO::PARAM_INT);
            $selectHeadingIdStatement->execute();
            $headingIds = $selectHeadingIdStatement->fetchAll(PDO::FETCH_COLUMN);


            print_r($headingIds);
            if (!empty($headingIds)) { // Check if there are heading IDs to process
                foreach ($headingIds as $headingId) {
                    // Step 3: Select id from column_functions where headings_id matches
                    $selectColumnFuncIdQuery = "SELECT id,headings_id FROM `column_functions` WHERE headings_id = :heading_id";
                    $selectColumnFuncIdStatement = $this->conn->prepare($selectColumnFuncIdQuery);
                    $selectColumnFuncIdStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
                    $selectColumnFuncIdStatement->execute();
                    // $columnFuncIds = $selectColumnFuncIdStatement->fetchAll(PDO::FETCH_COLUMN);
                    $columnFuncIds = $selectColumnFuncIdStatement->fetchAll(PDO::FETCH_ASSOC);
    
                    if (!empty($columnFuncIds)) { // Check if there are column function IDs to process
                        foreach ($columnFuncIds as $columnFuncId) {
                            // Step 4: Delete records from column_function_reference_columns where function_column_id matches
                            $deleteRefColumnsQuery = "DELETE FROM `column_function_reference_columns` WHERE function_column_id = :column_func_id";
                            $deleteRefColumnsStatement = $this->conn->prepare($deleteRefColumnsQuery);
                            $deleteRefColumnsStatement->bindParam(':column_func_id', $columnFuncId['headings_id'], PDO::PARAM_INT);
                            $deleteRefColumnsStatement->execute();
    
                            // Step 5: Delete records from column_functions where id matches
                            $deleteColumnFuncQuery = "DELETE FROM `column_functions` WHERE id = :column_func_id";
                            $deleteColumnFuncStatement = $this->conn->prepare($deleteColumnFuncQuery);
                            $deleteColumnFuncStatement->bindParam(':column_func_id', $columnFuncId['id'], PDO::PARAM_INT);
                            $deleteColumnFuncStatement->execute();
                        }
                    }
                }
    
                // Step 9: Delete records from headings where id matches
                $deleteHeadingsQuery = "DELETE FROM `headings` WHERE id IN (" . implode(',', $headingIds) . ")";
                $deleteHeadingsStatement = $this->conn->prepare($deleteHeadingsQuery);
                $deleteHeadingsStatement->execute();
            }
    
            // Step 10: Truncate layout_template table by template_id
            $truncateTemplateQuery = "DELETE FROM `layout_template` WHERE id = :template_id";
            $truncateTemplateStatement = $this->conn->prepare($truncateTemplateQuery);
            $truncateTemplateStatement->bindParam(':template_id', $templateId, PDO::PARAM_INT);
            $truncateTemplateStatement->execute();
    
            return ['status' => 'success', 'message' => 'Template data successfully deleted.'];
        } catch (\Throwable $th) {
            return ['status' => 'error', 'message' => $th];
        }
    }
    


    public function handleallLayouts($postData)
    {
        $start = $postData['start'];
        $length = $postData['length'];

        $query = "SELECT l.id as id, l.layout_name ,t.template_name FROM layout_template t, layouts l WHERE l.layout_template_id = t.id LIMIT :start, :length";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':length', $length, PDO::PARAM_INT);
        $statement->execute();

        // Fetch all rows as an associative array
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Assuming you have a method to get the total count of records
        $totalRecords = $this->getLayoutTotalRecords();

        $response = [
            "draw" => $postData['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data,
        ];

        return $response;
    }


    public function handleallTemplates($postData)
    {
        $start = $postData['start'];
        $length = $postData['length'];

        $query = "SELECT id, template_name FROM layout_template LIMIT :start, :length";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':length', $length, PDO::PARAM_INT);
        $statement->execute();

        // Fetch all rows as an associative array
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Assuming you have a method to get the total count of records
        $totalRecords = $this->getLayoutTotalRecords();

        $response = [
            "draw" => $postData['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data,
        ];

        return $response;
    }


    public function allTemplates($draw, $start, $length)
    {
        $query = "SELECT t.id as id, l.layout_name ,t.template_name FROM layout_template t, layouts l WHERE l.layout_template_id = t.id LIMIT :start, :length";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':length', $length, PDO::PARAM_INT);
        $statement->execute();

        // Fetch all rows as an associative array
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Assuming you have a method to get the total count of records
        $totalRecords = $this->getTemplatesTotalRecords();

        $response = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords, // For simplicity, assuming no filtering
            "data" => $data
        ];

        // Output JSON and stop script execution
        // echo json_encode($response);
        return $response;
    }


    private function getLayoutTotalRecords()
    {
        $query = "SELECT COUNT(*) as count FROM layouts";
        $statement = $this->conn->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    public function getCellValues($id)
    {
        $query = "SELECT value FROM multiple_values WHERE value_id  = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    private function getTemplatesTotalRecords()
    {
        $query = "SELECT COUNT(*) as count FROM layout_template WHERE active = 1";
        $statement = $this->conn->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    private function getValuesUnderHeading()
    {
        $query = "SELECT COUNT(*) as count FROM layout_template WHERE active = 1";
        $statement = $this->conn->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }


    // public function handleeditTitle($postData)
    // {
    //     $headingText = $postData['heading_text'];
    //     $headingId = $postData['heading_id'];
    //     $headingType = $postData['heading_type'];
    //     $headingFunction = $postData['heading_function'];

    //     $query = "UPDATE headings SET title = :heading_text, column_type = :heading_type WHERE id = :heading_id";
    //     $statement = $this->conn->prepare($query);
    //     $statement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
    //     $statement->bindParam(':heading_text', $headingText, PDO::PARAM_STR);
    //     $statement->bindParam(':heading_type', $headingType, PDO::PARAM_STR);

    //     // Execute the update query for headings
    //     if ($statement->execute()) {
    //         // Check if heading type is not "DATA" and insert/update record in column_functions
    //         if ($headingType !== "DATA") {
    //             $queryFunc = "SELECT * FROM column_functions WHERE headings_id = :heading_id";
    //             $statementFunc = $this->conn->prepare($queryFunc);
    //             $statementFunc->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
    //             $statementFunc->execute();
    //             $funcRecord = $statementFunc->fetch(PDO::FETCH_ASSOC);

    //             if ($funcRecord) {
    //                 // Update record in column_functions table
    //                 $updateQuery = "UPDATE column_functions SET column_function = :heading_function WHERE headings_id = :heading_id";
    //                 $updateStatement = $this->conn->prepare($updateQuery);
    //                 $updateStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
    //                 $updateStatement->bindParam(':heading_function', $headingFunction, PDO::PARAM_STR);
    //                 $updateStatement->execute();


    //                 //clear column_function_reference_columns
    //                 $deleteQuerydataColumns = "DELETE FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
    //                 $deleteStatementdataColumns = $this->conn->prepare($deleteQuerydataColumns);
    //                 $deleteStatementdataColumns->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
    //                 $deleteStatementdataColumns->execute();

    //                 foreach ($postData['re_columns'] as $column) {
    //                     $insertQuery = "INSERT INTO column_function_reference_columns (function_column_id, reference_column_id) VALUES (:function_column_id, :reference_column_id)";
    //                     $insertStatement = $this->conn->prepare($insertQuery);
    //                     $insertStatement->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
    //                     $insertStatement->bindParam(':reference_column_id', $column, PDO::PARAM_INT);
    //                     $insertStatement->execute();
    //                 }
    //             } else {
    //                 // Insert record into column_functions table
    //                 $insertQuery = "INSERT INTO column_functions (headings_id, column_function) VALUES (:heading_id, :heading_function)";
    //                 $insertStatement = $this->conn->prepare($insertQuery);
    //                 $insertStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
    //                 $insertStatement->bindParam(':heading_function', $headingFunction, PDO::PARAM_STR);
    //                 $insertStatement->execute();


    //                 //clear column_function_reference_columns
    //                 $deleteQuerydataColumns = "DELETE FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
    //                 $deleteStatementdataColumns = $this->conn->prepare($deleteQuerydataColumns);
    //                 $deleteStatementdataColumns->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
    //                 $deleteStatementdataColumns->execute();

    //                 foreach ($postData['re_columns'] as $column) {
    //                     $insertQuery = "INSERT INTO column_function_reference_columns (function_column_id, reference_column_id) VALUES (:function_column_id, :reference_column_id)";
    //                     $insertStatement = $this->conn->prepare($insertQuery);
    //                     $insertStatement->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
    //                     $insertStatement->bindParam(':reference_column_id', $column, PDO::PARAM_INT);
    //                     $insertStatement->execute();
    //                 }
    //             }
    //         } else {
    //             // Delete record from column_functions table if heading type is "DATA"
    //             $deleteQuery = "DELETE FROM column_functions WHERE headings_id = :heading_id";
    //             $deleteStatement = $this->conn->prepare($deleteQuery);
    //             $deleteStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
    //             $deleteStatement->execute();

    //             //clear column_function_reference_columns
    //             $deleteQuerydataColumns = "DELETE FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
    //             $deleteStatementdataColumns = $this->conn->prepare($deleteQuerydataColumns);
    //             $deleteStatementdataColumns->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
    //             $deleteStatementdataColumns->execute();
    //         }

    //         return ['status' => 'success', 'message' => 'Successfully updated.'];
    //     } else {
    //         return ['status' => 'error', 'message' => 'Error updating title.'];
    //     }
    // }

    public function handleeditTitle($postData)
    {
        $headingText = $postData['heading_text'];
        $headingId = $postData['heading_id'];
        $headingType = $postData['heading_type'];
        $headingFunction = $postData['heading_function'];
        $referenceColumns = $postData['re_columns'];

        try {
            // Begin transaction
            $this->conn->beginTransaction();

            $updateQuery = "UPDATE headings SET title = :heading_text, column_type = :heading_type WHERE id = :heading_id";
            $updateStatement = $this->conn->prepare($updateQuery);
            $updateStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
            $updateStatement->bindParam(':heading_text', $headingText, PDO::PARAM_STR);
            $updateStatement->bindParam(':heading_type', $headingType, PDO::PARAM_STR);
            $updateStatement->execute();

            if ($headingType !== "DATA") {
                $funcQuery = "SELECT * FROM column_functions WHERE headings_id = :heading_id";
                $funcStatement = $this->conn->prepare($funcQuery);
                $funcStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
                $funcStatement->execute();
                $funcRecord = $funcStatement->fetch(PDO::FETCH_ASSOC);

                if ($funcRecord) {
                    $updateFuncQuery = "UPDATE column_functions SET column_function = :heading_function WHERE headings_id = :heading_id";
                } else {
                    $updateFuncQuery = "INSERT INTO column_functions (headings_id, column_function) VALUES (:heading_id, :heading_function)";
                }

                $updateFuncStatement = $this->conn->prepare($updateFuncQuery);
                $updateFuncStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
                $updateFuncStatement->bindParam(':heading_function', $headingFunction, PDO::PARAM_STR);
                $updateFuncStatement->execute();

                // Clear existing reference columns
                $clearRefColumnsQuery = "DELETE FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
                $clearRefColumnsStatement = $this->conn->prepare($clearRefColumnsQuery);
                $clearRefColumnsStatement->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
                $clearRefColumnsStatement->execute();

                // Insert new reference columns
                $insertRefColumnsQuery = "INSERT INTO column_function_reference_columns (function_column_id, reference_column_id) VALUES (:function_column_id, :reference_column_id)";
                $insertRefColumnsStatement = $this->conn->prepare($insertRefColumnsQuery);
                foreach ($referenceColumns as $column) {
                    $insertRefColumnsStatement->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
                    $insertRefColumnsStatement->bindParam(':reference_column_id', $column, PDO::PARAM_INT);
                    $insertRefColumnsStatement->execute();
                }
            } else {
                // Delete from column_functions and reference columns if heading type is "DATA"
                $deleteFuncQuery = "DELETE FROM column_functions WHERE headings_id = :heading_id";
                $deleteFuncStatement = $this->conn->prepare($deleteFuncQuery);
                $deleteFuncStatement->bindParam(':heading_id', $headingId, PDO::PARAM_INT);
                $deleteFuncStatement->execute();

                $clearRefColumnsQuery = "DELETE FROM column_function_reference_columns WHERE function_column_id = :function_column_id";
                $clearRefColumnsStatement = $this->conn->prepare($clearRefColumnsQuery);
                $clearRefColumnsStatement->bindParam(':function_column_id', $headingId, PDO::PARAM_INT);
                $clearRefColumnsStatement->execute();
            }

            // Commit transaction
            $this->conn->commit();

            return ['status' => 'success', 'message' => 'Successfully updated.'];
        } catch (\Throwable $th) {
            // Rollback transaction on error
            $this->conn->rollBack();
            return ['status' => 'error', 'message' => 'Error updating title: ' . $th->getMessage()];
        }
    }


    public function getTemplateIdByLayoutId($layoutId)
    {
        $query = "SELECT layout_template_id FROM layouts WHERE id = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $layoutId, PDO::PARAM_INT);
        $statement->execute();
        $templateId = $statement->fetchColumn(); // Directly fetch the template ID value
        return $templateId;
    }

    public function handleGetColumns($postData)
    {
        $layoutID = $postData['layout_id'];
        $templateId = $this->getTemplateIdByLayoutId($layoutID);

        $queryFunc = "SELECT id, title FROM headings WHERE layout_template_id = :layout_template_id";
        $statementFunc = $this->conn->prepare($queryFunc);
        $statementFunc->bindParam(':layout_template_id', $templateId, PDO::PARAM_INT);
        $statementFunc->execute();
        $funcRecords = $statementFunc->fetchAll(PDO::FETCH_ASSOC);

        $titlesAndIds = [];
        foreach ($funcRecords as $record) {
            $titlesAndIds[$record['id']] = $record['title'];
        }

        return $titlesAndIds; // Or process the data as needed
    }


    public function updateCellValue($id, $value)
    {
        $query = "UPDATE value SET value = :cellValue WHERE id = :cell_id";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':cell_id', $id, PDO::PARAM_INT);
        $statement->bindParam(':cellValue', $value, PDO::PARAM_INT);


        if ($statement->execute()) {
            return ['status' => 'success', 'message' => ' Successfully updated '];
        } else {
            return ['status' => 'error', 'message' => 'Error update values.'];
        }
    }

    public function deleteHeading($headingId)
    {
        try {
            // Delete records in the column_functions table first
            $deleteColumnFuncQuery = "DELETE FROM `column_functions` WHERE headings_id = :id";
            $deleteColumnFuncStatement = $this->conn->prepare($deleteColumnFuncQuery);
            $deleteColumnFuncStatement->bindParam(':id', $headingId, PDO::PARAM_INT);
            $deleteColumnFuncStatement->execute();

            // Then, delete the heading record in the headings table
            $deleteHeadingQuery = "DELETE FROM `headings` WHERE id = :id";
            $deleteHeadingStatement = $this->conn->prepare($deleteHeadingQuery);
            $deleteHeadingStatement->bindParam(':id', $headingId, PDO::PARAM_INT);

            if ($deleteHeadingStatement->execute()) {
                return ['status' => 'success'];
            } else {
                return ['status' => 'error', 'message' => 'Error deleting heading.'];
            }
        } catch (\Throwable $th) {
            return ['status' => 'error', 'message' => $th->getMessage()];
        }
    }


    public function handledeleteValues($postData)
    {
        try {
            $headingId = $postData['heading_id'];

            // Check if there are records in the value table for the given heading_id
            $checkQuery = "SELECT COUNT(*) FROM `value` WHERE headings_id = :id";
            $checkStatement = $this->conn->prepare($checkQuery);
            $checkStatement->bindParam(':id', $headingId, PDO::PARAM_INT);
            $checkStatement->execute();
            $rowCount = $checkStatement->fetchColumn();

            if ($rowCount > 0) {
                // Records exist in the value table, execute the delete query
                $deleteQuery = "DELETE FROM `value` WHERE headings_id = :id";
                $deleteStatement = $this->conn->prepare($deleteQuery);
                $deleteStatement->bindParam(':id', $headingId, PDO::PARAM_INT);
                $deleteStatement->execute();

                $this->deleteHeading($headingId);
                return ['status' => 'success'];
            } else {
                // No records in the value table, directly execute deleteHeading
                $this->deleteHeading($headingId);
                return ['status' => 'success', 'message' => 'No records in value table.'];
            }
        } catch (\Throwable $th) {
            return ['status' => 'error', 'message' => $th->getMessage()];
        }
    }


    public function insertMultipleValues($value_id, $value)
    {
        try {
            $query = "INSERT INTO multiple_values (value_id, value) VALUES (:value_id, :value)";

            $statement = $this->conn->prepare($query);
            $statement->bindParam(':value_id', $value_id);
            $statement->bindParam(':value', $value);

            if ($statement->execute()) {
                return ['status' => 'success'];
            } else {
                return ['status' => 'error', 'message' => 'Error inserting multiple values.'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    }


    public function updateResultValues($valueId, $value, $template_id, $header_id, $rowId)
    {
        $query = "UPDATE value SET value = :value WHERE id = :id AND layout_template_id = :layout_template_id AND headings_id = :headings_id AND row_id = :row_id";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $valueId);
        $statement->bindParam(':value', $value);
        $statement->bindParam(':layout_template_id', $template_id);
        $statement->bindParam(':headings_id', $header_id);
        $statement->bindParam(':row_id', $rowId);

        if ($statement->execute()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'message' => 'Error updating value.'];
        }
    }


    public function getValuesCount($valueId)
    {
        $query = "SELECT COUNT(id) AS idcount FROM `multiple_values` WHERE value_id = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $valueId);
        if ($statement->execute()) {
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'idcount' => $result['idcount']];
        } else {
            return ['status' => 'error', 'message' => 'Error updating value.'];
        }
    }

    public function getValueOfValues($idList)
    {
        $resultArray = array(); // Initialize an array to store results

        foreach ($idList as $value) {
            $query = "SELECT value FROM `multiple_values` WHERE value_id = :id";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':id', $value);
            if ($statement->execute()) {
                // Fetch all rows as associative arrays
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                // Initialize an array to store values for the current $value
                $valueArray = array();
                // Iterate over each row to get the 'value' field
                foreach ($results as $row) {
                    // Push the fetched value into the value array
                    $valueArray[] = $row['value'];
                }
                // Push the value array into the result array
                $resultArray[] = $valueArray;
            } else {
                return ['status' => 'error', 'message' => 'Error updating value.'];
            }
        }

        // Return the array containing arrays of fetched values
        return $resultArray;
    }
}
