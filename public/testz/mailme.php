<?

$to = 'lorddeath@gmail.com';

echo "Sending mail to $to:";
mail($to, 'Test', 'Test message', "From: $to");

?>