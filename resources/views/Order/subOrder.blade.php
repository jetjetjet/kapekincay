<?php
  $rowIndex = $rowIndex ?? null;
?>

<tr class="subitem">
  <td>
    <p id="dtl[{{ $rowIndex }}][menuText]"></p>
  </td>
  <td>
    <p width="50%" id="dtl[{{ $rowIndex }}][menuPrice]"></p>
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" class=" text-right"/>
    <input type="text" name="dtl[{{ $rowIndex }}][qty]" style="width: 35px;" class=" text-right"/>
  </td>
  <td>
    <button type="button" class="btn btn-xs btn-danger" remove-row>
      <span class="fa fa-trash fa-fw"></span>
    </button>
  </td>
</tr>