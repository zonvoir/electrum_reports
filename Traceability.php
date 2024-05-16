<?php
class Traceability
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertTraceability($data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO traceability_table (equipment_id, standard, cal_date,name,statement,certificate_no_new,chain,lab) VALUES (?, ?, ?,?,?,?,?,?)");
            $stmt->execute($data);
        } catch (PDOException $exception) {
            echo "Insertion error: " . $exception->getMessage();
        }
    }

    public function getDateList()
    {
        try {
            $stmt = $this->conn->query("SELECT DISTINCT cal_date FROM traceability_table");
            $dates = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Fetch distinct dates as an array

            if ($dates) {
                return $dates;
            } else {
                return false; // No data found in the table
            }
        } catch (PDOException $exception) {
            echo "Error: " . $exception->getMessage();
            return false;
        }
    }

    public function getLatestCalDate(string $date)
    {
        $givenDate = new DateTime($date);
        // $dateList = array('2023-07-05', '2023-07-06', '2023-07-08', '2023-07-20', '2023-07-22', '2023-07-25');
        $dateList = $this->getDateList();
        $latestDate = null;

        // foreach ($dateList as $dateString) {
        //     $date = new DateTime($dateString);

        //     if ($date <= $givenDate) {
        //         if ($latestDate === null || $date > $latestDate) {
        //             $latestDate = $date;
        //         }
        //     }
        // }
        $stmt = $this->conn->query("SELECT max(cal_date) as cal_date FROM traceability_table where cal_date <= '2023-09-28' AND equipment_id = 'ECAL/WS/T04';");

        if ($latestDate !== null) {
            $latestDateString = $latestDate->format('Y-m-d'); // Format the date as needed
            return $latestDateString;
        } else {
            return $givenDate;
        }
    }



    public function getStatementFromTraceability(string $date, string $equipment_id)
    {
        try {
            $stmt = $this->conn->query("SELECT id,statement,certificate_no_new FROM traceability_table WHERE equipment_id = :eqid AND cal_date = :caldate");
            $stmt->bindParam(':layoutId', $_POST['eqid'], PDO::PARAM_INT);
        } catch (PDOException $exception) {
            echo "Error: " . $exception->getMessage();
            return false;
        }
    }
}
