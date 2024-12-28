<?php

function removeMetaTags($html) {
    // Создаем новый объект DOMDocument
    $dom = new DOMDocument();

    // Загружаем HTML-код. Используем @ для подавления ошибок.
    @$dom->loadHTML($html);

    // Удаляем мета-теги
    $tagsToRemove = ['title', 'meta[name="description"]', 'meta[name="keywords"]'];

    foreach ($tagsToRemove as $tag) {
        $elements = $dom->getElementsByTagName($tag);
        while ($elements->length > 0) {
            $elements->item(0)->parentNode->removeChild($elements->item(0));
        }
    }

    // Получаем измененный HTML
    return $dom->saveHTML();
}

?>