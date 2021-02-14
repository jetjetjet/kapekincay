<?php
  $rowIndex = $rowIndex ?? null;

  $menuText = $sub->odmenutext ?? null;
  $menuPrice = $sub->odprice ?? null;
  $menuid = $sub->odmenuid ?? null;
  $menuQty = $sub->odqty ?? null;
  $menuRemark = $sub->odremark ?? null;
  $menuDeliver = $sub->oddelivertext ?? null;
  $menuDelivered = $sub->oddelivered ?? null;
  $menuTotalprice = $sub->odtotalprice ?? null;
?>

<tr class="subitem">
  <td>
    <p id="dtl[{{ $rowIndex }}][odmenutext]">{{$menuText}}</p>
  </td>
  <td>
    <p width="40%" id="dtl[{{ $rowIndex }}][odprice]">{{ number_format($menuPrice,0) }}</p>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odprice]" value="{{$menuPrice}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odmenuid]" value="{{$menuid}}" class=" text-right"/>
  </td>
  @if(isset($rowIndex) && $sub->oddelivered)
    <td>
      <input type="hidden" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}">
      <p class="text-center">{{$menuQty}}</p>
    </td>
  @else
    <td>
      <input type="number" class="text-right subQty" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}" style="width: 35px;" class="tPrice" sub-input >
    </td>
  @endif
  <td>
    <div id="Totalp">
    <input type="hidden" name="dtl[{{ $rowIndex }}][odtotalprice]" value="{{$menuTotalprice}}" />
    <p id="dtl[{{ $rowIndex }}][odtotalprice]" >{{ number_format($menuTotalprice,0) }}</p>
    </div>
  </td>
  <td>
    <input type="text" value="{{$menuRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
  </td>
  <td>
  <!-- @if(isset($data->id))
    @if($sub->oddelivertext == 'Sedang Diproses')
      <button type="button" title="Hapus" style="border:none; background:transparent" remove-row>
      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
      </button>
    @endif
  @else
  @endif -->
  <button type="button" title="Hapus Pesanan" style="border:none; background:transparent" remove-row>
    <span class="badge badge-danger">H <i class="far fa-times-circle"></i></span>
  </button>
   &nbsp;
    <button type="button" title="Pesanan Selesai Diantar" style="border:none; background:transparent">
      <span class="badge badge-info">S <i class="far fa-check-square"></i></span>
    </button>
  <!-- @if(isset($data->id)) -->
  <!-- @endif -->
  </td>
</tr>