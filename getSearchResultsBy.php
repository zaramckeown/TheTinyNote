<?php

include("utilities.php");

function displayData($noteID, $titleNode, $senderTitleNode, $senderForenameNode ,$senderSurnameNode, $recipientsTitleNode, $recipientsForenameNode, $recipientsSurnameNode, $dateNode,$messageNode, $urlNode, $statusNode)
{
   echo ("<tr id='$noteID'> <td>".$noteID."</td>"); 
   echo ("<td>".$titleNode->nodeValue."</td>"); 
   echo ("<td>".$senderTitleNode->nodeValue." ".$senderForenameNode->nodeValue." ".$senderSurnameNode->nodeValue."</td>");
   echo ("<td>".$recipientsTitleNode->nodeValue." ".$recipientsForenameNode->nodeValue." ".$recipientsSurnameNode->nodeValue."</td>");
   echo ("<td>".$dateNode->nodeValue."</td>");
   echo ("<td id='status_$noteID'>".$statusNode->nodeValue."</td>");
   echo ("<td><select id='editStatus' onchange='editStatus($noteID,this.value)'>");
   echo ("<option value=''>Change Status</option>");
   echo ("<option value='new'>New</option>");
   echo ("<option value='current'>Current</option>");
   echo ("<option value='historic'>Historic</option>");
   echo ("</select><td>");
   echo ("<td> <input type='button' value='View' onclick='viewNote($noteID)'> </td>");
   echo ("<td> <input type='button' value='Delete' onclick='deleteNote($noteID)'> </td> </tr>");
}

if(isset($_GET['result']))
{   
   $result = $_GET["result"];
   
   $xml=getXML("note.xml");
   
   // get document element
   $root = $xml->documentElement;
   $notes = $root->childNodes->item(3);
   
   $notesFound = 0;
   
   echo ("<table id='displayNoteResults'>");    
   
   echo ("<tr> <th>Note ID</th> <th>Title</th> <th>Sender</th> <th>Recipent</th> <th>Date</th> <th>Status</th> </tr>");
 		
   foreach($notes->childNodes as $note) 
   {
   	   $notesFound++;
	   $noteID = $note->getAttribute('id');
	   
	   //title
	   $titleNode = $note->childNodes->item(0); 
	   
	   //sender
	   $noteSenderNode = $note->childNodes->item(1); 
	   $senderID = $noteSenderNode->getAttribute('id');
	   $senderTitleNode = $noteSenderNode->childNodes->item(0); 
	   $senderForenameNode = $noteSenderNode->childNodes->item(1); 
	   $senderSurnameNode = $noteSenderNode->childNodes->item(2);
	   
	   //date
	   $dateNode = $note->childNodes->item(3);
	
	   //url
	   $urlNode = $note->childNodes->item(4);
	   
	   //message
	   $messageNode = $note->childNodes->item(5); 
	
	   //status
	   $statusNode = $note->childNodes->item(6); 
	   
	   //recipient 
	   $noteRecipientsNode = $note->childNodes->item(2); 
	   
	   foreach($noteRecipientsNode->childNodes as $recipients)
	   {
	   	   $recipientID = $recipients->getAttribute('id');
		   $recipientsTitleNode = $recipients->childNodes->item(0); 
		   $recipientsForenameNode = $recipients->childNodes->item(1); 
		   $recipientsSurnameNode = $recipients->childNodes->item(2);  
		   
		   if($recipientID==$result)
		   {
			  displayData($noteID, $titleNode, $senderTitleNode, $senderForenameNode ,$senderSurnameNode, $recipientsTitleNode, $recipientsForenameNode, $recipientsSurnameNode, $dateNode,$messageNode, $urlNode, $statusNode);

		   }
	   }
	   
	   if ($noteID==$result || $senderID==$result || $statusNode->nodeValue==$result) 
	   {
			displayData($noteID, $titleNode, $senderTitleNode, $senderForenameNode ,$senderSurnameNode, $recipientsTitleNode, $recipientsForenameNode, $recipientsSurnameNode, $dateNode,$messageNode, $urlNode, $statusNode);
	   }
	  	  
   }
   if($notesFound==0) echo("</br> No notes found");
   
   echo("</table>");   
}
?>