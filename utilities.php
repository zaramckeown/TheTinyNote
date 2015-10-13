<?php

// a function to open the XML file, read it and return the XML DOM Object
function getXML($file) 
{
   $fp = fopen($file, "rb") or die("cannot open file");
   $str = fread($fp, filesize($file));
   $xml = new DOMDocument();
   $xml->formatOutput = true;
   $xml->preserveWhiteSpace = false;
   $xml->loadXML($str) or die("Error");
   return $xml;
}

// a function to replace a node in the XML tree, given the DOM Object, 
// the tag name to use, the new text value, the parent node and the current node
function replaceNode($xml, $tagName, $textValue, $parentNode, $nodeToReplace)
{
   $newNode=$xml->createElement("$tagName");
   $newTextNode=$xml->createTextNode("$textValue");
   $newNode->appendChild($newTextNode);
   $parentNode->replaceChild($newNode,$nodeToReplace);
}

function addNode($xml, $elementName, $elementText)
{
   $newNode = $xml->createElement("$elementName");
   $newTextNode = $xml->createTextNode("$elementText");
   $newNode->appendChild($newTextNode);
   
   return $newNode;
}

?>