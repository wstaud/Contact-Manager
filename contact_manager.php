<?php
// Shows intro and calls prompt() which prompts user for input
function startup() {
    echo "Contacts Manager" . PHP_EOL;
    echo "----------------------------" . PHP_EOL;
    prompt();
}

// Asks user for input (view contacts, search, add, delete or exit)
function prompt() {
    fwrite(STDOUT, PHP_EOL . "1: View Contacts" . PHP_EOL .  "2: Search Contacts" . PHP_EOL . "3: Add Contact" . PHP_EOL . "4: Delete Contact" . PHP_EOL . "0: Exit" . PHP_EOL);
    $answer = trim(fgets(STDIN));

    switch ($answer) {
        case "0":
            exit(0);
        case "1":
            displayContacts();
            break;
        case "2":
            searchContact();
            break;
        case "3":
            addContact();
            break;
        case "4":
            deleteContact();
            break;
        default:
            echo "Invalid Selection!";
            prompt();
            break;
    }
}

// Displays all the contacts in a human friendly formant.  
function displayContacts() {
    // call clearstatcache() in order to update file in cache in case user added or deleted a contact
    clearstatcache();
    echo "============" . PHP_EOL;

    $filename = "contacts.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    $contents = trim($contents);
    fclose($handle);
    $contactsArray = explode("\n", $contents);

    echo "Name      | Phone Number" . PHP_EOL;
    
    //Breaks apart the contacts further so that phone numbers may be formated to be human friendly. Calls formatNumber() to achieve this 
    foreach($contactsArray as $key => $contact) {
        $tempArray = explode("|", $contact);
        $result = array("name"  =>   $tempArray[0],
            "number" =>  $tempArray[1]);
        echo $tempArray[0] . " | " . formatNumber($tempArray[1]) . PHP_EOL;
    }
    prompt();
}

// Adds new contact to the address book
function addContact() {
    $filename = "contacts.txt"; 
    $handle = fopen($filename, 'a');
    
    fwrite(STDOUT, "First name?" . PHP_EOL);
    $firstName = trim(fgets(STDIN));
    
    fwrite(STDOUT, "Last Name?" . PHP_EOL);
    $lastName = trim(fgets(STDIN));

    fwrite(STDOUT, "Number?" . PHP_EOL);
    $number = trim(fgets(STDIN));

    fwrite($handle, $firstName . " " . $lastName . "|" . $number . PHP_EOL); 
    fclose($handle);
    prompt();

}

// Removes contact from contacts. Will use strict search to ensure user deletes right person. Rewrites the entire contacts file to prevent gaps.
function deleteContact() {
    $filename = "contacts.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    $contents = trim($contents); 
    // Turns txt file into array
    $contactsArray = explode("\n", $contents);
    $contactExist = false;
    
    fwrite(STDOUT, "Input full name of contact (Exact full name to ensure correct contact is deleted)" .  PHP_EOL);
    $delVal = trim(fgets(STDIN));

    foreach($contactsArray as $key => $contact) {
        $tempArray = explode("|", $contact);
        $result = array("name"  =>   $tempArray[0],
            "number" =>  $tempArray[1]);

        if($delVal === $tempArray[0] || $delVal === $tempArray[1]){
            echo "=====DELETE=====" . PHP_EOL;
            unset($contactsArray[$key]);
            fclose($handle);
            $writeHandle = fopen($filename, "w");
            $rewrite = implode("\n", $contactsArray) . PHP_EOL;
            fwrite($writeHandle, $rewrite);
            fclose($writeHandle);
            $contactExist = true;
        }
      
    }
    //Echos error if no contact was found
    if($contactExist === false) {
        echo "Error: No contact exists by that full name!" . PHP_EOL;
    }
    prompt();
}

// Allows user to search all contacts that fit loosly within their search results. It's case insensitive and can search partial strings
// Users can search full, first or last name... partially. Ex. "wil" may return William Staud
function searchContact() {
    clearstatcache();
    $filename = "contacts.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    $contents = trim($contents); 
    // Turns txt file into array
    $contactsArray = explode("\n", $contents);

    fwrite(STDOUT, "Search for contact:" . PHP_EOL);
    $searchVal = trim(fgets(STDIN));

    foreach($contactsArray as $contact) {
        $tempArray = explode("|", $contact);
        $result = array("name"  =>   $tempArray[0],
            "number" =>  $tempArray[1]);

        if(stristr($tempArray[0], $searchVal)!== false){
            echo "=====found=====" . PHP_EOL;
            echo $tempArray[0] . " | " . formatNumber($tempArray[1]) . PHP_EOL . PHP_EOL;
        }
    }
    fclose($handle);
    prompt();
}

// Takes standard phone number and adds dashes to them
function formatNumber($number) {
    $num1 = substr($number, 0, 3); 
    $num2 = substr($number, 3, 3); 
    $num3 = substr($number, 6, 4);
    $newNumber = $num1 . "-" . $num2 . "-" . $num3;

    return $newNumber;
 
}

// Call startup() on program launch
startup();

?>
