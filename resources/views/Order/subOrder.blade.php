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
  $canUpd = Perm::can(['order_save']) && ($sub->oddelivertext ?? false);

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
  <td>
    @if(isset($rowIndex) && $sub->oddelivered)
      <input type="hidden" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}">
      <p class="text-center">{{$menuQty}}</p>
    @else
      <input type="number" class="text-right subQty" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}" style="width: 35px;" class="tPrice" sub-input >
    @endif
  </td>
  <td>
    <div id="Totalp">
      <input type="hidden" name="dtl[{{ $rowIndex }}][odtotalprice]" value="{{$menuTotalprice}}" />
      <p id="dtl[{{ $rowIndex }}][odtotalprice]" >{{ number_format($menuTotalprice,0) }}</p>
    </div>
  </td>
  <td>
    @if(isset($rowIndex) && $sub->oddelivered)
      <p class="text-center">{{ $menuRemark }}</p>
      <input type="hidden" value="{{$menuRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
    @else
      <input type="text" value="{{$menuRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
    @endif
  </td>
    <td>
    @if(isset($rowIndex) && $sub->oddelivered)
      <p class="text-center"><i class="far fa-check-square"></i></p>
    @else
      <button type="button" id="dtl[{{ $rowIndex }}][deleteRow]" title="Hapus Pesanan" style="border:none; background:transparent" remove-row>
        <span class="badge badge-danger">H <i class="far fa-times-circle"></i></span>
      </button>
      @if(isset($rowIndex) && !$sub->oddelivered)
        <button type="button" title="Pesanan Selesai Diantar" id="dtl[{{ $rowIndex }}][delivRow]" style="border:none; background:transparent" deliver-row>
          <span class="badge badge-info">S <i class="far fa-check-square"></i></span>
        </button>
      @endif
    @endif
  </td>
</tr>