<?php
namespace MaikeNegreiros;

use MaikeNegreiros\PageBuilder;

class StaticPage extends PageBuilder
{
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
}
