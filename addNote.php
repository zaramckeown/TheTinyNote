<?php

include("utilities.php");
    
$xml = getXML("note.xml");

// get document element
$root = $xml->documentElement;
$notes = $root->childNodes->item(3);

//autofill date
$date = date("Y/m/d");

if (isset($_POST["submit"])) 
{	
   header('location: browseNote.html');
   // find first note element
   $firstNote = $notes->childNodes->item(0);

   //get values for IDs
   $noteID = (int) $root->childNodes->item(0)->nodeValue;
   $senderID = (int) $root->childNodes->item(1)->nodeValue;
   $recipientID = (int) $root->childNodes->item(2)->nodeValue;
      
   $noteTitle = $_POST["noteTitle"];
  
   $message = $_POST["message"];
   $url = $_POST["url"];
   $messageStatus = "new"; 
      
   //If a new sender has been entered
   if(isset($_POST['senderForename']))
   {	
	   $senderTitle = $_POST["senderTitle"];
	   $senderForename = $_POST["senderForename"];
	   $senderSurname = $_POST["senderSurname"];
	   
       // create the Sender element
	   $senderNode = $xml->createElement("sender");
	   $senderNode->setAttribute("id","senders_".$senderID);
	 	
	   //adding nodes to sender 
	   $senderNode->appendChild(addNode($xml, "title", $senderTitle));
	   $senderNode->appendChild(addNode($xml, "forename", $senderForename));
       $senderNode->appendChild(addNode($xml, "surname", $senderSurname));
		  
	   //replacing sender id with incremented one.
       $senderID+=1;     
       replaceNode($xml, "senderID", $senderID, $root, $root->childNodes->item(1));
   }
   
   //Existing sender has been entered
   if(isset($_POST['existingSenderID']))
   {
       $senderList=array();
      	 
   	   foreach($notes->childNodes as $note) 
	   {	   
		   $noteSenderNode = $note->childNodes->item(1); 
		   
		   $existingSenderID = $noteSenderNode->getAttribute('id');
		   $titleNode = $noteSenderNode->childNodes->item(0); 
		   $forenameNode = $noteSenderNode->childNodes->item(1); 
		   $surnameNode = $noteSenderNode->childNodes->item(2);
		   
		   if(!in_array($existingSenderID, $senderList))
		   {
		   	   array_push($senderList, $existingSenderID);	
		   	   
		   	   if(in_array($existingSenderID, $_POST['existingSenderID']))
		       {		   	      
			   	   // create the Sender element
				   $senderNode = $xml->createElement("sender");
				   $senderNode->setAttribute("id", $existingSenderID);				
				
				   //adding nodes to sender 
				   $senderNode->appendChild(addNode($xml, "title", $titleNode->nodeValue));
				   $senderNode->appendChild(addNode($xml, "forename", $forenameNode->nodeValue));
			       $senderNode->appendChild(addNode($xml, "surname", $surnameNode->nodeValue));
				}
		   }
	  }
   }

   // create the recipients element
   $recipientsNode = $xml->createElement("recipients");
    
   //to see if a new recipient has been added.
   if(isset($_POST["recipientTitle"]))
   {
	    $recipientTitle = $_POST["recipientTitle"];
	    $recipientForename = $_POST["recipientForenames"];
	    $recipientSurname =  $_POST["recipientSurname"];
	   		
		for($i=0; $i<count($recipientTitle); $i++)
		{
			$recipientNode = $xml->createElement("recipient");  
			
			$recipientNode->setAttribute("id","recipients_".$recipientID);
			
			//incrementing each time it runs.
			$recipientID+=1;
						
			//adding nodes to recipient
			$recipientNode->appendChild(addNode($xml, "title", $recipientTitle[$i]));
			$recipientNode->appendChild(addNode($xml, "forename", $recipientForename[$i]));
			$recipientNode->appendChild(addNode($xml, "surname", $recipientSurname[$i]));
			
			//creating recipients node and appending recipient
			$recipientsNode->appendChild($recipientNode); 
		} 
		
		
	    replaceNode($xml, "recipientID", $recipientID, $root, $root->childNodes->item(2));
   }

      
   //if an existing recipient has been chosen. Finding previous details and adding to new note. 
   if(isset($_POST["existingRecipientID"]))
   {
   	 $recipientList=array();
   	 
	   foreach($notes->childNodes as $note) 
	   {
		   $noteRecipientsNode = $note->childNodes->item(2);
		   
		   foreach($noteRecipientsNode->childNodes as $recipients)
		   {
		   	   $recipientExistingID = $recipients->getAttribute('id');
		   	   $recipientsTitleNode = $recipients->childNodes->item(0); 
			   $recipientsForenameNode = $recipients->childNodes->item(1); 
			   $recipientsSurnameNode = $recipients->childNodes->item(2); 
		
			   if(!in_array($recipientExistingID, $recipientList))
		   	   {
			   	  array_push($recipientList, $recipientExistingID);		   	      
		   	      $recipientPostedID = $_POST["existingRecipientID"];
		   	      
		   	      if(in_array($recipientExistingID, $recipientPostedID))
		   		  {		
			   	  	  $recipientNode = $xml->createElement("recipient");  
		 
					  $recipientNode->setAttribute("id",$recipientExistingID);
					
					  //adding nodes to recipient
					  $recipientNode->appendChild(addNode($xml, "title", $recipientsTitleNode->nodeValue));
					  $recipientNode->appendChild(addNode($xml, "forename", $recipientsForenameNode->nodeValue));
					  $recipientNode->appendChild(addNode($xml, "surname", $recipientsSurnameNode->nodeValue));

					  //creating recipients node and appending recipient
					  $recipientsNode->appendChild($recipientNode);
		   		  }
		   	   }
		   	}
	    }	   	
    }

   //replacing note id with incremented one.
   $noteID+=1;
   replaceNode($xml, "nextID", $noteID, $root, $root->childNodes->item(0));
     
   // create the Note
   $newNoteNode = $xml->createElement("note");
   $newNoteNode->setAttribute("id",$noteID);

   // create the note title element
   $newNoteNode->appendChild(addNode($xml, "title", $noteTitle));
   
   $newNoteNode->appendChild($senderNode);
   $newNoteNode->appendChild($recipientsNode);
      
   //creating the date element
   $newNoteNode->appendChild(addNode($xml, "date", $date));
   
   //creating the message element
   $newNoteNode->appendChild(addNode($xml, "message", $message));
      
   //creating the url element
   $newNoteNode->appendChild(addNode($xml, "url", $url));
   
   //creating the messageStatus element
   $newNoteNode->appendChild(addNode($xml, "messageStatus", $messageStatus));
  
   // add new note to the data set
   $notes->insertBefore($newNoteNode,$firstNote);

   $xml->save("note.xml");
        
} else {
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="css/agency.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
<link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

<title>The Tiny Note</title>

</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-shrink">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="index.html">The Tiny Note</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="addNote.php">Add a Note</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="browseNote.html">Browse Notes</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="sortNote.html">Sort Notes</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

<script type="text/javascript">

var recipientCount = 1;
var recipientDynamicCount = 1;
var alreadyAddedRecipients = []; 
var senderCount = 0;

$(document).ready(function()
{	
	//hid until sender has been added.
	$('#senderDeleteButton').hide();
	$('#receipientDeleteButton').hide();	
});

function validation()
{  
	var theForm = document.getElementById("addNoteForm");
	var recipientForenamesContainsCheck = theForm.recipientForenames;
	var senderTitle = theForm.senderTitle;
	var senderForename = theForm.senderForename;
	var senderSurname = theForm.senderSurname;
	var recipientTitle = document.getElementsByName("recipientTitle[]");
	var recipientForenames = document.getElementsByName("recipientForenames[]");
	var recipientSurname = document.getElementsByName("recipientSurname[]");
	var title = theForm.title.value;
	var message = theForm.message.value;
			
	if(title == "")
	{
		alert("Please enter a subject");
		return false;
	}
	
	if(senderCount==0)
	{
		alert("Please enter a sender");
		return false;
	}
	
	if(document.contains(senderTitle))
	{
		if(senderTitle.value == "" && senderForename.value == "" && senderSurname.value == "")
		{
			alert("Please enter complete sender details");
			return false;
		}
	}
	
	if(document.contains(recipientForenamesContainsCheck))
	{
		//looping though array of recipient titles
		for (var i = 0; i < recipientTitle.length; i++)
		{
			if (recipientTitle[i].value=="")
			{ 
				alert("Please complete recipient title");
 
				return false;
			}
		}
		
		//looping though array of recipient forenames
		for (var i = 0; i < recipientForenames.length; i++)
		{
			if (recipientForenames[i].value=="")
			{ 
				alert("Please complete recipient forename");
 
				return false;
			}
		}
		
		//looping though array of recipient forenames
		for (var i = 0; i < recipientSurname.length; i++)
		{
			if (recipientSurname[i].value=="")
			{ 
				alert("Please complete recipient surname");
 
				return false;
			}
		}
	}
	
	if(recipientCount == 1)
	{
		alert("Please add a recipient");
		return false;
	}
		
	if(message == "")
	{
		alert("Please enter a message");
		return false;
	}		
}

//functions for recipient;
function addInput(divName)
{
	//no more than 5 recipients can be added to a note.
   	if(recipientCount<=5)
   	{
	    var newdiv = document.createElement('div'); 	 
	   	newdiv.id = "recipientCount"+recipientCount;
        newdiv.innerHTML = "<tr><br>Recipient "+ recipientCount + ":</tr><td><br><br>Title: <select id='recipientTitle' name='recipientTitle[]'><option value=''>Choose a Title</option><option value='Mr'>Mr</option> <option value='Mrs'>Mrs</option> <option value='Miss'>Miss</option></select></td> <td>  Forename:  <input id='recipientForenames' type='text' name='recipientForenames[]'><td>  Surname: <input id='recipientSurname' type='text' name='recipientSurname[]'><br></td>";
            
        document.getElementById(divName).appendChild(newdiv);
      
        recipientCount++;
        
		$('#receipientDeleteButton').show();
   	}
   	
   	else
   	{
	   	alert("Maximum of 5 recipients has been reached.");
   	}
     
}

function gettingRecipientFromSelect(divName)
{
	var gettingSelect = document.getElementById('recipientsList');
	var gettingValue = gettingSelect.options[gettingSelect.selectedIndex].value;	
	var gettingText = gettingSelect.options[gettingSelect.selectedIndex].text;
	var verify = true;
	
	for(var i=0; i<alreadyAddedRecipients.length; i++)
	{
		if(alreadyAddedRecipients[i]==gettingValue)
		{
			alert("You have already added this recipient");
			verify = false;
		}
	}	
	
	if (verify)
	{
    	alreadyAddedRecipients.push(gettingValue);
    	
    	//no more than 5 recipients can be added to a note.
	   	if(recipientCount<=5)
	   	{
			var newdiv = document.createElement('div');
		    newdiv.id = "recipientCount"+recipientCount;
		    newdiv.innerHTML = "<tr><br>Existing Recipient "+ recipientCount + ":<td><input type='hidden' value='"+gettingValue+"' name='existingRecipientID[]'><br></td><br> <td>Name: <input type='text' value='"+gettingText+"'readonly><br></td><br></td></tr>";
		     
		    document.getElementById(divName).appendChild(newdiv);
		    
		    recipientCount++;
		    
		    $('#receipientDeleteButton').show();
		}
	   	
	   	else
	   	{
		   	alert("Maximum of 5 recipients has been reached.");
	   	}
	}
	
	resetSelectElement(gettingSelect);
}


//adding in sender
function addingInputFieldForSender(divName)
{
	if(senderCount<1)
	{
		var newdiv = document.createElement('div');
		newdiv.id = 'senderDiv';
	    newdiv.innerHTML = "<tr><td><br>Title: <select name='senderTitle'><option value=''>Choose a Title</option><option value='Mr'>Mr</option><option value='Mrs'>Mrs</option> <option value='Miss'>Miss</option></select></td><td>  Forename: <input type='text' name='senderForename'></td> <td>  Surname: <input type='text' name='senderSurname'></td></tr>";
	            
	  document.getElementById(divName).appendChild(newdiv);
	  
	  senderCount++;
	  
	  $('#senderDeleteButton').show();
	}
	
	else
	{
		alert("Maximum of 1 sender");
	}
}

function gettingSenderFromSelect(divName)
{
	var gettingSelect = document.getElementById("senderList");
	var gettingValue = gettingSelect.options[gettingSelect.selectedIndex].value;	
	var gettingText = gettingSelect.options[gettingSelect.selectedIndex].text;
	
	//no more than 1 sender can be added to a note.
	if(senderCount<1)
	{
		var newdiv = document.createElement('div');
		newdiv.id = 'senderDiv';
	    newdiv.innerHTML = "<tr><td><input id='sender' type='hidden' value='"+gettingValue+"' name='existingSenderID[]'><br></td> <td>Sender Name: <input type='text' value='"+gettingText+"'readonly><br></td><br></td></tr>";
	      
	    document.getElementById(divName).appendChild(newdiv);
	    
	    senderCount++;
	    
	    $('#senderDeleteButton').show();
	}
	   	
	else
	{
		alert("Maximum of 1 sender has been reached.");
	}
	
	resetSelectElement(gettingSelect);
}

//resetting the select element back to the default.
function resetSelectElement(selectElement) 
{
    selectElement.selectedIndex = 0;
}


function removeLastAddedSenderElement() 
{

  var div = document.getElementById('senderInputFields');//Parent Div ID
  var elem = document.getElementById('senderDiv');
  div.removeChild(elem);

  senderCount--;
  
  $('#senderDeleteButton').hide();
}

function removeLastAddedRecipientElement() 
{

  recipientCount--;
  var div = document.getElementById('dynamicInput');//Parent Div ID
  var elemName = 'recipientCount' + recipientCount;
  var elem = document.getElementById(elemName);
  div.removeChild(elem); 
  
  alreadyAddedRecipients.pop();	
    
  if(recipientCount==1)
  {
  	$('#receipientDeleteButton').hide();
  }

}


</script>


<section id="addNote">
	<div class="container">
		<h1>Adding a new note</h1>

		<form name="addNoteForm" id="addNoteForm" method="post" action="addNote.php" onsubmit="return validation()">
			<table>
				<tr>
					<td>Subject: <input id="title" type="text" name="noteTitle"><br><br></td>
				</tr>
			    
				<tr><th>Sender Details</th><br></tr>
			
				<tr>
					<td> 
					<br>
						<select id="senderList" onchange="gettingSenderFromSelect('senderInputFields')"> 
							<option value=''>Choose an Existing Sender</option>
		
<?php
	$senderList = array();
	
	foreach($notes->childNodes as $note) 
	{
	   $noteSenderNode = $note->childNodes->item(1); 
	   $gettingSenderID = $noteSenderNode->getAttribute('id');
	   $senderTitleNode = $noteSenderNode->childNodes->item(0); 
	   $senderForenameNode = $noteSenderNode->childNodes->item(1); 
	   $senderSurnameNode = $noteSenderNode->childNodes->item(2);
	
	   if(!array_key_exists($gettingSenderID, $senderList))
	   {
		   $senderList [$gettingSenderID] = $senderTitleNode->nodeValue." ".$senderForenameNode->nodeValue." ".$senderSurnameNode->nodeValue;
	   }
	
	}
	
	foreach($senderList as $key=>$value)
	{
		  echo ("<option value='$key'>$value</option>");
	}
	   	   
	?>					</select>
						
						OR
					
						<input id="senderButton" type="button" value="Add a New Sender" onClick="addingInputFieldForSender('senderInputFields');">
					
						<input id="senderDeleteButton" type="button" value="Delete Sender" onclick="removeLastAddedSenderElement();">
					</td>
			
				</tr>

			
			<tr>
				<td colspan=3><div id="senderInputFields"></div></td>
			</tr>

			<tr>
				<th><br>Recipient Details</th>
			</tr>
			
			<tr>
				<td> 
					<br>
					<select id="recipientsList" onchange="gettingRecipientFromSelect('dynamicInput')"> 
					 <option value=''>Choose an Existing Recipient</option>
		
<?php		 
   $recipientList=array();

   foreach($notes->childNodes as $note) 
   {
	   $noteRecipientsNode = $note->childNodes->item(2);
	    
	   foreach($noteRecipientsNode->childNodes as $recipients)
	   {
	   	   $recipientListID = $recipients->getAttribute('id');
		   $recipientsTitleNode = $recipients->childNodes->item(0); 
		   $recipientsForenameNode = $recipients->childNodes->item(1); 
		   $recipientsSurnameNode = $recipients->childNodes->item(2); 
		   			   
		   if(!array_key_exists($recipientListID, $recipientList))
	   	   {
	   	      $recipientList [$recipientListID] = $recipientsTitleNode->nodeValue." ".$recipientsForenameNode->nodeValue." ".$recipientsSurnameNode->nodeValue;
		   }
	   }
   }
   
   foreach($recipientList as $key=>$value)
   {
	   echo ("<option value='$key'>$value</option>");
   }
   
?>
						</select>
						OR
				  		<input id="newRecipientButton" type="button" value="Add a New Recipient" onClick="addInput('dynamicInput');">	
				  		<input id="receipientDeleteButton" type="button" value="Delete Recipient" onclick="removeLastAddedRecipientElement();">
					</td>
			  </tr>
			  
			  <tr>
					<td colspan=3><div id="dynamicInput"></div></td>
			  </tr>

			  <tr>
			 	    <td><br>Date: <input type="text" name="date" value="<?php echo($date); ?>" readonly><br><br></td>
			  </tr>

			  <tr>
				  	<td>Message: </br> <textarea cols="50" rows="4" name="message"></textarea> </br></br></td>
		      </tr>

			  <tr>
				    <td>URL: <input type="url" name="url" type="url" placeholder="http://www.google.com"><br><br></td>
			  </tr>

			  <tr>	
				   <td><input type="submit" name="submit" value="Add Note"></td>
			  </tr>
		 </table>
	  </form>
    </div>
  </section>
</body>

</html>
<?php
}
?>