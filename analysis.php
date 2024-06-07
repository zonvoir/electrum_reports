<?php
require_once('database.php');
$database = new Database();
$conn = $database->getConnection();
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
            // echo '<pre>';
            // print_r($headings);
            // echo '</pre>';
            // die;
            // Initialize the result array
            $rows = [];

            //Group elements by their level
            foreach ($headings as $key => $heading) {
                $level = $heading['level'];
                if (!array_key_exists($level, $rows)) {
                    $rows[$level] = [];
                }
                $rows[$level][] = $heading;
            }

            echo '<div class="table-responive">';
            echo '<table id="template-table" class="table table-bordered">';
            // echo '<pre>';
            // print_r($rows);
            // echo '</pre>';
            // die;
            foreach ($rows as $key => $row) {
                echo '<tr>';
                // $title = '';
                foreach ($row as $heading) {
                    // $title = $heading['title'];
                    echo '<th class="p-1 text-nowrap text-center" colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '">';
                    echo $heading['title'];
                    echo '<input class="hide heading_check" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
                    echo '</th>';
                }
                echo '</tr>';

                // echo $title; die;
                // $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE template_id = :templateID AND title = :title";
                // $statementCalculationTemplate = $conn->prepare($queryCalculationTemplate);
                // $statementCalculationTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
                // $statementCalculationTemplate->bindParam(':title', $title, PDO::PARAM_INT);
                // $statementCalculationTemplate->execute();
                // $analyses = $statementCalculationTemplate->fetchAll(PDO::FETCH_ASSOC);
                // echo '<pre>';
                // print_r($rows);
                // echo '</pre>';
                // die;
                // echo '<tr>';
                // foreach ($analyses as $analysis) {
                //     echo '<td>'.$analysis['title_value'].'</td>';
                // }
                // echo '</tr>';
            }

            $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE template_id = :templateID";
            $statementCalculationTemplate = $conn->prepare($queryCalculationTemplate);
            $statementCalculationTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
            $statementCalculationTemplate->execute();
            $analyses = $statementCalculationTemplate->fetchAll(PDO::FETCH_ASSOC);

            $columns = count($row);
            $rows = 0; 
            $firstTitle = $analyses[0]['title'];
            $totalEntries = count($analyses);
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
                                echo $analyses[$currentIndex]['title_value'];
                            } else {
                                echo '&nbsp;';
                            }
                        echo '</td>';
                    }
                echo '</tr>';
            }

            // $groupedResults = [];
            // foreach ($analyses as $row) {
            //     $title = $row['title'];
            //     unset($row['title']);
            //     if (!isset($groupedResults[$title])) {
            //         $groupedResults[$title] = [];
            //     }
                
            //     $groupedResults[$title][] = $row;
            // }
            
            // $columns = 12; // Number of columns
            // $rows = 4; // Number of rows per column

            // // Create an array to store the grouped data by columns
            // $groupedData = array_fill(0, $columns, array());

            // // Distribute the data into columns
            // foreach ($groupedResults as $index => $result) {
            //     $columnIndex = intval($index) % $columns;
            //     $groupedData[$columnIndex][] = $result['title_value'];
            // }

            // // Now print the table row by row
            // for ($row = 0; $row < $rows; $row++) {
            //     echo '<tr>';
            //     for ($col = 0; $col < $columns; $col++) {
            //         echo '<td>';
            //         if (isset($groupedData[$col][$row])) {
            //             echo $groupedData[$col][$row];
            //         }
            //         echo '</td>';
            //     }
            //     echo '</tr>';
            // }

            // $dataCount = count($analysess); // Total number of data items
            // $columns = 12; // Number of columns
            // $rows = 4; // Number of rows per column
            // $currentIndex = 0; // To keep track of the current data item

            // for ($row = 0; $row < $rows; $row++) {
            //     echo '<tr>'; // Start a new row
            //     for ($col = 0; $col < $columns; $col++) {
            //         echo '<td>';
            //         if ($currentIndex < $dataCount) {
            //             // echo '<pre>';
            //             // print_r($analyses[$currentIndex]['title_value']);
            //             // echo '</pre>';
            //             // die;
            //             // Output the data item
            //             echo $analyses[$currentIndex]['title_value'];
            //         }
            //         echo '</td>';
            //         $currentIndex++;
            //     }
            //     echo '</tr>'; // End the row
            // }


            // $count = 0; 
            // $firstTitle = $analyses[0]['title'];
            // foreach ($analyses as $row) {
            //     if($row['title'] !=$firstTitle){
            //         break;
            //     }
            //     $count++;
            // }
            // // echo $count; die;
            // for($i=0; $i<$count; $i++){
            //     echo '<tr>';
            //     foreach ($analyses as $key=>$analysis) {
            //         echo '<td>'.$analyses[$key]['title_value'].'</td>';
            //     }
            //     echo '</tr>';
            // }

            
            //$firstTitle = $analyses[0]['title'];
            // foreach ($analyses as $row) {
            //     if($row['title'] !=$firstTitle){
            //         break;
            //     }
            //     $count++;
            // }
            // echo $count = count($analyses) / $count;
            // $limit = 0;
            // $c=0;
            // $d=0;
            // $j=0;
            // $newArray = [];
            // $firstTitle = $analyses[0]['title'];
            // for($i=0; $i<$count; $i++){
            //     $analyses[$c]['title_value'];
            //     $newArray[$d][$j] = $analyses[$c]['title_value'];                 
            //     if($analyses[$j]['title'] !=$firstTitle){
            //         $d++;
            //         $c=$d;
            //         $j=0;
            //     }
            //     else
            //     $c = $c + 4;
            //     $j++;
            // }

            // echo $count;
            // echo '<pre>';
            // print_r($analyses);
            // echo '</pre>';

            // echo $count;
            // echo '<pre>';
            // print_r($newArray);
            // echo '</pre>';
            // die;
            // $groupedResults = [];
            // foreach ($analyses as $row) {
            //     $title = $row['title'];
            //     unset($row['title']);
            //     if (!isset($groupedResults[$title])) {
            //         $groupedResults[$title] = [];
            //     }
                
            //     $groupedResults[$title][] = $row;
            // }
            // echo '<pre>';
            // print_r($groupedResults);
            // echo '</pre>';
            // die;
            // foreach ($groupedResults as $groupedResult) {
            //     echo '<tr>';
            //     foreach ($groupedResult as $analysis) {
            //         // echo '<pre>';
            //         // print_r($analysis);
            //         // echo '</pre>';
            //         echo '<td>'.$analysis['title_value'].'</td>';
            //         }
            //     echo '</tr>';
            // }
            echo '</table>';
            echo '</div>';
            ?>
        </div>
    </div>
    <?php
    require 'modals.php';
    ?>
    <script> 
    </script>
<?php
require 'footer.php';
?>