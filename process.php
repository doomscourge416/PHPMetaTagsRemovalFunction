<?php

class MetaTagIterator implements Iterator
{
    private $tags = [];
    private $position = 0;

    public function __construct($htmlContent)
    {
        $this->tags = $this->extractRelevantMetaTags($htmlContent);
        $this->position = 0;
    }

    private function extractRelevantMetaTags($htmlContent)
    {
        $matches = [];
        $tags = [];

        // Извлекаем  <title>
        if (preg_match('/<title>(.*?)<\/title>/i', $htmlContent, $matches)) {
            $tags[] = '<title>' . $matches[1] . '</title>';
        }

        // Извлекаем description и keywords
        if (preg_match_all('/<meta[^>]+(name=["\'](description|keywords)["\'][^>]*|content=["\'](.*?)["\'][^>]*|name=["\'](description|keywords)["\'])[^>]*>/i', $htmlContent, $matches)) {
            $tags = array_merge($tags, $matches[0]);
        }

        return $tags;
    }

    public function current():mixed
    {
        return $this->tags[$this->position] ?? null;
    }

    public function key():mixed
    {
        return $this->position;
    }

    public function next():void
    {
        $this->position++;
    }

    public function valid():bool
    {
        return isset($this->tags[$this->position]);
    }

    public function rewind():void
    {
        $this->position = 0;
    }
}

// Получение пути к файлу из аргумента командной строки
if ($argc < 2) {
    die("Usage: php process.php <path_to_html_file>\n");
}

$htmlFilePath = $argv[1];

if (!file_exists($htmlFilePath)) {
    die("File not found: " . $htmlFilePath . PHP_EOL);
}

$htmlContent = file_get_contents($htmlFilePath);

$iterator = new MetaTagIterator($htmlContent);

echo "Extracted meta tags from {$htmlFilePath}:\n";
foreach ($iterator as $metaTag) {
    echo $metaTag . PHP_EOL;
}