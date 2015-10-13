<?php
	
   include("utilities.php");
   
   $result = $_GET["result"];
   
   $xml=getXML("note.xml");
   
   // get document element
   $root = $xml->documentElement;
   $notes = $root->childNodes->item(3);
   
   $notesFound = 0;

   if($result=="id")
   {
       echo ("<select id='searchOptions' onchange='showSearchResults(this.value)'> ");
       echo ("<option value=''>Choose an ID</option>");
      
	   foreach($notes->childNodes as $note) 
	   {	
	   	   $notesFound++;
		   $noteID = $note->getAttribute('id');
			
		   echo ("<option value='$noteID'>Note ID: $noteID</option>");
	   }
	        
	   echo ("</select>");
	   if($notesFound==0) echo("</br> No notes found");
   }
   
   else if($result=="sender")
   {
       echo ("<select id='searchOptions' onchange='showSearchResults(this.value)'> ");
       echo ("<option value=''>Choose a Sender</option>");
       
       $senderList = array();
       
	   foreach($notes->childNodes as $note) 
	   {	
	   	   $notesFound++;
		   $noteSenderNode = $note->childNodes->item(1); 
		   $senderID = $noteSenderNode->getAttribute('id');
		   $senderTitleNode = $noteSenderNode->childNodes->item(0); 
		   $senderForenameNode = $noteSenderNode->childNodes->item(1); 
		   $senderSurnameNode = $noteSenderNode->childNodes->item(2);
      
		   if(!array_key_exists($senderID, $senderList))
		   {
			   $senderList [$senderID] = $senderTitleNode->nodeValue." ".$senderForenameNode->nodeValue." ".$senderSurnameNode->nodeValue;
		   }
	   }
	  
	   foreach($senderList as $key=>$value)
	   {
	   	  echo ("<option value='$key'>Sender: $value</option>");
	   }
	   
	   echo ("</select>");
	   if($notesFound==0) echo("</br> No notes found");
   }
     
   else if($result=="recipient")
   {
       echo ("<select id='searchOptions' onchange='showSearchResults(this.value)'> ");
       echo ("<option value=''>Choose a Recipient</option>");
		 
	   $recipientList=array();

	   foreach($notes->childNodes as $note) 
	   {
	   	   $notesFound++;
		   $noteRecipientsNode = $note->childNodes->item(2);
		    
		   foreach($noteRecipientsNode->childNodes as $recipients)
		   {
		   	   $recipientID = $recipients->getAttribute('id');
			   $recipientsTitleNode = $recipients->childNodes->item(0); 
			   $recipientsForenameNode = $recipients->childNodes->item(1); 
			   $recipientsSurnameNode = $recipients->childNodes->item(2); 
			   			   
			   if(!array_key_exists($recipientID, $recipientList))
		   	   {
		   	      $recipientList [$recipientID] = $recipientsTitleNode->nodeValue." ".$recipientsForenameNode->nodeValue." ".$recipientsSurnameNode->nodeValue;
			   }
		   }
	   }
	   
	   foreach($recipientList as $key=>$value)
	   {
		   echo ("<option value='$key'>Recipients: $value</option>");
	   }
	   
	   echo ("</select>");
	   
	   if($notesFound==0) echo("</br> No notes found");
   }
   
   else if($result=="status")
   {
       echo ("<select id='searchOptions' onchange='showSearchResults(this.value)'> ");
       echo ("<option value=''>Choose a Status</option>");
	   echo ("<option value='new'>Status: New </option>");
	   echo ("<option value='current'>Status: Current </option>");
	   echo ("<option value='historic'>Status: Historic </option>");
	   echo ("</select>");
   }
   
?>
