<?php
include 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $postData = json_decode($input, true);

    if (!isset($postData['template_id'])) {
        echo json_encode(['error' => 'Invalid input data.']);
        exit;
    }

    $template_id = $postData['template_id'];

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn === null) {
        echo json_encode(['error' => 'Database connection error.']);
        exit;
    }

    try {
        // Fetch layout_id
        $query = "SELECT id FROM layouts WHERE layout_template_id = :template_id LIMIT 1";
        $statement = $conn->prepare($query);
        $statement->bindParam(':template_id', $template_id, PDO::PARAM_INT);
        $statement->execute();
        $layout = $statement->fetch(PDO::FETCH_ASSOC);
        // Fetch headings
        $query = "SELECT id, title FROM headings WHERE layout_template_id = :template_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':template_id', $template_id, PDO::PARAM_INT);
        $statement->execute();
        $headings = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($layout) {
            echo json_encode(['layout_id' => $layout['id'], 'headings' => $headings]);
        } else {
            echo json_encode(['error' => 'Layout or headings not found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to fetch layout_id and headings: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
