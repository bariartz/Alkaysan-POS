<?php
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

function showMenu($user)
{
  $success = false;
  $menuList = [];

  try {
    $success = true;
    $getMenuFromDatabase = Menu::with(['childMenu'])->get();

    foreach($getMenuFromDatabase as $menu){
      $menuPermissionByLevel = json_decode($menu->permissions, true)['levels'];

      if(in_array($user->levels, $menuPermissionByLevel) && $menu->isParent){
        $menuList[] = $menu;
      }
    }
  } catch (\Exception $e) {
    $menuList = $e->getMessage();
  }

  return $menuList;
}