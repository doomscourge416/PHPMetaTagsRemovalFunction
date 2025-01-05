<?php

class MetaTagIterator implements Iterator {
    private $dom;
    private $elements;
    private $position = 0;

    public function __construct($html) {
        // Создаем новый объект DOMDocument
        $this->dom = new DOMDocument();
        @$this->dom->loadHTML($html);
        $this->elements = [];

        // Собираем мета-теги и заголовок
        // Получаем все заголовки и мета-теги
        $titleElements = $this->dom->getElementsByTagName('title');
        foreach ($titleElements as $element) {
            $this->elements[] = $element;
        }

        $metaElements = $this->dom->getElementsByTagName('meta');
        foreach ($metaElements as $element) {
            $this->elements[] = $element;
        }
    }

    public function current() {
        return $this->elements[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function valid() {
        return isset($this->elements[$this->position]);
    }

    public function removeCurrent() {
        if ($this->valid()) {
            $this->current()->parentNode->removeChild($this->current());
        }
    }

    public function getDOM() {
        return $this->dom; // Метод для получения DOM
    }
}

function removeMetaTags($html) {
    // Создаем итератор
    $iterator = new MetaTagIterator($html);
    
    // Удаляем элементы title и все meta-теги
    while ($iterator->valid()) {
        $tag = $iterator->current();
        if ($tag->getAttribute('name') === 'description' || $tag->getAttribute('name') === 'keywords' || $tag instanceof DOMElement && $tag->nodeName === 'title') {
            $iterator->removeCurrent();
        }
        $iterator->next();
    }

    // Получаем измененный HTML
    return $iterator->getDOM()->saveHTML(); // Используем метод для доступа к DOM
}