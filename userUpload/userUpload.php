<?php
// Function to validate numeric input
function validateInput($input, $type = 'float') {
    if ($type == 'float') {
        return filter_var($input, FILTER_VALIDATE_FLOAT) !== false;
    } elseif ($type == 'int') {
        return filter_var($input, FILTER_VALIDATE_INT) !== false;
    }
    return false;
}

// Pressure for the first planet
do {
    echo("Please enter pressure for the first planet (PA): \n");
    $pressure1 = trim(fgets(STDIN));
    if (!validateInput($pressure1, 'float')) {
        echo "Invalid input. Please enter a valid number for pressure.\n";
    }
} while (!validateInput($pressure1, 'float'));

// Temperature for the first planet
do {
    echo("Please enter temperature for the first planet (degree Celsius): \n");
    $temperature1 = trim(fgets(STDIN));
    if (!validateInput($temperature1, 'float')) {
        echo "Invalid input. Please enter a valid number for temperature.\n";
    }
} while (!validateInput($temperature1, 'float'));

// Wind speed for the first planet
do {
    echo("Please enter wind for the first planet (m/s): \n");
    $wind1 = trim(fgets(STDIN));
    if (!validateInput($wind1, 'float')) {
        echo "Invalid input. Please enter a valid number for wind speed.\n";
    }
} while (!validateInput($wind1, 'float'));

// Pressure for the second planet
do {
    echo("Please enter pressure for the second planet (PA): \n");
    $pressure2 = trim(fgets(STDIN));
    if (!validateInput($pressure2, 'float')) {
        echo "Invalid input. Please enter a valid number for pressure.\n";
    }
} while (!validateInput($pressure2, 'float'));

// Temperature for the second planet
do {
    echo("Please enter temperature for the second planet (degree Celsius): \n");
    $temperature2 = trim(fgets(STDIN));
    if (!validateInput($temperature2, 'float')) {
        echo "Invalid input. Please enter a valid number for temperature.\n";
    }
} while (!validateInput($temperature2, 'float'));

// Wind speed for the second planet
do {
    echo("Please enter wind for the second planet (m/s): \n");
    $wind2 = trim(fgets(STDIN));
    if (!validateInput($wind2, 'float')) {
        echo "Invalid input. Please enter a valid number for wind speed.\n";
    }
} while (!validateInput($wind2, 'float'));

$cmd = escapeshellcmd("python3 userUpload/user_plot.py $pressure1 $temperature1 $wind1 $pressure2 $temperature2 $wind2");
$output = shell_exec($cmd);

echo $output;
?>