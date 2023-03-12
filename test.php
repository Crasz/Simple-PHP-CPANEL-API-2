<?php

  require_once "cpanelApi.php";

  $cpanel = new wb_cpanel_api(
    'example-domain.com', // Your Domain
    '2083', // CPanel Port
    true, // true : using https (https://example-domain.com) | false : using http only
    'cpsess98187', // CPanel Username
    'U7HMR63FGY292DQZ4H5BFH16JLYMO01M' // CPanel Token (Generate from CPANEL Interface)
  );
  $cpanel->query(
    'ServerInformation', // CPANEL Module Name
    'get_information', // Spesific Function on Module
    '' // Extra Paramters (if any)
  );
  
  $result = $cpanel->getResult();

  print_r($result);
