<?php
namespace MaikeNegreiros;

use MaikeNegreiros\PageBuilder;
use Ciebit\HpEngenharia\Views\View;
use Ciebit\HpEngenharia\Models\Model;

class DynamicPage extends PageBuilder
{
    private $model_element_selector = '.model-element';
    private $View; #View
    private $Model; #Model

    public function __construct(View $view, Model $model)
    {
        $this->View = $view;
        $this->Model = $model;
    }

    public function buildHtml(): self
    {
        $document = $this->getTemplateDocument();
        $container = $document->querySelector($this->selectorNodeWhereToImport);
        $document2 = $this->View->getDocumentUpdated();

        $tagStyle =  $document->importNode($this->View->importTagStyleUpdated(), true);
        $document->querySelector('body')->appendChild($tagStyle);

        $content = $document2->querySelector($this->selectorNodeToImport);
        $fragment = $document->importNode($content, true);
        $container->appendChild($fragment);

        $this->importMetaTagsAndTitle('title');
        $this->importMetaTagsAndTitle('[name=description]');
        $this->importMetaTagsAndTitle('[name=keywords]');
        $this->importStylesAndScripts();

        $this->output = $document->saveHTML();

        return $this;
    }

    public function getModelElementSelector(): string
    {
        return $this->model_element_selector;
    }

    public function setModelElementSelector(string $selector): self
    {
        $this->model_element_selector = $selector;
        return $this;
    }
}
