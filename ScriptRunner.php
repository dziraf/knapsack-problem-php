<?php
declare(strict_types=1);
require_once('Item.php');
require_once('Knapsack.php');
require_once('GreedyAlgorithm.php');

/**
 * Algorithm types
 */
define("GREEDY_ALGORITHM", 1);

/**
 * Default algorithm to run if none was specified by user
 */
define("DEFAULT_ALGORITHM", GREEDY_ALGORITHM);

/**
 * Maximum input number for algorithm type
 */
define("MAX_ALGORITHM_INPUT", 1);

/**
 * Error codes
 */
define("E_NOT_ENOUGH_ARGS", -101);
define("E_TOO_MANY_ARGS", -102);
define("E_NO_ARGS", -103);
define("E_INVALID_FILE_FORMAT", -201);
define("E_INVALID_WEIGHT_INPUT", -202);
define("E_INVALID_ALGORITHM_INPUT_TYPE", -203);
define("E_INVALID_ALGORITHM_INPUT_VALUE", -204);
define("E_INVALID_FILE_CONTENTS", -301);
define("NO_ERRORS_FOUND", 1);


/**
 * Class ScriptRunner
 */
class ScriptRunner
{
    private $csvFile;
    private $knapsackWeight;
    private $algorithmType;

    /**
     * ScriptRunner constructor.
     */
    public function __construct()
    {
        $this->csvFile = "";
        $this->knapsackWeight = 0.0;
        $this->algorithmType = DEFAULT_ALGORITHM;
    }


    /**
     * Evaluates arguments number
     * @param array $arguments      array of command line arguments
     * @return int                  error code if arguments number is incorrect, 1 if there were no errors
     */
    function checkArgumentsNumber(array $arguments) : int
    {
        $arguments_number = sizeof($arguments);

        // Check if correct format was used
        if($arguments_number === 0) {
            return E_NO_ARGS;
        }

        // Check if all required arguments were given
        if($arguments_number < 2) {
            return E_NOT_ENOUGH_ARGS;
        }

        // Check if too many arguments were given
        if($arguments_number > 3) {
            return E_TOO_MANY_ARGS;
        }

        // No errors
        return NO_ERRORS_FOUND;
    }


    /**
     * Proceeds with script if no errors found.
     * Displays an error message and exits script if an error was found.
     * @param int $resultCode      number representing a function's result code
     */
    function handleErrors(int $resultCode) : void
    {
        switch($resultCode) {
            case E_NOT_ENOUGH_ARGS:
                echo "Not enough arguments given. Minimum: 2.\n";
                exit;
            case E_TOO_MANY_ARGS:
                echo "Too many arguments given. Maximum: 3.\n";
                exit;
            case E_NO_ARGS:
                echo "No arguments were given. Make sure you follow correct format.\nExample: php ScriptRunner.php --file=file.csv --weight=50 --algorithm=1";
                exit;
            case E_INVALID_FILE_FORMAT:
                echo "File: .csv file format is required.\n";
                exit;
            case E_INVALID_WEIGHT_INPUT:
                echo "Weight: floating point number is required.\n";
                exit;
            case E_INVALID_ALGORITHM_INPUT_TYPE:
                echo "Algorithm: integer is required.\n";
                exit;
            case E_INVALID_ALGORITHM_INPUT_VALUE:
                echo "Incorrect value of algorithm type. Minimum is " . DEFAULT_ALGORITHM . ", maximum is " . MAX_ALGORITHM_INPUT . ".\n";
                exit;
            case E_INVALID_FILE_CONTENTS:
                echo "Invalid contents in given file. Make sure each line follows format: 'int;float or int;float or int'.\n";
                exit;
            default:
                // Display nothing, continue the script
        }
    }


    /**
     * Evaluates arguments' types and values.
     * @param array $arguments      array of arguments passed to the script
     * @return int                  error code if arguments number was incorrect, 1 if there were no errors
     */
    function checkArgumentsTypes(array &$arguments) : int
    {
        // Check if file is a .csv file
        if(substr($arguments['file'], -4) !== '.csv') {
            return E_INVALID_FILE_FORMAT;
        }

        // Check if knapsack weight is a correct floating point number
        if(preg_match('/^\d*\.?\d*$/', $arguments['weight']) !== 1) {
            return E_INVALID_WEIGHT_INPUT;
        }

        // Check if algorithm type argument was given
        if(array_key_exists('algorithm', $arguments)) {
            $is_correct_type = preg_match('/^\d+$/', $arguments['algorithm']) === 1;

            // Check if correct algorithm type input was given
            if(!$is_correct_type)
                return E_INVALID_ALGORITHM_INPUT_TYPE;

            // Check if correct algorithm type value was given
            if($is_correct_type && ((int)$arguments['algorithm'] < DEFAULT_ALGORITHM || (int)$arguments['algorithm'] > MAX_ALGORITHM_INPUT))
                return E_INVALID_ALGORITHM_INPUT_VALUE;
        }
        // No errors
        return NO_ERRORS_FOUND;
    }


