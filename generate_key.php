<?php

// Generate SECRET KEY (32 bytes = 256 bit)
$secretKey = bin2hex(random_bytes(32));

// Generate SECRET IV (16 bytes = 128 bit)
$secretIv = bin2hex(random_bytes(16));

echo "SECRET_KEY = $secretKey <br>";
echo "SECRET_IV  = $secretIv <br>";