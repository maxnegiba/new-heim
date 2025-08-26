<?php
namespace Blog;

class BlogCache {
    private $cacheDir;
    private $enabled;
    
    public function __construct($cacheDir = null, $enabled = true) {
        $this->cacheDir = $cacheDir ?: __DIR__ . '/../cache/';
        $this->enabled = $enabled;
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }
    
    public function get($key) {
        if (!$this->enabled) return null;
        
        $file = $this->getCacheFile($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $data = unserialize(file_get_contents($file));
        
        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }
        
        return $data['value'];
    }
    
    public function set($key, $value, $ttl = 3600) {
        if (!$this->enabled) return false;
        
        $file = $this->getCacheFile($key);
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        return file_put_contents($file, serialize($data), LOCK_EX) !== false;
    }
    
    public function delete($key) {
        $file = $this->getCacheFile($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return true;
    }
    
    public function clear($pattern = '*') {
        $files = glob($this->cacheDir . $pattern . '.cache');
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return true;
    }
    
    private function getCacheFile($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }
}