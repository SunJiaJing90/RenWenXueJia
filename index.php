
# 创建断点上传对象
$breakpoint = new Breakpoint('demo.php');
# 上传文件
$breakpoint->upload();

// Path: breakpoint.php
<?php
class Breakpoint {
    private $file = '';
    private $breakpoint_file = '';
    private $breakpoint_size = 1024 * 1024; // 1M
    public function __construct($file) {
        $this->file = $file;
        $this->breakpoint_file = $this->file . '.breakpoint';
    }
    public function upload() {
        $file = $this->file;
        $breakpoint_file = $this->breakpoint_file;
        $breakpoint_size = $this->breakpoint_size;
        $breakpoint = file_exists($breakpoint_file) ? file_get_contents($breakpoint_file) : 0;
        $filesize = filesize($file);
        $handle = fopen($file, 'rb');
        fseek($handle, $breakpoint);
        while (!feof($handle)) {
            $data = fread($handle, $breakpoint_size);
            $breakpoint += $breakpoint_size;
            file_put_contents($breakpoint_file, $breakpoint);
            file_put_contents($file, $data, FILE_APPEND);
        }
        fclose($handle);
        unlink($breakpoint_file);
    }
}

$breakpoint = new Breakpoint('index.php');
