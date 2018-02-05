<?php
namespace MaikeNegreiros;

use \Gt\Dom\HTMLDocument;

abstract class PageBuilder
{
    protected $template = __DIR__."/../../../../layouts/menu.html";
    protected $layouts; #Array<string>
    protected $tagsToImport = ".export";
    protected $selectorNodeToImport = ".content";
    protected $selectorNodeWhereToImport = ".content";
    protected $templateDocument; #HTMLDocument
    protected $layoutDocument; #HTMLDocument
    protected $output; #string

    public function getHtml(): string
    {
        return $this->output;
    }

    protected function importStylesAndScripts(): self
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

    protected function importMetaTagsAndTitle(string $selector): self
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

    protected function getTemplateDocument(): HTMLDocument
    {
        if ($this->templateDocument) {
            return $this->templateDocument;
        }
        $html = file_get_contents($this->template);
        $this->templateDocument = new HTMLDocument($html);

        return $this->templateDocument;
    }

    protected function getLayoutDocument(string $content): HTMLDocument
    {
        $this->layoutDocument = new HTMLDocument($content);

        return $this->layoutDocument;
    }

    public function setLayout(string $path): self
    {
        if (! $this->layouts) {
            $this->layouts = array();
        }
        array_push($this->layouts, file_get_contents($path));
        return $this;
    }

    public function setLayoutbyContent(string $content): self
    {
        if (! $this->layouts) {
            $this->layouts = array();
        }
        array_push($this->layouts, $content);
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
