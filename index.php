<?php

// Forward requests to the public front controller when the web root
// points at the project root (common in shared hosting).
require __DIR__ . '/public/index.php';
