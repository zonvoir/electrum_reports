<?php
include 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $postData = json_decode($input, true);

    // Debugging: Log the incoming data
    error_log("Received data: " . print_r($postData, true));

    if (!isset($postData['template_id']) || !isset($postData['layout_id']) || !isset($postData['data'])) {
        echo json_encode(['error' => 'Invalid input data.']);
        exit;
    }

    $template_id = $postData['template_id'];
    $layout_id = $postData['layout_id'];
    $data = $postData['data'];

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn === null) {
        echo json_encode(['error' => 'Database connection error.']);
        exit;
    }

    $conn->beginTransaction();

    try {
        $query = "INSERT INTO view_templates (template_id, layout_id, heading_id, value) 
                  VALUES (:template_id, :layout_id, :heading_id, :value)";
        $statement = $conn->prepare($query);

        foreach ($data as $item) {
          
          
            // Fetch heading_id based on template_id and layout_id
            $queryHeadingId = "SELECT id FROM headings WHERE layout_template_id = :template_id AND id = :heading_id LIMIT 1";
            $statementHeadingId = $conn->prepare($queryHeadingId);
            $statementHeadingId->bindParam(':template_id', $template_id, PDO::PARAM_INT);
            $statementHeadingId->bindParam(':heading_id', $item['heading_id'], PDO::PARAM_INT);
            $statementHeadingId->execute();

            $heading = $statementHeadingId->fetch(PDO::FETCH_ASSOC);

            if ($heading) {
                $heading_id = $heading['id'];
                print_r($heading_id); die;
                // Debugging: Log each item being inserted
                error_log("Inserting item: " . print_r($item, true));

                $statement->bindParam(':template_id', $template_id, PDO::PARAM_INT);
                $statement->bindParam(':layout_id', $layout_id, PDO::PARAM_INT);
                $statement->bindParam(':heading_id', $heading_id, PDO::PARAM_INT);
                $statement->bindParam(':values', $item['value'], PDO::PARAM_STR);
                $statement->execute();
            } else {
                error_log("Heading not found for item: " . print_r($item, true));
            }
        }

        $conn->commit();
        echo json_encode(['message' => 'Data saved successfully!']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['error' => 'Failed to save data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
