<?php
  $rowIndex = $rowIndex ?? null;

  $menuText = $sub->odmenutext ?? null;
  $menuPrice = $sub->odprice ?? null;
  $menuid = $sub->odmenuid ?? null;
  $menuQty = $sub->odqty ?? null;
  $menuRemark = $sub->odremark ?? null;
?>

<tr class="subitem">
  <td>
    <p id="dtl[{{ $rowIndex }}][odmenutext]">{{$menuText}}</p>
  </td>
  <td>
    <p width="40%" id="dtl[{{ $rowIndex }}][odprice]">{{$menuPrice}}</p>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odprice]" value="{{$menuPrice}}" class=" text-right"/>
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odmenuid]" value="{{$menuid}}" class=" text-right"/>
    <input type="text" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}" style="width: 35px;" class=" text-right"/>
  </td>
  <td>
    <input type="hidden" value="{{$menuRemark}}" name="dtl[{{ $rowIndex }}][odremark]"/>
    <p id="dtl[{{ $rowIndex }}][odremark]"> {{$menuRemark}}</p>
  </td>
  <td>
    <button type="button" title="Hapus" style="border:none; background:transparent" remove-row>
    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
    </button>
  </td>
</tr>