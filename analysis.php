<?php
require_once('database.php');
$database = new Database();
$conn = $database->getConnection();
require 'check-login.php';
require 'header.php';
?>

<body>
    <?php
    require 'navigation.php';
    ?>
    <div class="container1 p-3">
        <div class="row mt-4">
            <?php
            $layoutTemplateID = $_GET['id'];

            $queryTemplate = "SELECT levels FROM layout_template WHERE id = :templateID";
            $statementTemplate = $conn->prepare($queryTemplate);
            $statementTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
            $statementTemplate->execute();
            $template = $statementTemplate->fetch(PDO::FETCH_ASSOC);

            $totalLevels = $template ? $template['levels'] : 1;
            $hLevel = range(1, $totalLevels);
            $in  = str_repeat('?,', count($hLevel) - 1) . '?';

            $query1 = "SELECT h.*, c.column_function FROM headings h 
                    LEFT JOIN column_functions c ON h.id = c.headings_id 
                    WHERE  h.level IN ($in) AND h.layout_template_id = ? 
                    ORDER BY h.id, h.parent_id";

            $statement1 = $conn->prepare($query1);
            $statement1->execute([...$hLevel, $layoutTemplateID]);
            $headings = $statement1->fetchAll(PDO::FETCH_ASSOC);

            $rows = [];
            foreach ($headings as $key => $heading) {
                $level = $heading['level'];
                if (!array_key_exists($level, $rows)) {
                    $rows[$level] = [];
                }
                $rows[$level][] = $heading;
            }

            echo '<h3 class="mb-4 text-center">Analysis</h3>';
            echo '<div class="table-responive">';
            echo '<table id="template-table" class="table table-bordered">';
            foreach ($rows as $key => $row) {
                echo '<tr>';
                foreach ($row as $heading) {
                    echo '<th class="p-1 text-nowrap text-center" colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '">';
                    echo $heading['title'];
                    echo '<input class="hide heading_check" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
                    echo '</th>';
                }
                echo '</tr>';
            }

            $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE template_id = :templateID";
            $statementCalculationTemplate = $conn->prepare($queryCalculationTemplate);
            $statementCalculationTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
            $statementCalculationTemplate->execute();
            $analyses = $statementCalculationTemplate->fetchAll(PDO::FETCH_ASSOC);
            
            $totalEntries = count($analyses);
            if ($totalEntries > 0) {
                $columns = count($row);
                $rows = 0; 
                $firstTitle = $analyses[0]['title'];
                foreach ($analyses as $analysis) {
                    if($analysis['title'] !=$firstTitle){
                        break;
                    }
                    $rows++;
                }

                for ($i = 0; $i < $rows; $i++) {
                    echo '<tr>';
                        for ($j = 0; $j < $columns; $j++) {
                            echo '<td class="" colspan="' . $row[$i]['colspan'] . '">';
                                $currentIndex = $j * $rows + $i;
                                if ($currentIndex < $totalEntries) {
                                    echo nl2br($analyses[$currentIndex]['title_value']);
                                } else {
                                    echo '&nbsp;';
                                }
                            echo '</td>';
                        }
                    echo '</tr>';
                }
            }
            echo '</table>';
            echo '</div>';
            ?>
        </div>
    </div>
    <?php
    require 'modals.php';
    ?>
<?php
require 'footer.php';
?>