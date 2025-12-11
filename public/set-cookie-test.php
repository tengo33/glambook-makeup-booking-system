<?php
// Set multiple cookies directly
setcookie('test_cookie_1', 'value1_' . time(), time() + 3600, '/', 'localhost', false, true);
setcookie('test_cookie_2', 'value2_' . time(), time() + 3600, '/', 'localhost', false, true);
setcookie('laravel-session', 'test_session_' . time(), time() + 3600, '/', 'localhost', false, true);
setcookie('laravel_session', 'test_session_underscore_' . time(), time() + 3600, '/', 'localhost', false, true);

echo "<h2>Cookies Set</h2>";
echo "Check browser cookies for:<br>";
echo "1. test_cookie_1<br>";
echo "2. test_cookie_2<br>";
echo "3. laravel-session<br>";
echo "4. laravel_session<br>";
echo "<br>Current cookies: " . print_r($_COOKIE, true);