    /**
     * Loads arguments to referenced variables.
     * @param array $arguments          array of arguments passed to the script
     */
    function loadArguments(array &$arguments) : void
    {
        $this->csvFile = $arguments['file'];
        $this->knapsackWeight = (float)$arguments['weight'];
        if(array_key_exists('algorithm', $arguments))
            $this->algorithmType = (int)$arguments['algorithm'];
    }


    /**
     * Loads file contents to an array, displays a message if file couldn't be loaded.
     * @return array                    array of file's lines
     */
    function readCsvFile() : array
    {
        if(file_exists($this->csvFile)) {
            return file($this->csvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
        else {
            echo "Unable to open file. Make sure correct file path was specified.\n";
            exit;
        }
    }

    /**
     * @param array $fileContents       array of lines loaded from file
     * @return int                      error code if file contents were invalid, 1 if there were no errors
     */
    function verifyFileContents(array &$fileContents) : int
    {
        $number_of_lines = sizeof($fileContents);
        for($i = 1; $i < $number_of_lines; $i++) {
            if(preg_match('/^\d+;\d*\.?\d*;\d*\.?\d*$/', $fileContents[$i]) !== 1) return E_INVALID_FILE_CONTENTS;
        }

        // No errors
        return NO_ERRORS_FOUND;
    }


    /**
     * Build Items array from file contents
     * @param array $fileContents           array containing file contents
     * @return array                        array of Items
     */
    function loadItems(array &$fileContents) : array
    {
        $items_number = sizeof($fileContents);
        $items_array = [];
        $current_item = 0;
        for($i = 1; $i < $items_number; $i++) {     // ignore first line containing column names
            $item = explode(';', $fileContents[$i]);
            if((float)$item[1] <= $this->knapsackWeight) {
                $items_array[$current_item] = new Item((int)$item[0], (float)$item[1], (float)$item[2]);
                $current_item++;
            }
        }
        return $items_array;
    }


    /**
     * @param array $itemsArray             array of items
     * @return Knapsack                     optimized Knapsack
     */
    private function runAlgorithm(array &$itemsArray) : Knapsack
    {
        switch($this->algorithmType) {
            case GREEDY_ALGORITHM:
                return GreedyAlgorithm::run($itemsArray, $this->knapsackWeight);
            default:
                return GreedyAlgorithm::run($itemsArray, $this->knapsackWeight);
        }
    }


    /**
     * Sets CSV File path
     * @param string $csvFile       new file path
     */
    public function setCsvFile($csvFile): void
    {
        $this->csvFile = $csvFile;
    }


    /**
     * Sets knapsack's max weight
     * @param float $knapsackWeight     new weight
     */
    public function setKnapsackWeight($knapsackWeight): void
    {
        $this->knapsackWeight = $knapsackWeight;
    }


    /**
     * Sets algorithm type
     * @param int $algorithmType        new algorithm type
     */
    public function setAlgorithmType($algorithmType): void
    {
        $this->algorithmType = $algorithmType;
    }


    /**
     * Returns CSV file path
     * @return string       CSV file path
     */
    public function getCsvFile(): string
    {
        return $this->csvFile;
    }


    /**
     * Returns knapsack's weight
     * @return float        knapsack's max weight
     */
    public function getKnapsackWeight(): float
    {
        return $this->knapsackWeight;
    }


    /**
     * Returns algorithm type number
     * @return int          algorithm type number
     */
    public function getAlgorithmType(): int
    {
        return $this->algorithmType;
    }


    /**
     * Starts the script.
     * @param array $arguments      cli arguments
     */
    public function run() : void
    {
        if (PHP_SAPI !== "cli") {
            exit;
        }

        $arguments = getopt('', ['file:', 'weight:', 'algorithm:']);

        // Check if the number of given arguments is correct
        $this->handleErrors($this->checkArgumentsNumber($arguments));

        // Check if arguments types are correct
        $this->handleErrors($this->checkArgumentsTypes($arguments));

        // Load arguments
        $this->loadArguments($arguments);

        // Load file contents
        $file_contents = $this->readCsvFile();

        // Verify file contents
        $this->handleErrors($this->verifyFileContents($file_contents));

        // Load items
        $items = $this->loadItems($file_contents);

        // Run algorithm, get optimized knapsack
        $optimized_knapsack = $this->runAlgorithm($items);

        // Display results
        echo (string)$optimized_knapsack;
    }
}

$script = new ScriptRunner();
$script->run();
