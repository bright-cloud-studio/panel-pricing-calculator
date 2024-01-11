<?php

/*
 * Bright Cloud Studio's Panel Pricing Calculator
 * Copyright (C) 2023-2024 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/panel-pricing-calculato
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
*/

namespace Bcs;

use Contao\Database;

class Hooks
{

  // Called when a form is submitted
  public function onProcessForm($submittedData, $formData, $files, $labels, $form)
  {
    if($formData['formID'] == 'calc_add_to_cart') {
      echo "BING";
      die();
    }
  }
  
}
