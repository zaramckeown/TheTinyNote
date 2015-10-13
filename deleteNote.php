<?php

   include("utilities.php");
   
   $noteID = $_GET["noteID"];
   $xml=getXML("note.xml");

   // get document element
   $root = $xml->documentElement;
   $notes = $root->childNodes->item(3);
   
   // find node and make the change
   foreach($notes->childNodes as $note) 
   {
      if ($note->getAttribute('id')==$noteID) 
      { 
          $notes->removeChild($note);
      }
   }

   $xml->save("note.xml");  
?>