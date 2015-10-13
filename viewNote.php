<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>The Tiny Note</title>

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
    
<section id="displayNote">

	<div class="container">


<?php

include("utilities.php");

if(isset($_GET['noteID']))
{
   
    $xml=getXML("note.xml");
    $root = $xml->documentElement;
    $notes = $root->childNodes->item(3);

	foreach($notes->childNodes as $note) 
	{
		$noteID = $note->getAttribute('id');
		
		if($noteID==$_GET['noteID'])
		{
			//title
			$titleNode = $note->childNodes->item(0); 
			
			//sender
			$noteSenderNode = $note->childNodes->item(1); 
			$senderTitleNode = $noteSenderNode->childNodes->item(0); 
			$senderForenameNode = $noteSenderNode->childNodes->item(1); 
			$senderSurnameNode = $noteSenderNode->childNodes->item(2);
			
			//date
			$dateNode = $note->childNodes->item(3);
			
			//url
			$urlNode = $note->childNodes->item(5);
			
			//message
			$messageNode = $note->childNodes->item(4); 
			
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
			}
		}
	}
		
	echo ("<h2>Note $noteID</h2>");
	echo ("<div id='maincontent'");
	echo ("<table id='alignment'>");    
	echo ("<tr> <td> <div id='title'>".$titleNode->nodeValue."</div> </td> </tr>"); 
	echo ("<tr> <td> <div id='date'> ".$dateNode->nodeValue."</div> </td>");
	echo ("<tr> <td> <div id='sender'>".$senderTitleNode->nodeValue." ".$senderForenameNode->nodeValue." ".$senderSurnameNode->nodeValue."</div> </td> </tr>");
	echo ("<tr> <td> <div id='recipient'>".$recipientsTitleNode->nodeValue." ".$recipientsForenameNode->nodeValue." ".$recipientsSurnameNode->nodeValue."</div> </td> </tr>");
	echo ("<tr> <td> <div id='message'> Message:</div> </td> </tr>"); 
	echo ("<tr> <td> <div id='message'>".$messageNode->nodeValue."</div> </td> </tr>"); 
	echo ("<tr> <td> <div id='url'> <a href=".$urlNode->nodeValue.">Click Here</a> </div> </td> </tr>");
	echo ("<tr> <td> <div id='status'>".$statusNode->nodeValue." </div> </td> </tr>");
	echo ("</table> </div>");    
}

?>
	</div>
	
</section>

</body>

</html>


