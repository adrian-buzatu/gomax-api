<?php

function getTextDefaults() {
  return array(
    'name' => 'Name',
    'code' => 'Code',
    'dashboard' => 'Dashboard',
    'manufacturer_code' => 'Manufacturer Code',
    'price' => 'Price',
    'description' => 'Description',
    'categories' => 'Categories',
    'stock' => 'Stock',
    'category' => 'Category',
    'active' => 'Active',
    'settings' => 'Settings',
    'products' => 'Products',
    'users' => 'Users',
    'action' => 'Action',
    'admin_users' => 'Admin Users',
    'admin_groups' => 'Admin Groups',
    '3_to_255_characters' => '3 to 255 characters',
    'add_product' => 'Add Product',
  );
}

function ___text($label) {
  $labelArr = explode("|", $label);
  $textDefaults = getTextDefaults();
  $textRaw = isset($textDefaults[$labelArr[0]]) ? $textDefaults[$labelArr[0]] : str_replace("_", " ", $labelArr[0]);
  if(isset($labelArr[1]) && function_exists($labelArr[1])) {
    $textRaw = ${$labelArr[1]}($textRaw);
  }
  return $textRaw;
}