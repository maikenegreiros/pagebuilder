<?php
namespace MaikeNegreiros;

use \Gt\Dom\HTMLDocument;

class PageBuilder
{
    private $template = __DIR__."/../layouts/menu.html";
    private $layouts; #Array<string>
    private $tagsToImport = ".export";
    private $selectorNodeToImport = ".content";
    private $selectorNodeWhereToImport = ".content";
    private $templateDocument; #HTMLDocument
    private $layoutDocument; #HTMLDocument

    public function getHtml(): string
    {
        return $this->output;
    }

    public function buildHtml(): self
    {
        $document = $this->getTemplateDocument();
        $container = $document->querySelector($this->selectorNodeWhereToImport);

        for ($i=0; $i < count($this->layouts); $i++) {
            $document2 = $this->getLayoutDocument($this->layouts[$i]);
            $content = $document2->querySelector($this->selectorNodeToImport);
            $fragment = $document->importNode($content, true);

            $container->appendChild($fragment);
        }

        $this->importMetaTagsAndTitle('title');
        $this->importMetaTagsAndTitle('[name=description]');
        $this->importMetaTagsAndTitle('[name=keywords]');
        $this->importStylesAndScripts();

        $this->output = $document->saveHTML();

        return $this;
    }

    private function importStylesAndScripts(): self
    {
        $document = $this->getTemplateDocument();
        $head = $document->querySelector("head");

        for ($i=0; $i < count($this->layouts); $i++) {
            $document2 = $this->getLayoutDocument($this->layouts[$i]);
            $tagsToImport = $document2->querySelectorAll($this->tagsToImport);

            for ($x=0; $x < count($tagsToImport); $x++) {
                $head->appendChild($document->importNode($tagsToImport[$x], true));
            }
        }

        return $this;
    }

    private function importMetaTagsAndTitle(string $selector): self
    {
        $document = $this->getTemplateDocument();
        $head = $document->querySelector("head");
        $current_tag = $document->querySelector($selector);
        if ($current_tag) {
            $head->removeChild($current_tag);
        }

        $document2 = $this->getLayoutDocument($this->layouts[0]);
        $new_tag = $document2->querySelector($selector);

        if ($new_tag) {
            $head->appendChild($document->importNode($new_tag, true));
        }

        return $this;
    }

    private function getTemplateDocument(): HTMLDocument
    {
        if ($this->templateDocument) {
            return $this->templateDocument;
        }
        $html = file_get_contents($this->template);
        $this->templateDocument = new HTMLDocument($html);

        return $this->templateDocument;
    }

    private function getLayoutDocument($path): HTMLDocument
    {
        $html = file_get_contents($path);
        $this->layoutDocument = new HTMLDocument($html);

        return $this->layoutDocument;
    }

    public function setLayout(string $path): self
    {
        if (! $this->layouts) {
            $this->layouts = array();
        }
        array_push($this->layouts, $path);
        return $this;
    }

    public function setTemplate(string $path): self
    {
        $this->template = $path;
        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setSelectorTagsToImport(string $selector): self
    {
        $this->tagsToImport = $selector;
        return $this;
    }

    public function getSelectorTagsToImport(): string
    {
        return $this->tagsToImport;
    }

    public function setSelectorNodeToImport(string $selector): self
    {
        $this->selectorNodeToImport = $selector;
        return $this;
    }

    public function getSelectorNodeToImport(): string
    {
        return $this->selectorNodeToImport;
    }

    public function setSelectorNodeWhereToImport(string $selector): self
    {
        $this->selectorNodeWhereToImport = $selector;
        return $this;
    }

    public function getSelectorNodeWhereToImport(): string
    {
        return $this->selectorNodeWhereToImport;
    }
}
