<?php
// Served only by the test Compose configuration; exposes the real parser's result.
require __DIR__.'/../../vendor/autoload.php';
$reader = new class {
    use \AppWeb\InputTrait;
    public function read() { return $this->_get_input($_SERVER['REQUEST_METHOD'], $_SERVER['CONTENT_TYPE'] ?? ''); }
};
header('Content-Type: application/json');
echo json_encode(get_object_vars($reader->read()), JSON_THROW_ON_ERROR);
