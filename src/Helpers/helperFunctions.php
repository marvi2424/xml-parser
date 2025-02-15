<?php

/**
 * Show usage help for the CLI script
 */
function showHelp() {
    echo "Usage:\n";
    echo "  php src/main.php [insert|update|delete]\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php src/main.php insert\n";
    echo "  php src/main.php update\n";
    echo "  php src/main.php delete\n";
}