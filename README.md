# knapsack-problem-php
Knapsack Problem solver implementation in PHP.

### How to run
Run with command:<br/>
`php ScriptRunner.php --file=<file_path - string> --weight=<int> --algorithm=<int>`<br/>
Example (if script is run from main directory):<br/>
`php ScriptRunner.php --file=file.csv --weight=50 --algorithm=1`<br/>
`php ScriptRunner.php --file=file.csv --weight=20.12`

### Parameters
* file - text, CSV file path, required
* weight - int/float, maximum weight of knapsack, required
* algorithm - int, algorithm type value, optional, default: 1

### Available algorithms
* GreedyAlgorithm - value: 1
