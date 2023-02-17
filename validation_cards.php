<?php

class CardValidation {
  
  public function validate($cardNumber) {
    // Проверка эмитента карты
    if (preg_match('/^(41|42|43|44|45|46|47|48|49|14)/', $cardNumber)) {
      $emitent = 'VISA';
    } elseif (preg_match('/^(51|52|53|54|55|62|67)/', $cardNumber)) {
      $emitent = 'MasterCard';
    } else {
      $emitent = 'Unknown';
    }

    // Проверка контрольной суммы по стандарту ISO/IEC 7812
    $sum = 0;
    $length = strlen($cardNumber);
    $str = str_split($cardNumber);

    for ($i = 0; $i < $length; $i++) {
      $number = (int)$str[$i];
      if (($i + $length) % 2 == 0) {
        $number = $number * 2;
        if ($number > 9) {
          $number = $number - 9;
        }
      }
      $sum += $number;
    }

    if ($sum % 10 == 0) {
      $isValid = 'valid';
    } else {
      $isValid = 'not valid';
    }

    return [$isValid, $emitent];
  }
}

$cardValidation = new CardValidation();
// echo $cardValidation->validate('4750657776370372')[0] . ' ' . $cardValidation->validate('4750657776370372')[1] . "\n";
// echo $cardValidation->validate('4023901956789014')[0] . ' ' . $cardValidation->validate('4023901956789014')[1] . "\n";
// echo $cardValidation->validate('5569191777864116')[0] . ' ' . $cardValidation->validate('5569191777864116')[1] . "\n";
// echo $cardValidation->validate('725163728819929')[0] . ' ' . $cardValidation->validate('725163728819929')[1] . "\n";

// Output:
// valid VISA
// valid VISA
// valid MasterCard
// not valid Unknown