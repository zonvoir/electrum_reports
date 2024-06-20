<?php require 'header.php'; ?>

<div class="container">
    <?php if ($role['name'] == 'data-entry-operator'): ?>
        <?php require '401.php'; ?>
    <?php else: ?>
        <div class="mt-4">
            <?php
            $layout_id = $_GET['layout_id'];

            $query = "SELECT * FROM layouts WHERE id = :layoutID";
            $statement = $conn->prepare($query);
            $statement->bindParam(':layoutID', $layout_id, PDO::PARAM_INT);
            $statement->execute();
            $layout = $statement->fetch(PDO::FETCH_ASSOC);

            $layoutTemplateID = $layout['layout_template_id'];

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
                    WHERE  h.level IN ($in) AND h.layout_id = ? AND h.layout_template_id = ? 
                    ORDER BY h.id, h.parent_id";

            $statement1 = $conn->prepare($query1);
            // $params = array_merge($hLevel, [$layoutID, $layoutTemplateID]);
            $params = [...$hLevel, $layout_id, $layoutTemplateID];
            $statement1->execute($params);
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
            echo '<div class="table-responsive">';
            echo '<table id="template-table" class="table table-bordered">';
            foreach ($rows as $key => $row) {
                echo '<tr>';
                foreach ($row as $heading) {
                    echo '<th class="p-1 text-nowrap text-center" colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '">';
                    echo $heading['title'];
                    echo '<input class="hide heading_check" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
                    echo '</th>';
                }
                echo '<th class="p-1">Action</th>';
                echo '</tr>';
            }

            $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE layout_id = :layoutID AND template_id = :templateID";
            $statementCalculationTemplate = $conn->prepare($queryCalculationTemplate);
            $statementCalculationTemplate->bindParam(':layoutID', $layout_id, PDO::PARAM_INT);
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
                        $arr = [];
                        for ($j = 0; $j < $columns; $j++) {
                            echo '<td class="" colspan="' . $row[$j]['colspan'] . '">';
                                $currentIndex = $j * $rows + $i;
                                if ($currentIndex < $totalEntries) {
                                    $arr[$analyses[$currentIndex]['title']]=$analyses[$currentIndex]['title_value'];
                                    echo nl2br($analyses[$currentIndex]['title_value']);
                                } else {
                                    echo '&nbsp;';
                                }
                            echo '</td>';
                        }
                        echo '<td><a href="#" class="table2" data-row_id="'.($i+1).'" data-data="'.htmlspecialchars(json_encode($arr)).'"><i class="fa fa-eye"></i></a></td>';
                    echo '</tr>';
                }
            }
            echo '</table>';
            echo '</div>';
            ?>
        </div>
    <?php endif; ?>
</div>
<script>
$(document).ready(function() {
    var layout_id = <?php echo $layout_id; ?>;
    var template_id = <?php echo $layoutTemplateID; ?>;
    $(".table2").on('click', function(){
        var row_id = $(this).attr('data-row_id');
        $.ajax({
            type: 'POST',
            url: './functions/ComponentAction.php',
            data: {
                action: 'loadTable2Data',
                layout_id: layout_id,
                template_id: template_id,
                row_id: row_id,
            },
            success: function(dataJSON) {
                var response = JSON.parse(dataJSON)
                if (response.status === 'success') {
                    $('#table2tbody').html(response.tableHTML);
                    $("#table2Modal").modal('show');
                    $("#table3tbody").hide();
                }
            }
        });
    });

    $("body").on('change', '.distribution', function(){
        var value = $(this).val();
        $(this).closest('tr').find('.divisor').val(value); 
        stdUncertCalculation($(this));
    });

    $("body").on('input', '.sensitivity', function(){
        stdUncertCalculation($(this));
    });

    function stdUncertCalculation(that)
    {
        var col2 = that.closest('tr').find('.magnitude').val(); 
        var col4 = that.closest('tr').find('.divisor').val(); 
        var col5 = that.closest('tr').find('.sensitivity').val();
        var col6 = col2 * col5 / col4;
        var std_unsert = isNumeric(col6) ? col6 : '0.00';
        that.closest('tr').find('.std_uncert').val(std_unsert); 
    }

    function isNumeric(value) {
        return !isNaN(value) && isFinite(value);
    }

    var distributionFieldIsValid = false;
    var sensitivityFieldIsValid = false;
    $("body").on('click', '.addValues', function(){

        $('.distribution-field-validation').each(function() {
            distributionFieldIsValid = validateInput($(this));
        });

        $('.sensitivity-field-validation').each(function() {
            sensitivityFieldIsValid = validateInput($(this));
        });

        if (distributionFieldIsValid == false || sensitivityFieldIsValid==false) {
            $("#table3tbody").hide();
            toastrErrorMessage('Distribution and Sensitivity all fields is required!');
        } else {
            var csuValue = 0;
            $('.std_uncert').each(function(){
                var value = parseFloat($(this).val()) || 0;
                csuValue += value * value;
            });
            $(".csu").val(csuValue.toFixed(4));

            var expanded_uncertainty = csuValue*2;
            $(".expanded_uncertainty").val(expanded_uncertainty.toFixed(4));

            var cmc_claimed = $(".cmc_claimed").val();
            var report = expanded_uncertainty >= cmc_claimed ? expanded_uncertainty : cmc_claimed;
            $(".report").val(report.toFixed(4));

            $("#table3tbody").show();
        }
    });

    function validateInput(input) {
        const value = input.val().trim();
        if (value === '') {
            return false;
        } else {
            return true;
        }
    }

    function toastrErrorMessage(message) {
        toastr.error(message, 'Opps!', {
            timeOut: 3000,
            extendedTimeOut: 2000,
            progressBar: true,
            closeButton: true,
            tapToDismiss: false,
            positionClass: "toast-top-right",
        });
    }
});
</script>
<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
</style>
<?php require 'footer.php'; ?>