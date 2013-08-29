<?

// check if readfile or curl
$inis = ini_get_all();

$allow_url_fopen=$inis['allow_url_fopen']['global_value'];

print "<pre>";
print "BEGIN\n";
print "allow_url_fopen=$allow_url_fopen\n";
print "END\n";
print_r($inis);
print "</pre>";
phpinfo();

// Create the Monk Cache

mkdir('monkcache', 0777);
chmod('monkcache', 0777);

?>
