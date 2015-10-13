<?php
include("utilities.php");
if(isset($_GET["noteID"]))
{
	$xml = getXML("note.xml");
	
	$status = $_GET['status'];
		
	// get document element
	$root = $xml->documentElement;
	$notes = $root->childNodes->item(3);
	
	foreach($notes->childNodes as $note) 
	{
		if($note->getAttribute('id')==$_GET['noteID'])
		{
			replaceNode($xml, "messageStatus", $status, $note, $note->childNodes->item(6));
			$xml->save("note.xml");
		}
	}
}
?>