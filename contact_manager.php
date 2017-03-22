<?php

function startup() {
    echo "Contacts Manager" . PHP_EOL;
    echo "----------------------------" . PHP_EOL;
    prompt();
}


function prompt() {
    fwrite(STDOUT, "0: Exit" . PHP_EOL . "1: View Contacts" . PHP_EOL .  "2: Search Contacts" . PHP_EOL . "3: Add Contact" . PHP_EOL . "4: Delete Contact" . PHP_EOL);
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

function displayContacts() {
    echo "============" . PHP_EOL;
    $filename = "contacts.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    $contents = trim($contents);
    fclose($handle);
    echo $contents . PHP_EOL;

    prompt();
}

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

function deleteContact() {

}

function searchContact() {
    $filename = "contacts.txt";
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    $contents = trim($contents); 
    // Turns txt file into array
    $contactsArray = explode("\n", $contents);

    foreach($contactsArray as $contact) {
        $tempArray = explode("|", $contact);
        $result = array("name"  =>   $tempArray[0],
            "number" =>  $tempArray[1]);
        print_r($result);
    }
    

    fwrite(STDOUT, "Search for contact:" . PHP_EOL);
    $searchVal = trim(fgets(STDIN));

    $result = array_search($searchVal, $contactsArray);

    var_dump($result);


}

startup();


?>
