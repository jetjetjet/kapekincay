$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

let formatter = new Intl.NumberFormat();

function cloneModal($idModal){
  $('#uiModalInstance').remove();
  $('.modal-backdrop').remove();

  var $modal = $idModal.clone().appendTo('body');
  $modal.attr('id', 'uiModalInstance');

  return $modal;
}

function sweetAlert(header, message, status){
  return swalWithBootstrapButtons(
    header,
    message,
    status
  );
}

function gridDeleteInput(url, title, message, grid){
  const swalWithBootstrapButtons = swal.mixin({
    input: 'textarea',
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })
  
  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'question',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url,{'shiftdeleteremark':result.value}, function (data){
        if (data.status == 'success'){
          sweetAlert('Data Dihapus', data.messages[0], 'success')
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
        grid.ajax.reload();
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      sweetAlert('Batal','Shift batal hapus','error')
    } else {
      sweetAlert('Kesalahan!','Alasan menghapus harus di isi','error')
    }
  });
}

function gridDeleteInputvoid(url, title, message, grid){
  const swalWithBootstrapButtons = swal.mixin({
    input: 'textarea',
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })
  
  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'question',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url,{'ordervoidreason':result.value}, function (data){
        if (data.status == 'success'){
          sweetAlert('Pesanan dibatalkan', data.messages[0], 'success')
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
        grid.ajax.reload();
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      sweetAlert('Batal','Pesanan tidak dibatalkan','error')
    } else {
      sweetAlert('Kesalahan!','Alasan membatalkan harus di isi','error')
    }
  });
}

function gridDeleteRow(url, title, message, grid){
  const swalWithBootstrapButtons = swal.mixin({
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })

  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url, function (data){
        if (data.status == 'success'){
          sweetAlert('Data Dihapus', data.messages[0], 'success')
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
        grid.ajax.reload();
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      sweetAlert('Batal','Data meja batal hapus','error')
    }
  });
}

function showPopupOrder(paramBody, actFn){
  // Enables modal on current element.
  $(this).attr('data-toggle', 'modal');
  $(this).attr('data-target', '#uiModalInstance');

  let $modal = cloneModal($('#menuModal'));
  // $('#uiModalInstance').modal({
  //   // backdrop: 'static',
  //   keyboard: true
  // });
  $modal.on('show.bs.modal', function (){
      // Draws text.
      $modal.find('.modal-title').html('Tambah');
      $modal.find('#menuPopupText').html(paramBody['text']);
      $modal.find('#menuPopupPrice').html(paramBody['price']);
      $modal.modal({
          backdrop: 'static',
          keyboard: false
        });
      $('.modal-add-row')
      .click(function (){
        if (actFn){
          actFn();
        }
        $modal.modal('hide');
      });
  }).modal('show');
}

$.fn.registerAddRow = function ($rowTemplateContainer, $addRow, rowAddedFn, validationFn){
  $(this).each(function (){
    var $targetContainer = $(this),
      $tbody = $targetContainer.find('> tbody'),
      currentRowIndex = ($tbody.length ? $tbody : $targetContainer).children().length - 1;
    $targetContainer.attr('wbl-last-row-index', currentRowIndex);
    ($tbody.length ? $tbody : $targetContainer).children().each(function (idx){
      $(this).attr('wbl-curr-row-index', idx);
    });

    var $addRowBtns = typeof $addRow === 'function' ? $addRow($targetContainer) : $addRow;
    $addRowBtns.on('click', function (){
      if (validateAddRow()){
        addRow(true);
      }
    });
    $addRowBtns.on('addRow', function (){
      if (validateAddRow()){
        addRow(false);
      }
    });

    function validateAddRow(){
      //validate
      if (validationFn && typeof validationFn === 'function'){
        return validationFn();
      }

      return true; //no validation needed
    }

    function addRow(focus){
        // Clones.
      var $instance = cloneRow($targetContainer, $rowTemplateContainer);

      if (rowAddedFn && typeof rowAddedFn === 'function'){
        rowAddedFn($instance);
      }
      
      // Custom setup.
      $targetContainer.triggerHandler("row-added", [$instance]);

      if (focus){
        // Sets focus.
        $instance.find('input[type=text]:visible:not([readonly]):not([disabled]),select:visible:not([disabled]),textarea:visible:not([readonly])').not('.no-autofocus').first().focus();
      }
    }
  });
}

function cloneRow($targetContainer, $rowTemplateContainer, rowIndex){
  var $rowTemplateTbody = $rowTemplateContainer.find('> tbody'),
    $instance = ($rowTemplateTbody.length ? $rowTemplateTbody : $rowTemplateContainer).children(":first").clone(),
    lastIndexName = 'wbl-last-row-index',
    currIndexName = 'wbl-curr-row-index';
  if (!rowIndex){
    rowIndex = Number($targetContainer.attr(lastIndexName) || 0) + 1;
    $targetContainer.attr(lastIndexName, rowIndex);
    $instance.attr(currIndexName, rowIndex);
  }

  // Sets index on name recursively all the way up.
  var grandParentIndices = $targetContainer.parents('[' + currIndexName + ']').map(function (){
      return $(this).attr(currIndexName);
  }).toArray(),
  parentIndices = grandParentIndices.concat(rowIndex);
  $instance.find('[name*="[]"]').each(function (){
    var $input = $(this);
    parentIndices.forEach(function (val){
        $input.attr('name', $input.attr('name').replace('[]', '[' + val + ']'));
    });
  });
  $instance.find('[wblgstc-link-params*="[]"]').each(function (){
    var $input = $(this);
    parentIndices.forEach(function (val){
        //$input.attr('wblgstc-link-params', $input.attr('wblgstc-link-params').replace('[]', '[' + val + ']'));
        $input.attr('wblgstc-link-params', $input.attr('wblgstc-link-params').split('[]').join('[' + val + ']'));
    });
  });

  // Adds.
  var $tbody = $targetContainer.find('> tbody');
  ($tbody.length ? $tbody : $targetContainer).append($instance);

  return $instance;
}

$('table,.subitem-container')
.on('click', '[remove-row]', function (e){
  var $tr = $(this).closest('tr,.panel,.rowpanel'),
      $table = $tr.closest('table,.subitem-container');
  $table.triggerHandler("row-removing", [$tr]);
  $tr.remove();
  $table.triggerHandler("row-removed", [$tr]);

  $table.attr('data-has-changed', '1');
});

function inputSearch(inputId, urlSearch, width, callBack)
{
  let input = $(inputId);
  input.select2({
    placeholder: 'Cari...',
    width: width,
    ajax: {
      url: urlSearch,
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results:  $.map(data, function (item) {
            return callBack(item)
          })
        };
      },
      cache: false
    }
  })
}

function changeOptSelect2($select, url)
{
  $.ajax({
      type: 'GET',
      url: url
  }).then(function (data) {
      var option = new Option(data[0].text, data[0].id, true, true);
      $select.append(option).trigger('change');
  });
}